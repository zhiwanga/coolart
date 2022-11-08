<?php


namespace app\common\library;

use think\facade\Db;

require_once '../addons/sd/sdk/QuickPay.php';
require_once '../addons/sd/sdk/H5Cashier.php';

/**
 * 第二支付
 * Class Sd
 * @package app\common\library
 */
class Sd
{

    private $http_url = 'https://nft4hd.dingfengkj.com/';

    public function pay($params){

        $client = new \QuickPay;

        $totalAmount = '000000000000';

        $price_info = explode('.',$params['price']);

        $price_num = strlen($price_info[0]);

        $last_num = 0;

        if(isset($price_info[1])){

            $last_num = strlen($price_info[1]);

        }

        $m = '00';

        if($last_num >= 1){

            $m = $price_info[1];

        }

        $pay_str = $price_info[0].$m;

        $totalAmount = substr($totalAmount,0,strlen($totalAmount)-strlen($pay_str)).$pay_str;

        // 参数
        $client->body = array(
            'userId'       => $params['user_id'],
            'orderCode'    => $params['ordersn'],
            'orderTime'    => date('YmdHis'),
            'totalAmount'  => $totalAmount,
            'subject'      => '用户消费',
            'body'         => $params['goods_name'],
            'currencyCode' => 156,
            'notifyUrl'    => $this->http_url.$params['notify_url'],
            'frontUrl'     => 'http://nft4.dingfengkj.com/h5',
            'extend'       => ''
        );

        // 返回结果
        $form = $client->form('index');

        $res = Db::name('pay_log')->insertGetId(['post_data'=>serialize($form['data']),'url'=>$form['url']]);

        return $res;
    }

    public function h5($params){

        $client = new \H5Cashier();
        // 参数
        $client->body = array(
            'orderCode'   => date('YmdHis', time()) . '0601',
            'totalAmount' => '000000000012',
            'subject'     => '话费充值',
            'body'        => '用户购买话费0.12',
            'notifyUrl'   => 'http://192.168.62.61/sandpay-qr-phpdemo/notifyurl.php',
            'frontUrl'    => 'http://192.168.62.61/sandpay-qr-phpdemo/notifyurl.php',
        );
        // 生成表单
        $form = $client->form('orderCreate');
        echo $form;die;

    }
}