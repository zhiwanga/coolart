<?php
// +----------------------------------------------------------------------
// | 萤火商城系统 [ 致力于通过产品和服务，帮助商家高效化开拓市场 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2021 https://www.yiovo.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed 这不是一个自由软件，不允许对程序代码以任何形式任何目的的再发行
// +----------------------------------------------------------------------
// | Author: 萤火科技 <admin@yiovo.com>
// +----------------------------------------------------------------------
declare (strict_types=1);

namespace app\api\controller;

use app\api\model\Coll;
use app\api\model\Order as OrderModel;
use app\api\model\Setting as SettingModel;
use app\api\model\UserBank;
use app\common\library\helper;
use app\common\model\Integrals;
use app\common\model\Order;
use app\common\model\Transaction as TransactionModel;
use app\common\service\Transaction;
use app\store\model\Express as ExpressModel;
use app\common\enum\order\PayType as OrderPayTypeEnum;
use cores\exception\BaseException;
use app\store\model\Setting;
use app\api\service\User;
use think\cache\driver\Redis;
use think\facade\Db;
use think\response\Json;
use app\api\model\User as UserModel;
use app\common\model\Goods;


/**
 * 京东银行卡支付
 * Class JdPay
 * @package app\api\controller
 */
class JdPay extends Controller
{

    public $accesskey = "xxx";    //公钥
    public $secretKey = "xxx";    //私钥
    public $merchant_num = "xxx";   //商户编号
    //md5加密私钥作为AES加密的密钥
    public function getAesKey($key){
        return substr(md5($key),8,24);
    }

    //AES加密
    function AES($data,$aesKey){
        $encrypt = openssl_encrypt($data, 'AES-128-ECB', $aesKey, 0);
        return $encrypt;
    }

    /**
     * 快捷绑卡
     * @return Json
     * @throws BaseException
     */
    public function bindCard(){
        $userId = User::getCurrentLoginUserId();
        $url = "https://openapi.duolabao.com/api/applyBindCard";
        $post = $this->postData();
        if (empty($post['payerName']) || empty($post['idCardNo']) || empty($post['cardNo']) || empty($post['phone'])){
            return $this->renderError("参数错误！");
        }
        //判断卡号是否已被绑定
        $user_bank = UserBank::where(['cardNo' => $post['cardNo'], 'status' => 10, 'is_delete' => 0])->find();
        if (!empty($user_bank)){
            return $this->renderError("当前银行卡已被绑定！");
        }
        $orderModel = new Order();
        $orderNo = $orderModel->orderNo();
        $data = [
            "customerNum" => $this->merchant_num,  //商户编号
            "userId" => (string)$userId,            //用户编号
            "requestNum" => $orderNo,       //订单号
            "payerName" => $post['payerName'],      //真实姓名
            "idCardType" => "IDCARD",       //身份证类型
            "idCardNo" => $this->AES($post['idCardNo'],$this->getAesKey($this->secretKey)),     //身份证号
            "cardNo" => $this->AES($post['cardNo'],$this->getAesKey($this->secretKey)),         //银行卡号
            "phone" => $this->AES($post['phone'],$this->getAesKey($this->secretKey))            //手机号
        ];
        $time = time();
        $body  = json_encode($data);
        $info = "secretKey=" . "xxx" . "&timestamp=" . $time . "&path=/api/applyBindCard&body=" . $body;
        $token = sha1($info);
        $token = strtoupper($token);

        $header = [
            'Content-Type: application/json',
            "charset: 'utf-8'",
            'accesskey:' . $this->accesskey,
            'timestamp: ' . $time,
            'token: ' . $token
        ];

        $res = post($url, $body, $header);
        $res = json_decode($res,true);
        if (empty(!$res) && $res['success'] == 'success'){
            $bank = [
                'user_id' => $userId,
                'requestNum' => $orderNo,
                'idCardNo' => $post['idCardNo'],
                'cardNo' => $post['cardNo'],
                'phone' => $post['phone'],
                'payerName' => $post['payerName'],
                'create_time' => time()
            ];
            $info = UserBank::create($bank);
            return $this->renderSuccess(['bank_id' => $info->id]);
        }
        if ($res['code'] == '000'){
            return $this->renderError('请核实输入信息是否正确！');
        }
        return $this->renderError($res['msg']);
    }

