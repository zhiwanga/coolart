<?php


namespace app\common\library;


use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Random;
use think\facade\Request;

class H5pay
{
    protected $request = 'https://changqing.tech/';

    public function pay($params)
    {
        $data = [
            'version' => 10,
            'mer_no' => '6888805045980', //商户号
            'mer_key' => 'qhElsM91RHhzdfwO8IdGeLFP52RulL2eKRKvkH3KCYc/D2HqmfxIOfknJtpDMOJmR0cMiVHjD/o=', // 商户私钥通过安卓APK工具解析出来的KEY1
            'mer_order_no' => $params['ordersn'], //商户唯一订单号
            'create_time' => date('YmdHis'),
            'expire_time' => date('YmdHis', time() + 30 * 60),
            'order_amt' => $params['price'], //订单支付金额
            'notify_url' => $this->request.$params['notify_url'], //订单支付异步通知
            'return_url' => $this->request.'h5', //订单前端页面跳转地址
            'create_ip' => str_replace('.','_',Request::instance()->ip()),
            'goods_name' => $params['goods_name'],
            'store_id' => '000000',
            'product_code' => '06030003', // 产品编码: 云函数h5：02010006；支付宝H5：02020002；微信公众号H5：02010002；
            //一键快捷：05030001；H5快捷：06030001；支付宝扫码：02020005  ；快捷充值：  06030003;
            //电子钱包【云账户】：开通账户并支付product_code应为：04010001；消费（C2C）product_code 为：04010003 ; 我的账户页面 product_code 为：00000001
            'clear_cycle' => '3',
            'pay_extra' => json_encode([
                "userId"     => "{$params['user_id']}",
                'userName'   => "{$params['username']}",
                'idCard'     => "{$params['idCard']}",
            ]),//resourceAppid：小程序 AppID ;resourceEnv：云开发环境 ID，云函数所需参数，如不清楚请商户群里详问杉德联调人员
            'accsplit_flag' => 'NO',
            'jump_scheme' => 'sandcash://scpay',
            'meta_option' => json_encode([["s" => "Android", "n" => "wxDemo", "id" => "com.pay.paytypetest", "sc" => "com.pay.paytypetest"]]),
            'sign_type' => 'MD5'

        ];
        $temp = $data;
        unset($temp['goods_name']);
        unset($temp['jump_scheme']);
        unset($temp['expire_time']);
        unset($temp['product_code']);
        unset($temp['clear_cycle']);
        unset($temp['meta_option']);

        file_put_contents('log.txt', date('Y-m-d H:i:s', time()) . " 签名串:" . $this->getSignContent($temp) . "&key=OfVZ1x+EcLjZYClVhkD9gqlWnO3" . "\r\n", FILE_APPEND); // key对应商户私钥通过安卓APK工具解析出来的MD5KEY

        $sign = strtoupper(md5($this->getSignContent($temp) . "&key=meJ7enP+Kc/D8HUMDKwmN1yZDtz7ObPwD6/VxK8RpKOZh51vbT8cgfLR1NZrYmmt46228fNlmvvpohxApF94jH8OGjHl/ZGOETFVgovo7qboEBRk/xOpD5RfCFpx9iqChbOFomTAO3Bixuz+UKFTiw=="));  // key对应商户私钥通过安卓APK工具解析出来的MD5KEY
        $data['sign'] = $sign;

        $query = http_build_query($data);
        $payurl = "https://sandcash.mixienet.com.cn/pay/h5/quicktopup?" . $query;  //          云函数h5：applet；支付宝H5：alipay；微信公众号H5：wechatpay； //一键快捷：fastpayment；H5快捷：unionpayh5；支付宝扫码：alipaycode  ;快捷充值：quicktopup ；电子钱包【云账户】：cloud

        return $payurl; // 返回支付url
    }

    function getSignContent($params)
    {
        ksort($params);

        $stringToBeSigned = "";
        $i = 0;
        foreach ($params as $k => $v) {
            if (false === $this->checkEmpty($v) && "@" != substr($v, 0, 1)) {

                if ($i == 0) {
                    $stringToBeSigned .= "$k" . "=" . "$v";
                } else {
                    $stringToBeSigned .= "&" . "$k" . "=" . "$v";
                }
                $i++;
            }
        }

        unset ($k, $v);
        return $stringToBeSigned;
    }

    function checkEmpty($value)
    {
        if (!isset($value))
            return true;
        if ($value === null)
            return true;
        if (trim($value) === "")
            return true;

        return false;
    }

}
