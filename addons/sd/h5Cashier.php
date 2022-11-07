<?php
date_default_timezone_set('Asia/Shanghai');
header('Content-type:text/html;charset=utf-8');
require './class/common.php';
require './class/H5Cashier.php';

class Test
{
    // 统一下单
    public function orderCreate()
    {
        $client = new H5Cashier;
        // 参数
        $client->body = array(
            'orderCode'   => date('YmdHis', time()) + '0601',
            'totalAmount' => '000000000012',
            'subject'     => '话费充值',
            'body'        => '用户购买话费0.12',
            'notifyUrl'   => 'http://192.168.62.61/sandpay-qr-phpdemo/notifyurl.php',
            'frontUrl'    => 'http://192.168.62.61/sandpay-qr-phpdemo/notifyurl.php',
        );
        // 生成表单
        $form = $client->form('orderCreate');
        echo $form;
    }

    // 退款申请
    public function orderRefund()
    {
        $client = new H5Cashier;
        // 参数
        $client->body = array(
            'orderCode'          => 'Y20181204170925675836', //新的订单号
            'oriOrderCode'       => '2017091551421977', //原订单号
            'refundAmount'       => '000000000012', //退款金额
            'refundMarketAmount' => '000000000012', //退营销金额
            'notifyUrl'          => 'http://192.168.22.171/sandpay-qr-phpdemo.bak/test/dist/notifyUrl.php',
            'refundReason'       => 'test',
            'extend'             => ''
        );
        // 返回结果
        $ret = $client->request('orderRefund');
        echo '<pre>';
        print_r($ret);
        echo '</pre>';
    }

    // 订单查询
    public function orderQuery()
    {
        $client = new H5Cashier;
        // 参数, 每次需要重新赋值
        $client->body = array(
            'orderCode' => '2017091551423473', //订单号
            'extend'    => ''
        );
        // 返回结果
        $ret = $client->request('orderQuery');
        echo '<pre>';
        print_r($ret);
        echo '</pre>';
    }

    public function orderConfirmPay()
    {
        $client = new H5Cashier;
        // 参数, 每次需要重新赋值
        $client->body = array(
            'orderCode'    => 'Y20181204170925675836', //新的订单号
            'oriOrderCode' => '2017091551421977', //原订单号
            'userId'       => '1', //付款方Id
            'deliveryNo'   => '', //物流/快递单号
            'extend'       => ''
        );
        // 返回结果
        $ret = $client->request('orderConfirmPay');
        echo '<pre>';
        print_r($ret);
        echo '</pre>';
    }

    // 异步通知通用接口
    public function notify()
    {
        // 实例化客户端
        $client = new H5Cashier;

        $sign = $_POST['sign']; //签名
        $data = stripslashes($_POST['data']); //支付数据

        // 验签
        try {
            $verifyFlag = $client->verify($data, $sign);
            if (!$verifyFlag) throw new Exception('签名失败');
        } catch (\Exception $e) {
            exit('签名失败');
        }

        // 回调数据
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }

    public function orderMcAutoNotice()
    {
        $client = new H5Cashier;
        // 参数
        $client->body = array(
            'orderCode'  => 'Y201805071725592',
            'noticeType' => '00',
        );
        // 返回结果
        $ret = $client->request('orderMcAutoNotice');
        echo '<pre>';
        print_r($ret);
        echo '</pre>';
    }

    public function clearfileDownload()
    {
        // 实例化客户端
        $client = new H5Cashier;
        // 参数
        $client->body = array(
            'clearDate' => '20200611', // 结算日期
            'fileType'  => '1', // 文件返回类型
            'extend'    => ''
        );
        // 返回值
        $ret = $client->request('clearfileDownload');
        echo '<pre>';
        print_r($ret);
        echo '</pre>';
    }
}

$test = new Test();
// $test->orderCreate();
// $test->orderRefund();
// $test->orderQuery();
// $test->orderConfirmPay();
// $test->clearfileDownload();