    //绑卡确认
    public function confirmBindCard()
    {
        $userId = User::getCurrentLoginUserId();
        $url = "https://openapi.duolabao.com/api/confirmBindCard";
        $post = $this->postData();
        if (empty($post['bank_id']) || empty($post['code'])){
            return $this->renderError("参数错误！");
        }
        //获取用户银行信息
        $bank = UserBank::where(['id' => $post['bank_id'],'is_delete' => 0,'status' => 0,'user_id' => $userId])->find();
        if (empty($bank)){
            return $this->renderError("银行信息不存在，请刷新再绑定！");
        }
        $data = [
            "customerNum" => $this->merchant_num,  //商户编号
            "requestNum" => $bank['requestNum'],   //订单编号
            "validateCode" => $post['code']        //验证码
        ];

        $time = time();
        $body  = json_encode($data);
        $info = "secretKey=" . "xxx" . "&timestamp=" . $time . "&path=/api/confirmBindCard&body=" . $body;
        $token = sha1($info);
        $token = strtoupper($token);

        $header = [
            'Content-Type: application/json',
            "charset='utf-8'",
            'accesskey: xxx',
            'timestamp: ' . $time,
            'token: ' . $token
        ];

        $res = post($url, $body, $header);
        $res = json_decode($res,true);
        if (empty(!$res) && $res['success'] == true && $res['bindStatus'] == 'SUCCESS'){
            $bank->save(['bindId' => $res['bindId'],'status' => 10]);   //更新状态和绑卡ID
            return $this->renderSuccess('绑定成功');
        }
        return $this->renderError($res['msg']);
    }

    //快捷预下单
    public function payWithCheck()
    {
        $url = "https://openapi.duolabao.com/api/applyQuickPayWithCheck";
        $data = [
            "version" => "V4.0",
            "customerNum" => "xxx",
            "shopNum" => "xxx",
            "requestNum" => "2022061023175016458335816",
            "orderAmount" => "0.01",
            "clientIp" => "127.0.0.1",
            "callbackUrl" => "xxx/index.php/api/passport/callbackUrl",
            "userId" => "10001116546006965353762",
            "bindId" => "f16322eff9144e2b9ab2b010cdfc5eaf",
            "goodsName" => "测试",
            "goodsQuantity" => "1",
            "terminalType" => "IMEI",
            "terminalId" => "122121212121",
            "userAccount" => "13824896948",
            "appType" => "H5",
            "appName" => "某宝",
            "tradeScene" => "QUICKPAY",
            "source" => "API",
        ];
//        var_dump($data);exit;
        $time = time();
        $body  = json_encode($data,JSON_UNESCAPED_SLASHES);
        $info = "secretKey=" . "xxx" . "&timestamp=" . $time . "&path=/api/applyQuickPayWithCheck&body=" . $body;
        $token = sha1($info);
        $token = strtoupper($token);

        $header = [
            'Content-Type: application/json',
            "charset='utf-8'",
            'accesskey: 38931903c42d47cb95e20d6e079133202ac23322',
            'timestamp: ' . $time,
            'token: ' . $token
        ];

        $res = post($url, $body, $header);
        return $res;
    }

