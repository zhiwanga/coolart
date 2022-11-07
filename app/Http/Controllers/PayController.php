<?php

namespace App\Http\Controllers;

use Yansongda\Pay\Pay;
use Yansongda\Pay\Log;

class PayController
{
    protected $config = [
        'app_id' => 'xxx',
        'notify_url' => 'http://yansongda.cn/notify.php',
        'return_url' => 'http://yansongda.cn/return.php',

        'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAt2vKNqzSuYJwdyMNxcAUWVeQQ6NUb1S424dyNtf4IQn0NTML77/uTIbZ9YKf/LjtobRGYI/VxE5GWo0DtVWsooIPIvWw9yXoNUw1QAa/ZZd/+vDoc+IWTxFb7/4owf1zosaUqUuPu/gHns4zNudftD8ofpNhXCSPCO86hgzgULKdjvbpVkwiUvbhA44336Gjs/1VV95uxIHr8qBzR5MnV9P5+qWuUV4HEXu/En95RSr5zdJWYjU3moP9MwM3zbwKP2zZB7IjJzqaSJI3YR/a53tzj4DDtR5ccUsNsA2KxCskx4u15jXtvSLC2NbRWRkyzvIVluxZh4TOf5tbLh+94QIDAQAB',
        // 加密方式： **RSA2**

        //私钥
        'private_key' => 'MIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQC3a8o2rNK5gnB3Iw3FwBRZV5BDo1RvVLjbh3I21/ghCfQ1Mwvvv+5Mhtn1gp/8uO2htEZgj9XETkZajQO1Vayigg8i9bD3Jeg1TDVABr9ll3/68Ohz4hZPEVvv/ijB/XOixpSpS4+7+AeezjM251+0Pyh+k2FcJI8I7zqGDOBQsp2O9ulWTCJS9uEDjjffoaOz/VVX3m7EgevyoHNHkydX0/n6pa5RXgcRe78Sf3lFKvnN0lZiNTeag/0zAzfNvAo/bNkHsiMnOppIkjdhH9rne3OPgMO1HlxxSw2wDYrEKyTHi7XmNe29IsLY1tFZGTLO8hWW7FmHhM5/m1suH73hAgMBAAECggEBALBK6+qtJUSkOJBpor0kpupQHRrFi0wONDbb8k0AOz8WICBZ1ekzokjve+JbEIaI/+1iJhp9ZMrvKPOzgZLyq6DuPLovR03ko1FG1A+b7K4qdRmuHt3yudp5JtJ+iJIblt8m2o/1TksuU7SxH8xvii7mXXd7FCLKCKhftvJMwfUMdXHQDQhZZtDHqli73gAIGfsVLfrQ0Wz33Uh0TnKi0f3Ij5vGAbbNA7vm8Jqq7+E4mSDq/fTzcJG1m+6kiXUiDA4EVw6RHmt/pD9G3p1Pg63wpE45fL8b881Mgr+pSRFw4oKH9UY0SjEpaOKYg2a0i6hVP2vaPFWNf/SbNntU6jkCgYEA771VeCs6sBypZ3nQBzjH7Y6SalrtIPTdO4y1WgGeGrNtGDCUkkyenjtaK1nQ3XUY9cFVvEMykeGZ+MlhCB/GWRAO1FgtCyi2gEDHRAUu4nXQcEuNKFTBu8/qGBqvw+L91QOVXcQaPgulJDfhajex0kGb6JlylzgNlDzrghgKMrcCgYEAw9yU2yazlY+t3xt7ZaPwqmud6Q/J8A96QBSQaoDfufNoDhALcjUduD73hwS9fUII84zjemFleyI1AplYN/KD/0PVKH6/4auhuvNFwMaykNGhDfutAkk76mOvEmOtk8paXeclzlmmgerHyCXf5TxXVJmXI04NdxvFROZky5MqHCcCgYAChEpRaKLFFofwviag7MeshsKiuzGiFu/7oA6HU10FiryqdFga8RL5NkZMDjVTHdeVPOJZh5sQTP4AzpxmKW87oB+HuyU96L+BuSxeWm+TkQkPJLvmoH0oK5aZii4uMEPZ6J+4zEY6QIULTErhgpQrUOUCHBpH3B7IX5tZUkdJIQKBgQCelEwZnB4u6+P1DkU8qB3FfdieyN/RdFag54V+FdYOspr/N9Jsp8DmWmUDdr0NW5fvyZSsk4scxTdyYUHiGoLw/LTrUqR9TP9nZGyHhtkxE+l4bttHTJAsWIV5OCdjz6SW/v3HMyLzcWpMDQ5YhiCXKLwMI7gXRe7UuE3H3eFsZQKBgQDfEQHZDG+wUD743imTc7/vvJ2Xfo2lRi7ukbMNuymk3NnQqFHQyuogFbR+rrja6s2ZMKnJmnC6I0PrJlyI++HIIhHSli5Yn29mLsBIVT3euGjO7GVs49fBwFDNUmji9zVALQUlazVT7jJch/p9UsTT+i/uzlw0Zb0F+TUy41BsCQ==',
        // 使用公钥证书模式，请配置下面两个参数，同时修改ali_public_key为以.crt结尾的支付宝公钥证书路径，如（./cert/alipayCertPublicKey_RSA2.crt）
        // 'app_cert_public_key' => './cert/appCertPublicKey.crt', //应用公钥证书路径
        // 'alipay_root_cert' => './cert/alipayRootCert.crt', //支付宝根证书路径
        'log' => [ // optional

            'file' => './logs/alipay.log',
            'level' => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
            'type' => 'single', // optional, 可选 daily.
            'max_file' => 30, // optional, 当 type 为 daily 时有效，默认 30 天
        ],
        'http' => [ // optional
            'timeout' => 5.0,
            'connect_timeout' => 5.0,
            // 更多配置项请参考 [Guzzle](https://guzzle-cn.readthedocs.io/zh_CN/latest/request-options.html)
        ],
        'mode' => 'dev', // 设置此参数，将进入沙箱模式  默认dev

    ];

    public function index()
    {
        $order = [
            'out_trade_no' => time(),
            'total_amount' => '1',
            'subject' => 'test subject - 测试',
        ];

        $alipay = Pay::alipay($this->config)->web($order);

        return $alipay->send();// laravel 框架中请直接 `return $alipay`
    }

    public function return()
    {
        $data = Pay::alipay($this->config)->verify(); // 是的，验签就这么简单！

        // 订单号：$data->out_trade_no
        // 支付宝交易号：$data->trade_no
        // 订单总金额：$data->total_amount
    }

    public function notify()
    {
        $alipay = Pay::alipay($this->config);

        try{
            $data = $alipay->verify(); // 是的，验签就这么简单！
            // 请自行对 trade_status 进行判断及其它逻辑进行判断，在支付宝的业务通知中，只有交易通知状态为 TRADE_SUCCESS 或 TRADE_FINISHED 时，支付宝才会认定为买家付款成功。
            // 1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号；
            // 2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额）；
            // 3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）；
            // 4、验证app_id是否为该商户本身。
            // 5、其它业务逻辑情况
            Log::debug('Alipay notify', $data->all());
        } catch (\Exception $e) {
            // $e->getMessage();
        }

        return $alipay->success()->send();// laravel 框架中请直接 `return $alipay->success()`
    }
}