<?php

require_once '../addons/sd/sdk/Common.php';

class QuickPay extends Common
{
    protected $productId = '00000016';

    // 参数映射
    protected function apiMap()
    {
        return array(
            'index'             => array(
                'method' => 'sandPay.fastPay.quickPay.index',
                'url'    => '/fastPay/quickPay/index ',
            ),
            'orderQuery'        => array(
                'method' => 'sandpay.trade.query',
                'url'    => '/gateway/api/order/query',
            ),
            'orderRefund'       => array(
                'method' => 'sandpay.trade.refund',
                'url'    => '/gateway/api/order/refund',
            ),
            'clearfileDownload' => array(
                'method' => 'sandpay.trade.download',
                'url'    => '/gateway/api/clearfile/download',
            ),
        );
    }
}
