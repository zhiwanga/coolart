<?php
require_once '../addons/sd/sdk/Common.php';
class H5Cashier extends Common
{
    protected $productId = '00002000';

    // 参数映射
    protected function apiMap()
    {
        return array(
            'orderCreate'       => array(
                'method' => 'sandpay.trade.orderCreate',
                'url'    => '/gw/web/order/create',
            ),
            'orderRefund'       => array(
                'method' => 'sandpay.trade.refund',
                'url'    => '/gw/api/order/refund',
            ),
            'orderQuery'        => array(
                'method' => 'sandpay.trade.query',
                'url'    => '/gw/api/order/query',
            ),
            'orderConfirmPay'   => array(
                'method' => 'sandpay.trade.confirmPay',
                'url'    => '/gw/api/order/confirmPay',
            ),
            'orderMcAutoNotice' => array(
                'method' => 'sandpay.trade.notify',
                'url'    => '/gateway/api/order/mcAutoNotice',
            ),
            'clearfileDownload' => array(
                'method' => 'sandpay.trade.download',
                'url'    => '/gateway/api/clearfile/download',
                // 普通商户（即时交易）请求地址：https://cashier.sandpay.com.cn/gateway/api/clearfile/download
                // 供应链商户（担保交易）请求地址；https://cashier.sandpay.com.cn/gw/api/clearfile/download
            ),
        );
    }
}
