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

use app\api\service\passport\Login as LoginService;
use app\common\library\helper;
use app\common\model\UserIdcar;
use think\cache\driver\Redis;
use think\facade\Cache;
use yiovo\captcha\facade\CaptchaApi;
use app\api\model\User;
/**
 * 用户认证模块
 * Class Passport
 * @package app\api\controller
 */
class Passport extends Controller
{
    /**
     * 登录接口 (需提交手机号、短信验证码、第三方用户信息)
     * @return array|\think\response\Json
     * @throws \app\common\exception\BaseException
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function login()
    {
        // 执行登录
        $LoginService = new LoginService;

        if (!$LoginService->login($this->postForm())) {
            return $this->renderError($LoginService->getError());
        }
        // 用户信息
        $userInfo = $LoginService->getUserInfo();
        return $this->renderSuccess([
            'userId' => (int)$userInfo['user_id'],
            'token' => $LoginService->getToken((int)$userInfo['user_id'])
        ], '登录成功');
    }

    /**
     * 微信小程序快捷登录 (需提交wx.login接口返回的code、微信用户公开信息)
     * 业务流程：判断openid是否存在 -> 存在:  更新用户登录信息 -> 返回userId和token
     *                          -> 不存在: 返回false, 跳转到注册页面
     * @return array|\think\response\Json
     * @throws \app\common\exception\BaseException
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function loginMpWx()
    {
        // 微信小程序一键登录
        $LoginService = new LoginService;
        if (!$LoginService->loginMpWx($this->postForm())) {
            return $this->renderError($LoginService->getError());
        }
        // 获取登录成功后的用户信息
        $userInfo = $LoginService->getUserInfo();
        return $this->renderSuccess([
            'userId' => (int)$userInfo['user_id'],
            'token' => $LoginService->getToken((int)$userInfo['user_id'])
        ], '登录成功');
    }

    /**
     * 快捷登录: 微信小程序授权手机号登录
     * @return array|\think\response\Json
     * @throws \app\common\exception\BaseException
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function loginMpWxMobile()
    {
        // 微信小程序一键登录
        $LoginService = new LoginService;
        if (!$LoginService->loginMpWxMobile($this->postForm())) {
            return $this->renderError($LoginService->getError());
        }
        // 获取登录成功后的用户信息
        $userInfo = $LoginService->getUserInfo();
        return $this->renderSuccess([
            'userId' => (int)$userInfo['user_id'],
            'token' => $LoginService->getToken((int)$userInfo['user_id'])
        ], '登录成功');
    }


    /**
     * 手机号码登录
     */
    public function loginAll(){

        $LoginService=new LoginService;

        $posta=$this->postData();
        //获取手机号码
        $phone=$posta['phone'];
        $pass=$posta['password'];

        //传值进行验证
        $userInfo = $LoginService->loginas($phone,$pass);

        if($userInfo == false){
            return $this->renderError('账号或密码不正确，请重新输入！');
        }

        $token = $LoginService->getToken((int)$userInfo['user_id']);

        $get_token = Cache::get($userInfo['user_id'].'_token');

        if(!empty($get_token)){

            Cache::delete($get_token);

        }

        $store_id=10001;

        Cache::set($token, [
            'user' => [
                'user_id'=>$userInfo['user_id'],
            ],
            'store_id' => $store_id,
            'is_login' => true,
        ], 86400 * 30);

        Cache::set($userInfo['user_id'].'_token',$token);

        return $this->renderSuccess([
            'userId' => (int)$userInfo['user_id'],
            'phone' => $userInfo['mobile'],
            'nick_name' => $userInfo['nick_name'],
            'file_path' =>$userInfo['avatar_url'],
            'idname'=>$userInfo['idname'],
            'idcar'=>$userInfo['idcar'],
            'token' => $token,
            'address'=>$userInfo['address'],
            'issetpwd'=>$userInfo['trade_pass'] ? true : false,
        ], '登录成功');
    }


    /**
     * 注册
    */
    public function registerNre(){

        $LoginService=new LoginService;

        $posta=$this->postData();
        //获取手机号码
        $phone=$posta['phone'];
        $pass=$posta['password'];
        //获取推荐者的用户ID
        if (isset($posta['code'])){
            $codes=$posta['code'];
        }else{
            $codes=0;
        }

        // 验证短信验证码是否匹配
         if (!CaptchaApi::checkSms($posta['smsCode'],$phone)) {
            throwError('短信验证码不正确');
         }

        $userInfo = $LoginService->registerNreMode($phone,$pass,$codes);

        if($userInfo=="该账号已注册！"){
            return $this->renderError($userInfo);
        }

        if(is_array($userInfo) && $userInfo['status'] == 201){

            return $this->renderError($userInfo['message']);
        }

        $token = $LoginService->getToken((int)$userInfo['user_id']);

        return $this->renderSuccess([
                'userId' => (int)$userInfo['user_id'],
                'phone' => $userInfo['mobile'],
                'nick_name' => $userInfo['nick_name'],
                'file_path' =>$userInfo['file_path'],
                // 'idname'=>$userInfo['idname'],
                // 'idcar'=>$userInfo['idcar'],
                'token' => $token,
                'address'=>$userInfo['address']
            ], '注册成功');
    }



