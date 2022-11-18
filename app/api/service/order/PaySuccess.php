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

namespace app\api\service\order;

use app\api\model\Coll;
use app\api\model\Setting;
use app\common\model\Goods;
use app\common\model\Integrals;
use app\common\model\Order;
use app\common\model\Transaction;
use app\common\model\User;
use think\facade\Db;
use think\facade\Event;
use app\common\service\BaseService;
use app\api\model\User as UserModel;
use app\api\model\Order as OrderModel;
use app\api\model\OrderRefund;
use app\api\model\user\BalanceLog as BalanceLogModel;
use app\common\service\goods\source\Factory as StockFactory;
use app\common\enum\OrderType as OrderTypeEnum;
use app\common\enum\order\PayStatus as PayStatusEnum;
use app\common\enum\order\PayType as OrderPayTypeEnum;
use app\common\enum\user\balanceLog\Scene as SceneEnum;
use think\Model;

/**
 * 订单支付成功服务类
 * Class PaySuccess
 * @package app\api\service\order
 */
class PaySuccess extends BaseService
{
    // 订单模型
    public $model;

    // 当前用户信息
    private $user;

    /**
     * 构造函数
     * PaySuccess constructor.
     * @param $orderNo
     */
    public function __construct($orderNo)
    {
        parent::__construct();
        // 实例化订单模型
        $this->model = OrderModel::getPayDetail($orderNo);
        // 获取用户信息
        $this->user = UserModel::detail($this->model['user_id']);
    }

    /**
     * 获取订单详情
     * @return OrderModel|null
     */
    public function getOrderInfo()
    {
        return $this->model;
    }

    /**
     * 订单支付成功业务处理
     * @param $payType
     * @param array $payData
     * @return bool
     */
    public function onPaySuccess($payType, $payData = [],$blind_id)
    {
        if (empty($this->model)) {
            $this->error = '未找到该订单信息';
            return false;
        }
        // 更新付款状态
        $status = $this->updatePayStatus($payType, $payData,$blind_id);
        // 订单支付成功事件
        if ($status == true) {
            Event::trigger('OrderPaySuccess', ['order' => $this->model, 'orderType' => OrderTypeEnum::ORDER]);
        }
        return $status;
    }

    /**
     * 更新付款状态
     * @param $payType
     * @param array $payData
     * @return bool
     */
    private function updatePayStatus($payType, $payData = [],$blind_id)
    {
        // 验证余额支付时用户余额是否满足
        if ($payType == OrderPayTypeEnum::BALANCE) {
            if ($this->user['balance'] < $this->model['pay_price']) {
                $this->error = '用户余额不足，无法使用余额支付';
                return false;
            }
        }

        // 事务处理
        $this->model->transaction(function () use ($payType, $payData,$blind_id) {
            // 更新订单状态
            $this->updateOrderInfo($payType, $payData,$blind_id);
            // 累积用户总消费金额
            UserModel::setIncPayMoney($this->user['user_id'], (float)$this->model['pay_price']);
            // 记录订单支付信息
            $this->updatePayInfo($payType);
        });
        return true;
    }

    /**
     * 更新订单记录
     * @param int $payType
     * @param array $payData
     * @return false|int
     * @throws \Exception
     */
    public function updateOrderInfo(int $payType, array $payData,int $blind_id)
    {
        if ($this->model['type'] == 0){

            //调用新增藏品接口
            (new OrderModel())->addCollec($this->model['user_id'], $this->model['goods_id'], $this->model['order_no'],$this->model['goods_sum']);

        }else{
            $transaction = Transaction::get($this->model['transaction_id']);

            $price_data = rateLess(2, $transaction['user_id'], $transaction['price']);
            $transaction->save(['buyer_id' => $this->model['user_id'],'buytime' => time(),'status' => 1]); //交易状态修改

            Coll::update(['user_id' => $this->model['user_id'],'status' => 0,'addtime' => time()],['coll_id' => $transaction['coll_id']]); //转移到买房账上

            //变成未寄售
            Db::name('goods_sn')
                ->where('coll_id',$transaction['coll_id'])
                ->where('goods_id',$transaction['goods_id'])
                ->update(['status'=>0]);
            User::setIncBalance(intval($transaction['user_id']), floatval($price_data['price'])); //增加卖方余额
        }

        $goodsModel = new Goods();
        $goodsModel->setIncSales($this->model['goods_id'],$this->model['goods_sum']);

        $good = $goodsModel->where('goods_id',$this->model['goods_id'])->cache('pay_success',300)->find();

        //针对下单减库存
        if ($good['deduct_stock_type'] == 20 && $this->model['type'] == 0){
            Db::name('goods_sku')
                ->where('goods_id', $this->model['goods_id'])
                ->dec('stock_num',$this->model['goods_sum'])
                ->update();
            Db::name('goods')
                ->where('goods_id', $this->model['goods_id'])
                ->dec('stock_total',$this->model['goods_sum'])
                ->update();
        }

        //$this->commission($this->model);
        // 整理订单信息
        $order = [
            'pay_type' => $payType,
            'pay_status' => PayStatusEnum::SUCCESS,
            'order_status' => 30,
            'pay_time' => time()
        ];

        if ($payType == OrderPayTypeEnum::WECHAT) {
            $order['transaction_id'] = $payData['transaction_id'];
        }
        // 更新订单状态
        return $this->model->save($order);
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

    /**
     * 记录订单支付信息
     * @param int $payType
     */
    public function updatePayInfo(int $payType)
    {
        // 余额支付
        if ($payType == OrderPayTypeEnum::BALANCE) {
            // 更新用户余额
            UserModel::setDecBalance((int)$this->user['user_id'], (float)$this->model['pay_price']);
            // 新增余额变动记录
            $goods = Db::name('order')->alias('a')
                                ->leftJoin('transaction_log b', 'a.transaction_id = b.id')
                                ->leftJoin('goods_sn c', 'b.coll_id = c.coll_id')
                                ->where('a.order_no', $this->model['order_no'])
                                ->field('c.number, b.name')
                                ->find();
            BalanceLogModel::add(SceneEnum::CONSUME, [
                'user_id' => (int)$this->user['user_id'],
                'money' => -$this->model['pay_price'],
            ], ['order_no' => '商品：'.$goods['name'].'，编号：'.$goods['number'].'，订单号：'.$this->model['order_no']]);
        }
        // 微信支付
        if ($payType == OrderPayTypeEnum::WECHAT) {

        }
    }

}