    //支付确认
    public function quickPay()
    {
        $userId = User::getCurrentLoginUserId();
        $url = "https://openapi.duolabao.com/api/confirmQuickPay";
        $post = $this->postData();
        if (empty($post['bank_id']) || empty($post['code']) || empty($post['requestNum'])){
            return $this->renderError("参数错误！");
        }

        $order = OrderModel::where(['order_no' => $post['requestNum'],'is_delete' => 0,'pay_status' => 10,'order_status' => 10,'user_id' => $userId])->find();

        if (empty($order)){
            return $this->renderError('订单不存在，请刷新重试！');
        }
        $data = [
            "customerNum" => $this->merchant_num,  //商户编号
            "requestNum" => $order['order_no'],
            "validateCode" => $post['code'],
        ];

        $time = time();
        $body  = json_encode($data,JSON_UNESCAPED_SLASHES);
        $info = "secretKey=" . "xxx" . "&timestamp=" . $time . "&path=/api/confirmQuickPay&body=" . $body;
        $token = sha1($info);
        $token = strtoupper($token);

        $header = [
            'Content-Type: application/json',
            "charset='utf-8'",
            'accesskey: 38931903c42d47cb95e20d6e079133202ac23322',
            'timestamp: ' . $time,
            'token: ' . $token
        ];

        $res = post($url, $body, $header);
        $res = json_decode($res,true);
        if (empty(!$res) && $res['success'] == true && $res['orderStatus'] == 'SUCCESS'){
            return $this->renderSuccess('支付成功');
        }
        return $this->renderError($res['msg']);
    }

    /**
     * 绑卡查询
     * @return bool|string
     */
    public function queryBindCard()
    {
        $url = "https://openapi.duolabao.com/api/queryBindCard";

        $data = [
            "customerNum" => "10001116536583322991023",
            "requestNum" => "2022061023175016458335812",
            "userId" => "10001116546006965353762",
            "bindId" => "f16322eff9144e2b9ab2b010cdfc5eaf",
        ];
//        var_dump($data);exit;
        $time = time();
        $body  = json_encode($data,JSON_UNESCAPED_SLASHES);
        $info = "secretKey=" . "03f8d570699c4ebf8ae12164dedc1e51e9fbf868" . "&timestamp=" . $time . "&path=/api/queryBindCard&body=" . $body;
        $token = sha1($info);
        $token = strtoupper($token);

        $header = [
            'Content-Type: application/json',
            "charset='utf-8'",
            'accesskey: 38931903c42d47cb95e20d6e079133202ac23322',
            'timestamp: ' . $time,
            'token: ' . $token
        ];

        $res = post($url, $body, $header);
        return $res;
    }

    /**
     * 查询订单
     * @return bool|string
     */
    public function query()
    {
        $url = "https://openapi.duolabao.com/v3/order/query";

        $data = [
            "customerNum" => "10001116536583322991023",
            "requestNum" => "2022061023175016458335815",
            "shopNum" => "10001216536585039620385",
        ];
//        var_dump($data);exit;
        $time = time();
        $body  = json_encode($data,JSON_UNESCAPED_SLASHES);
        $info = "secretKey=" . "03f8d570699c4ebf8ae12164dedc1e51e9fbf868" . "&timestamp=" . $time . "&path=/v3/order/query&body=" . $body;
        $token = sha1($info);
        $token = strtoupper($token);

        $header = [
            'Content-Type: application/json',
            "charset='utf-8'",
            'accesskey: xxx',
            'timestamp: ' . $time,
            'token: ' . $token
        ];

        $res = post($url, $body, $header);
        return $res;
    }

    public function callbackUrl()
    {
        $content = file_get_contents("php://input");
        $content = json_decode($content,true);
        helper::logInfo('京东快捷支付', $content);
    }

    /**
     * 获取绑卡信息
     * @return Json
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function info()
    {
        $userId = User::getCurrentLoginUserId();
        $data = UserBank::where(['user_id' => $userId,'status' => 10,'is_delete' => 0])->field('id,cardNo')->select();
        $xing ='************';
        if(!empty($data)){
            foreach ($data as &$value){
                $value['cardNo'] = substr_replace($value['cardNo'],$xing,2,13);
            }
        }
        return $this->renderSuccess(['bank' => $data],'获取成功！');
    }
}