    /**
     * 忘记密码
     */
     public function forgetPass(){
        $LoginService=new LoginService;

        $posta=$this->postData();
        //获取手机号码
        $phone=$posta['phone'];
        $pass=$posta['password'];

        // 验证短信验证码是否匹配
         /*if (!CaptchaApi::checkSms($posta['smsCode'],$phone)) {
             throwError('短信验证码不正确');
         }*/

        $userInfo = $LoginService->forgetPassMode($phone,$pass);
        if($userInfo){
            return $this->renderSuccess(['data'=>$userInfo],"修改成功");
        }
        return $this->renderError("输入的手机号码未注册！");

     }

    /**
     * 手机号码登录
     */
    // public function loginAll(){

    //     $LoginService=new LoginService;

    //     $posta=$this->postData();
    //     //获取手机号码
    //     $phone=$posta['phone'];

    //     //获取推荐者的用户ID
    //     if (isset($posta['code'])){
    //         $codes=$posta['code'];
    //     }else{
    //         $codes=0;
    //     }

    //     //传值进行验证
    //     $userInfo = $LoginService->logina($phone,$codes);



    //     // 验证短信验证码是否匹配
    //     if (!CaptchaApi::checkSms($posta['smsCode'],$phone)) {
    //         throwError('短信验证码不正确');
    //     }


    //     if($userInfo['status']==201){
    //         return $this->renderError('201',$userInfo);
    //     }else{
    //         $token = $LoginService->getToken((int)$userInfo['user_id']);
    //         $store_id=10001;
    //         Cache::set($token, [
    //             'user' => [
    //                 'user_id'=>$userInfo['user_id'],
    //             ],
    //             'store_id' => $store_id,
    //             'is_login' => true,
    //         ], 86400 * 30);


    //         return $this->renderSuccess([
    //             'userId' => (int)$userInfo['user_id'],
    //             'phone' => $userInfo['mobile'],
    //             'nick_name' => $userInfo['nick_name'],
    //             'file_path' =>$userInfo['file_path'],
    //             'idname'=>$userInfo['idname'],
    //             'idcar'=>$userInfo['idcar'],
    //             'token' => $token,
    //             'address'=>$userInfo['address']

    //         ], '登录成功');
    //     }
    // }

        public function test()
        {
            $data = [
                'customerNum' => '10001116536583322991023',
                'shopNum' => '10001216536585039620385',
            ];

            $data1 = json_encode($data);
//        var_dump($data);exit;
//        var_dump(json_encode($data));exit;
            $url = 'https://openapi.duolabao.com/v3/order/refund/query';
            $url = 'https://baidu.com';
            $info = "secretKey="."03f8d570699c4ebf8ae12164dedc1e51e9fbf868" . "&timestamp=" . 1654564957 . "&path=/v3/order/refund/query&body=" . $data1;
//        $info = "secretKey=03f8d570699c4ebf8ae12164dedc1e51e9fbf868&timestamp=1654506297&path=/v3/order/refund/query&body={\"agentNum\":\"10001014472963095391100\",\"customerNum\":\"10001116536583322991023\",\"shopNum\":\"10001216536585039620385\"}";
//        var_dump($info);exit;
            $token = sha1($info);
            $token = strtoupper($token);
//        var_dump($token);exit;
            $header = [
                'Content-Type' => 'application/json',
                'accesskey' => '38931903c42d47cb95e20d6e079133202ac23322',
                'timestamp' => '1654564957',
                'token' => 'DF1419DEAE261BBA49870FBD04F0338AB6C0E533'
            ];
//        var_dump($header);
//        var_dump($data);exit;
            $data = [];
            $header = [
                'Content-Type' => 'application/json',
            ];
            $res = http_post($url,$data,$header);
            var_dump(json_decode($res,true));
        }

    public function ceshi()
    {
//        $redis = new Redis();
//        $goods = \app\common\model\Goods::where('is_delete' ,0)->where('is_box',0)->select();
//        foreach ($goods as $value){
//            $this->redisQueue($value['stock_total'],$value['goods_id']);
//        }
        $redis = new Redis();
        $name = '10099_list';    //队列名
        var_dump($redis->lLen($name));exit;
////        $len = $redis->get($name);   //队列长度
////        if ($num - $len > 0){
//        for ($i = 1; $i <= 2; $i++) {
//            $redis->lpush($name, $i);
//        }
////        }
//        var_dump($redis->lLen($name));exit;
    }

    public function card()
    {
        $card = UserIdcar::order('idcar_id')->select();
        $user = new User();
        foreach ($card as $value){
            $res = $user->where(['user_id' => $value['user_id']])->save(['idcar_id' => $value['idcar_id']]);
        }
    }

