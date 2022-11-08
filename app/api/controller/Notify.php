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
use app\common\library\helper;
use app\common\library\wechat\WxPay;
use app\common\exception\BaseException;
use app\common\model\Integrals;
use app\common\model\Test;
use app\common\model\Transaction;
use think\facade\Db;
use think\facade\Log;
use app\api\model\Order;
//use app\common\model\Order;
use app\common\model\User;
use app\common\model\Goods;
use app\api\model\Setting;

/**
 * 支付成功异步通知接口
 * Class Notify
 * @package app\api\controller
 */
class Notify
{
    /**
     * 支付成功异步通知(微信小程序-微信支付)
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function wxpay()
    {
        // 微信支付组件：验证异步通知
        $WxPay = new WxPay();
        $WxPay->notify();
    }

    /**
     * 京东支付回调
     * @return string|\think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function jdPay()
    {
        $content = file_get_contents("php://input");
        $orderModel = new Order();
        $content = json_decode($content,true);
        helper::logInfo('京东支付', $content);
        if (!empty($content) && $content['status'] == 'SUCCESS'){
            $order = $orderModel->where('order_no',$content['requestNum'])->find();
            if (!empty($order) && $order['order_status'] == 10 && $order['pay_status'] == 10){
                Db::startTrans();
                try {
                    if ($order['type'] == 0){
                        if ($order['is_Box'] == 1) {
                            //调用抽取盲盒接口
                            $orderModel->drawboxs($order['user_id'], $order['order_no'], $order['goods_id']);
                        } else {
                            //调用新增藏品接口
                            $orderModel->addCollec($order['user_id'], $order['goods_id'], $order['order_no']);
                        }
                    }else{
                        $transaction = Transaction::get($order['transaction_id']);
                        $config = Integrals::field('charges,copyright')->find();
                        $price = $transaction['price'] * (100 - ($config['charges'] + $config['copyright'])) / 100;
                        $transaction->save(['buyer_id' => $order['user_id'],'buytime' => time(),'status' => 1]); //交易状态修改
                        Coll::update(['user_id' => $order['user_id'],'status' => 0,'addtime' => time()],['coll_id' => $transaction['coll_id']]); //转移到买房账上
                        User::setIncBalance($transaction['user_id'],$price); //增加卖方余额
                    }
                    $order->save(['pay_status' => 20,'order_status' => 30,'pay_time' => time()]);
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
                    //处理二级分销
                    $this->commission($order);
                } catch (\Exception $e) {
                    // 回滚事务
                    Db::rollback();
                    helper::logInfo($e->getMessage(),$content);
                    return json([], '500');
                }
            }
            return json([], '200');
        }
    }

    /**
     * 二级分销
     * @param $order
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function commission($order){
        $user = new User();
        if ($order['total_price'] < 1){
            return;
        }
        $config = Setting::getItem('setting');
        //上级id
        $pid = $user->where('user_id',$order['user_id'])->value('extension_id');
        if (!empty($pid)){
            $price = $order['total_price'] * $config['ratio_one'] / 100;
            $user->setIncBalance($pid,(int)$price); //一级分佣
            //上上级id
            $pid1 = $user->where('user_id',$pid)->value('extension_id');
            if (!empty($pid1)){
                $price1 = $order['total_price'] * $config['ratio_two'] / 100;
                $user->setIncBalance($pid1,(int)$price1); //二级分佣
            }
        }
    }
}
