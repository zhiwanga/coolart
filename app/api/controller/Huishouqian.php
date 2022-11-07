<?php

namespace app\api\controller;

use app\api\model\Coll;
use app\common\library\helper;
use app\common\model\Goods;
use app\common\model\Integrals;
use app\common\model\Transaction;
use app\common\model\User;
use org\Http;
use org\huishouqian\alipay\AopClient;
use org\huishouqian\alipay\request\AlipaySystemOauthTokenRequest;
use org\huishouqian\request\TransContent;
use org\huishouqian\request\TransDataUtils;
use org\huishouqian\sdk\HttpUtil;
use org\huishouqian\sdk\ResUtil;
use qeq66\think\Jump;
use think\cache\driver\Redis;
use think\Exception;
use think\facade\Db;
use think\facade\View;
use app\api\model\Order as OrderModel;

/**
 * 慧收钱
 *
 * @auth 隔壁老吴
 * @date 2022/5/27 0027
 */
class Huishouqian
{
    use Jump;

    /**
     * 支付
     *
     * @auth 隔壁老吴
     * @date 2022/5/22 0022
     */
    public function pay()
    {
        $order_id = input('order_id');
        $type = input('type');
        if (empty($order_id) || empty($type)) {
            return view('error', ['msg' => '参数错误', 'return_url' => 'https://www.web3metadao.cn/#/pages/login/index']);
        }
        $order = Db::name('order')->where(['order_no' => $order_id])->order('order_id desc')->find();;
        if (empty($order)) {
            return view('error', ['msg' => '订单不存在或者已经支付', 'return_url' => 'https://www.web3metadao.cn/#/pages/login/index']);
        }
        if ($order['pay_status'] == 20) {
            return view('error', ['msg' => '订单已支付', 'return_url' => 'https://www.web3metadao.cn/#/pages/login/index']);
        }
        if ($type == 'wechat') {
            try {
                $this->wechat($order_id);
            } catch (Exception $e) {
                return view('error', ['msg' => $e->getMessage(), 'return_url' => 'https://www.web3metadao.cn/#/pages/login/index']);
            }
        } else {
            try {
                $this->alipay($order_id);
            } catch (Exception $e) {
                return view('error', ['msg' => $e->getMessage(), 'return_url' => 'https://www.web3metadao.cn/#/pages/login/index']);
            }
        }
        return view($type);
    }

    /**
     * 支付宝
     *
     * @auth 隔壁老吴
     * @date 2022/5/23 0023
     */
    public function alipay($order_id)
    {
        View::assign('appid', config('huishouqian.alipay.appid'));
        View::assign('order_id', $order_id);
    }

    /**
     * 获取微信小程序scheme
     *
     * @auth 隔壁老吴
     * @date 2022/5/22 0022
     */
    public function wechat($order_id)
    {
        $access_token = $this->getAccessToken();
        $res = $this->generatescheme($order_id, $access_token);
        View::assign('openlink', $res['openlink']);
    }

    /**
     * 获取支付宝小程序的user_id
     *
     * @auth 隔壁老吴
     * @date 2022/5/23 0023
     */
    public function getUserId()
    {
        $code = input('js_code');
        $c = new AopClient();
        $c->appId = config('huishouqian.alipay.appid');       // APPID
        $c->rsaPrivateKey = config('huishouqian.alipay.private_key');      // 生成的RSA私钥
        $c->alipayrsaPublicKey = config('huishouqian.alipay.public_key');   // 生成的RSA公钥
        $c->signType = "RSA2";
        $request = new AlipaySystemOauthTokenRequest();
        $request->setCode($code); //前端传来的code
        $request->setGrantType("authorization_code");
        $response = $c->execute($request);
        $user_id = $response->alipay_system_oauth_token_response->user_id;
        return json([
            'code' => 1,
            'msg' => '获取成功',
            'data' => ['openid' => $user_id]
        ]);
    }

    /**
     * 获取微信小程序AccessToken
     *
     * @auth 隔壁老吴
     * @date 2022/5/22 0022
     */
    public function getAccessToken()
    {
        $redis = new Redis(['select' => 2]);
        $access_token = $redis->get('access_token');
        if (empty($access_token) || $access_token['expires_time'] < time()) {
            // 获取
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . config('huishouqian.wechat.appid') . '&secret=' . config('huishouqian.wechat.appsecret');
            $res = Http::get($url);
            $res = json_decode($res, true);
            // 开始缓存
            $access_token = [
                'access_token' => $res['access_token'],
                'expires_time' => $res['expires_in'] + time(),
                'expires_in' => $res['expires_in']
            ];
            $redis->set('access_token', $access_token);
        }
        return $access_token['access_token'];
    }