    /**
     * 队列处理
     * @param $num
     * @param $goods_id
     */
    public function redisQueue($num,$goods_id)
    {
        $redis = new Redis();
        $name = $goods_id . '_list';    //队列名
        $len = $redis->lLen($name);   //队列长度
        if ($num - $len > 0){
            for ($i = 1; $i <= $num - $len; $i++) {
                $redis->lpush($name, $i);
            }
        }else{
            $num1 = $len - $num;
            for ($i = 1; $i <= $num1; $i++) {
                $redis->rpop($name); //出列
            }
        }
    }

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
     * @return bool|string
     */
    public function jd(){
        $url = "https://openapi.duolabao.com/api/applyBindCard";

        $data = [
            "customerNum" => "10001116536583322991023",
            "userId" => "1000",
//            "requestNum" => "2022061023175016458335813",
            "requestNum" => "202206",
            "payerName" => "洪群",
            "idCardType" => "IDCARD",
            "idCardNo" => $this->AES("4408821995020",$this->getAesKey('03f8d570699c4ebf8ae12164dedc1e51e9fbf868')),
            "cardNo" => $this->AES("62146221210074",$this->getAesKey('03f8d570699c4ebf8ae12164dedc1e51e9fbf868')),
            "phone" => $this->AES("13824896",$this->getAesKey('03f8d570699c4ebf8ae12164dedc1e51e9fbf868'))
        ];
//        var_dump($data);exit;
        $time = time();
        $body  = json_encode($data,JSON_UNESCAPED_SLASHES);
        $info = "secretKey=" . "03f8d570699c4ebf8ae12164dedc1e51e9fbf868" . "&timestamp=" . $time . "&path=/api/applyBindCard&body=" . $body;
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
//        var_dump($res);
//        return json_decode($res, true);
    }

    //绑卡确认
    public function bindCard()
    {
        $url = "https://openapi.duolabao.com/api/confirmBindCard";

        $data = [
            "customerNum" => "10001116536583322991023",
            "requestNum" => "2022061023175016458335813",
            "validateCode" => "168005"
        ];
//        var_dump($data);exit;
        $time = time();
        $body  = json_encode($data,JSON_UNESCAPED_SLASHES);
        $info = "secretKey=" . "03f8d570699c4ebf8ae12164dedc1e51e9fbf868" . "&timestamp=" . $time . "&path=/api/confirmBindCard&body=" . $body;
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

    //银行卡解绑
    public function unBindCard()
    {
        $url = "https://openapi.duolabao.com/api/unBindCard";

        $data = [
            "customerNum" => "10001116536583322991023",
            "requestNum" => "2022061023175016458335812",
            "userId" => "10001116546006965353762",
            "bindId" => "f16322eff9144e2b9ab2b010cdfc5eaf"
        ];
//        var_dump($data);exit;
        $time = time();
        $body  = json_encode($data,JSON_UNESCAPED_SLASHES);
        $info = "secretKey=" . "03f8d570699c4ebf8ae12164dedc1e51e9fbf868" . "&timestamp=" . $time . "&path=/api/unBindCard&body=" . $body;
        $token = sha1($info);
        $token = strtoupper($token);

        $header = [
            'Content-Type: application/json',
            "charset:'utf-8'",
            'accesskey: 38931903c42d47cb95e20d6e079133202ac23322',
            'timestamp: ' . $time,
            'token: ' . $token
        ];

        $res = post($url, $body, $header);
        return $res;
    }

    //快捷预下单
    public function payWithCheck()
    {
        $url = "https://openapi.duolabao.com/api/applyQuickPayWithCheck";
        $orderNo = random(12);
        $data = [
            "version" => "V4.0",
            "customerNum" => "10001116536583322991023",
            "shopNum" => "10001216536585039620385",
            "requestNum" => "2022061023175016458335816",
            "orderAmount" => "0.01",
            "clientIp" => "127.0.0.1",
            "callbackUrl" => "https://www.web3metadao.cn/index.php/api/passport/callbackUrl",
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
        $info = "secretKey=" . "03f8d570699c4ebf8ae12164dedc1e51e9fbf868" . "&timestamp=" . $time . "&path=/api/applyQuickPayWithCheck&body=" . $body;
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
        $url = "https://openapi.duolabao.com/api/confirmQuickPay";

        $data = [
            "customerNum" => "10001116536583322991023",
            "requestNum" => "2022061023175016458335816",
            "validateCode" => "158892",
        ];
//        var_dump($data);exit;
        $time = time();
        $body  = json_encode($data,JSON_UNESCAPED_SLASHES);
        $info = "secretKey=" . "03f8d570699c4ebf8ae12164dedc1e51e9fbf868" . "&timestamp=" . $time . "&path=/api/confirmQuickPay&body=" . $body;
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
            'accesskey: 38931903c42d47cb95e20d6e079133202ac23322',
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

}