    /**
     * 获取SCHEME
     *
     * @auth 隔壁老吴
     * @date 2022/5/22 0022
     */
    private function generatescheme($order_id, $access_token)
    {
        $data = [];
        $url = 'https://api.weixin.qq.com/wxa/generatescheme?access_token=' . $access_token;
        $data['jump_wxa'] = [
            'path' => 'pages/pay/pay',
            'query' => 'order_id=' . $order_id
        ];
        $data['is_expire'] = true;//到期失效：true，永久有效：false
        $data['expire_type'] = 0;//失效时间：0，失效间隔天数：1
        $data['expire_time'] = time() + 120; //is_expire 为 true 且 expire_type 为 0 时必填
        $data['expire_interval'] = '1';//is_expire 为 true 且 expire_type 为 1 时必填
        $data = json_encode($data);
        $headerArray = array("Content-type:application/json;charset='utf-8'", "Accept:application/json");
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headerArray);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        // print_r(json_decode($output,true)) ;
        return json_decode($output, true);
    }

    /**
     * 获取openid
     *
     * @auth 隔壁老吴
     * @date 2022/5/22 0022
     */
    public function getOpenid()
    {
        $js_code = input('js_code');
        $url = 'https://api.weixin.qq.com/sns/jscode2session';
        $data = [
            'appid' => config('huishouqian.wechat.appid'),
            'secret' => config('huishouqian.wechat.appsecret'),
            'grant_type' => 'authorization_code',
            'js_code' => $js_code
        ];
        $res = Http::get($url, $data);
        $res = json_decode($res, true);
        return json([
            'code' => 1,
            'msg' => '获取成功',
            'data' => ['openid' => $res['openid']]
        ]);
    }

    /**
     * 生成支付参数
     *
     * @auth 隔壁老吴
     * @date 2022/5/22 0022
     */
    public function getPay()
    {
        $openid = input('openid');
        $payType = input('payType');
        $order_id = input('order_id');
        if (empty($order_id) || empty($payType)) {
            return json([
                'code' => 0,
                'msg' => '参数错误',
                'data' => ''
            ]);
        }
        $order = Db::name('order')->where(['order_no' => $order_id])->order('order_id desc')->find();
        if (empty($order)) {
            return json([
                'code' => 0,
                'msg' => '订单不存在或者已经支付',
                'data' => ''
            ]);
        }
        if ($order['pay_status'] == 1) {
            return json([
                'code' => 0,
                'msg' => '订单已支付',
                'data' => ''
            ]);
        }
        $params = new TransContent();
        $params->_set("method", "POLYMERIZE_MAIN_SWEPTN");
        $params->_set("version", "1.0");
        $params->_set("format", "json");
        $params->_set("merchantNo", config('huishouqian.merchant'));
        $params->_set("signType", "RSA2");
        $params->_set("signContent", "");
        $params->_set("sign", "");
        $body = new TransContent();
        date_default_timezone_set("Asia/Shanghai");
        $body->_set("transNo", $order_id . rand(1000, 9999));//商户订单号
        $body->_set("payType", $payType == 'alipay' ? 'ALI_JSAPI' : 'WECHAT_APPLET');//支付类型
        $body->_set("orderAmt", bcmul($order['pay_price'], 100));//交易金额
        $body->_set("goodsInfo", "藏品购买");//商品信息摘要
        $body->_set("returnUrl", url('notify', [], true, true)->build());//后台回调地址
        $body->_set("requestDate", date("YmdHis"));//请求时间
        $body->_set("pageUrl", url('number', [], true, true)->build());//前台跳转地址
        $body->_set("extend", "");//原样返回字段
        $body->_set("memo", "");

        $memo = new TransContent();
        $memo->_set("openid", $openid);
        $memo->_set("appid", config('huishouqian.wechat.appid'));
        $memo->_set("spbillCreateIp", $this->getIp());//终端用户IP
        $memo->_set("longitude", "171.21");//经度
        $memo->_set("latitude", "22.33");//纬度
        $memojson = $memo->_getArray2Json();
        $body->_set("memo", $memojson);
        $bodyjson = TransDataUtils:: _array2Json($body->_getValues());
        $params->_set("signContent", $bodyjson);

        $rsaUtil = new ResUtil();
        $httpUtil = new HttpUtil();
        $key = config('huishouqian.key');
        $signStr = "method=" . $params->_get("method") . "&version=" . $params->_get("version") . "&format=" . $params->_get("format") . "&merchantNo=" . $params->_get("merchantNo") . "&signType=" . $params->_get("signType") . "&signContent=" . $params->_get("signContent") . "&key=" . $key;
        $encrypted = $rsaUtil->sign($signStr);
        // echo "[请求]RSA加签后内容：", $encrypted . "\r\n";
        $params->_set("sign", $encrypted);
        // $array = $params->_getValues();
        // echo "[客户端-发送] 内容：", TransDataUtils:: _array2Json($array) . "\r\n";
        $request_url = "https://api.huishouqian.com/api/acquiring";
        $httpResult = $httpUtil->post2($params->_getValues(), $request_url);
        $bodyArray = TransDataUtils:: _json2Array($httpResult);
        if (!$bodyArray['success']) {
            return json([
                'code' => 0,
                'msg' => $bodyArray['errorMsg'],
                'data' => []
            ]);
        }
        $result = json_decode($bodyArray['result'], true);
        if ($payType == 'alipay') {
            $qrCode = $result['qrCode'];
        } else {
            $qrCode = json_decode($result['qrCode'], true);
        }
        return json([
            'code' => 1,
            'msg' => '生成成功',
            'data' => $qrCode
        ]);
    }

    /**
     * 获取IP
     *
     * @auth 隔壁老吴
     * @date 2022/5/22 0022
     */
    public function getIp()
    {
        //strcasecmp 比较两个字符，不区分大小写。返回0，>0，<0。
        if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
            $ip = getenv('REMOTE_ADDR');
        } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $res = preg_match('/[\d\.]{7,15}/', $ip, $matches) ? $matches [0] : '';
        return $res;
    }

    /**
     * [write_log 写入日志]
     * @param  [type] $data [写入的数据]
     * @return [type]       [description]
     */
    function write_log($data)
    {
        $years = date('Y-m');
        //设置路径目录信息
        $url = dirname(__FILE__) . '/public/log/txlog/' . $years . '/' . date('Ymd') . '_request_log.txt';
        $dir_name = dirname($url);
        //目录不存在就创建
        if (!file_exists($dir_name)) {
            //iconv防止中文名乱码
            $res = mkdir(iconv("UTF-8", "GBK", $dir_name), 0777, true);
        }
        $fp = fopen($url, "a");//打开文件资源通道 不存在则自动创建
        fwrite($fp, date("Y-m-d H:i:s") . var_export($data, true) . "\r\n");//写入文件
        fclose($fp);//关闭资源通道
    }

    /**
     * 回调
     *
     * @auth 隔壁老吴
     * @date 2022/5/27 0027
     */
    public function notify()
    {
         $this->write_log($_REQUEST);
        $method = $_REQUEST["method"];
        $version = $_REQUEST["version"];
        $format = $_REQUEST["format"];
        $merchantNo = $_REQUEST["merchantNo"];
        $signType = $_REQUEST["signType"];
        $signContent = $_REQUEST["signContent"];
        $sign = $_REQUEST["sign"];
        $signContent = html_entity_decode($signContent);
        $signStr = "method=" . $method . "&version=" . $version . "&format=" . $format . "&merchantNo=" . $merchantNo . "&signType=" . $signType . "&signContent=" . $signContent . "&key=" . config('huishouqian.key');
        $rsaUtil = new ResUtil();
        $verifyResult = $rsaUtil->verify($signStr, $sign);
        if ($verifyResult) {
            $orderModel = new OrderModel();
            // $result = $_REQUEST;
            $signContent = \GuzzleHttp\json_decode($signContent, true);
            $orderNo = substr($signContent['transNo'], 0, -4);
            $order = $orderModel->where('order_no', $orderNo)->find();
            if (!empty($order)) {
                Db::startTrans();
                try {
                    if ($order['type'] == 0) {
                        if ($order['is_Box'] == 1) {
                            //调用抽取盲盒接口
                            $orderModel->drawboxs($order['user_id'], $order['order_no'], $order['goods_id']);
                        } else {
                            //调用新增藏品接口
                            $orderModel->addCollec($order['user_id'], $order['goods_id'], $order['order_no']);
                        }
                        $order->save(['pay_status' => 20, 'order_status' => 30]);
                    } else {
                        $transaction = Transaction::get($order['transaction_id']);
                        $config = Integrals::field('charges,copyright')->find();
                        $price = $transaction['price'] * (100 - ($config['charges'] + $config['copyright'])) / 100;
                        $transaction->save(['buyer_id' => $order['user_id'], 'buytime' => time(), 'status' => 1]); //交易状态修改
                        Coll::update(['user_id' => $order['user_id'], 'status' => 0], ['coll_id' => $transaction['coll_id']]); //转移到买房账上
                        User::setIncBalance($transaction['user_id'], $price); //增加卖方余额
                    }
                    $goodsModel = new Goods();
                    $goodsModel->setIncSales($order['goods_id']);
                    Db::name('goods_sku')
                        ->where('goods_id', $order['goods_id'])
                        ->dec('stock_num')
                        ->update();
                    Db::name('goods')
                        ->where('goods_id', $order['goods_id'])
                        ->dec('stock_total')
                        ->update();
                    Db::commit();
                } catch (\Exception $e) {
                    // 回滚事务
                    Db::rollback();
                    exit('fail');
                }
            }
            exit('success');
        } else {
            exit('fail');
        }
    }

    /**
     * 同步回调
     *
     * @auth 隔壁老吴
     * @date 2022/5/27 0027
     */
    public function number()
    {

    }
}
