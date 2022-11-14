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

namespace app\api\model;

use app\common\model\BlindLog;
use app\common\model\GoodsSn;
use app\common\model\Integrals;
use app\common\model\nft\NftClass;
use app\common\model\nft\NftOrder;
use app\common\model\Transaction;
use app\common\model\UserBoxLog;
use app\api\model\{Goods as GoodsModel, OrderRefund as OrderRefundModel, Setting as SettingModel};
use think\Exception;
use think\facade\Db;
use think\facade\Request;
use think\Model;
use app\api\service\{Nft, User as UserService, Payment as PaymentService};
use app\api\service\order\{Checkout, PaySuccess as OrderPaySuccesService, source\Factory as OrderSourceFactory};
use app\common\model\Order as OrderModel;
use app\common\service\{Order as OrderService, order\Complete as OrderCompleteService};
use app\common\enum\{
    Setting as SettingEnum,
    OrderType as OrderTypeEnum,
    order\PayType as OrderPayTypeEnum,
    order\PayStatus as PayStatusEnum,
    order\OrderStatus as OrderStatusEnum,
//    order\DeliveryType as DeliveryTypeEnum,
    order\ReceiptStatus as ReceiptStatusEnum,
    order\DeliveryStatus as DeliveryStatusEnum
};
use app\common\library\helper;
use cores\exception\BaseException;
use app\api\service\User;

/**
 * 订单模型
 * Class Order
 * @package app\api\model
 */
class Order extends OrderModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'store_id',
        'update_time'
    ];

    // 信息提示
    private $message = '';

    /**
     * 待支付订单详情
     * @param string $orderNo 订单号
     * @return null|static
     */
    public static function getPayDetail(string $orderNo): ?Order
    {
        return self::detail(['order_no' => $orderNo, 'pay_status' => PayStatusEnum::PENDING, 'is_delete' => 0], ['goods', 'user']);
    }

    /**
     * 订单支付事件
     * @param int $payType
     * @return bool
     */
    public function onPay(int $payType = OrderPayTypeEnum::WECHAT): bool
    {
        // 判断订单状态
        $orderSource = OrderSourceFactory::getFactory($this['order_source']);
        if (!$orderSource->checkOrderStatusOnPay($this)) {
            $this->error = $orderSource->getError();
            return false;
        }
        // 余额支付
        if ($payType == OrderPayTypeEnum::BALANCE) {
            return $this->onPaymentByBalance($this['order_no']);
        }
        return true;
    }

    /**
     * 构建支付请求的参数
     * @param self $order 订单信息
     * @param int $payType 订单支付方式
     * @return array
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function onOrderPayment(self $order, int $payType): array
    {

        if ($payType == OrderPayTypeEnum::WECHAT) {
            return $this->onPaymentByWechat($order);
        }
        return [];
    }

    /**
     * 构建微信支付请求
     * @param self $order 订单详情
     * @return array
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    protected function onPaymentByWechat(self $order): array
    {
        return PaymentService::wechat(
            $order['order_id'],
            $order['order_no'],
            $order['pay_price'],
            OrderTypeEnum::ORDER
        );
    }

    /**
     * 立即购买：获取订单商品列表
     * @param int $goodsId 商品ID
     * @param string $goodsSkuId 商品SKU
     * @param int $goodsNum 购买数量
     * @return mixed
     * @throws BaseException
     */
    public function getOrderGoodsListByNow(int $goodsId, string $goodsSkuId, int $goodsNum)
    {
        // 获取商品列表
        $model = new GoodsModel;
        $goodsList = $model->isGoodsGradeMoney(false)->getListByIdsFromApi([$goodsId]);

        if ($goodsList->isEmpty()) {
            throwError('未找到商品信息');
        }
        // 隐藏冗余的属性
       $goodsList->hidden(array_merge($model->hidden, ['content', 'goods_images', 'images']));



        foreach ($goodsList as &$item) {
            // 商品sku信息
            $goodsInfo['skuInfo'] = GoodsModel::getSkuInfo($item, $goodsSkuId, false);
//            var_dump($goodsInfo['skuInfo']);die;
            // 商品单价
            $item['goods_price'] = $item['skuInfo']['goods_price'];
            // 商品购买数量
            $item['total_num'] = $goodsNum;
            // 商品SKU索引
            $item['goods_sku_id'] = $item['skuInfo']['goods_sku_id'];
            // 商品购买总金额
            $item['total_price'] = helper::bcmul($item['goods_price'], $goodsNum);

        }

        return $goodsList;
    }

    /**
     * 余额支付标记订单已支付
     * @param string $orderNo 订单号
     * @return bool
     */
    public function onPaymentByBalance(string $orderNo,int $blind_id =0): bool
    {
        // 获取订单详情
        $service = new OrderPaySuccesService($orderNo);
        // 发起余额支付
        $status = $service->onPaySuccess(OrderPayTypeEnum::BALANCE,[],$blind_id);
        if (!$status) {
            $this->error = $service->getError();
        }
        return $status;
    }

    /**
     * 获取用户订单列表
     * @param string $type 订单类型 (all全部 payment待付款 received待发货 deliver待收货 comment待评价)
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     * @throws BaseException
     */
    public function getList(string $type = 'all'): \think\Paginator
    {
        // 筛选条件
        $filter = [];
        // 订单数据类型
        switch ($type) {
            case 'all':
                break;
            case 'payment':
                $filter['pay_status'] = PayStatusEnum::PENDING;
                $filter['order_status'] = OrderStatusEnum::NORMAL;
                break;
            case 'delivery':
                $filter['pay_status'] = PayStatusEnum::SUCCESS;
                $filter['delivery_status'] = DeliveryStatusEnum::NOT_DELIVERED;
                $filter['order_status'] = OrderStatusEnum::NORMAL;
                break;
            case 'received':
                $filter['pay_status'] = PayStatusEnum::SUCCESS;
                $filter['delivery_status'] = DeliveryStatusEnum::DELIVERED;
                $filter['receipt_status'] = ReceiptStatusEnum::NOT_RECEIVED;
                $filter['order_status'] = OrderStatusEnum::NORMAL;
                break;
            case 'comment':
                $filter['is_comment'] = 0;
                $filter['order_status'] = OrderStatusEnum::COMPLETED;
                break;
        }
        // 当前用户ID
        $userId = UserService::getCurrentLoginUserId();
        // 查询列表数据
        return $this->with(['goods.image'])
            ->where($filter)
            ->where('user_id', '=', $userId)
            ->where('is_delete', '=', 0)
            ->order(['create_time' => 'desc'])
            ->paginate(15);
    }

    /**
     * 取消订单
     * @return bool|mixed
     */
    public function cancel()
    {
        if ($this['delivery_status'] == DeliveryStatusEnum::DELIVERED) {
            $this->error = '已发货订单不可取消';
            return false;
        }
        // 订单是否已支付
        $isPay = $this['pay_status'] == PayStatusEnum::SUCCESS;
        // 提示信息
        $this->message = $isPay ? '订单已申请取消，需等待后台审核' : '订单已取消成功';
        // 订单取消事件
        return $this->transaction(function () use ($isPay) {
            // 订单取消事件
            $isPay == false && OrderService::cancelEvent($this);
            // 更新订单状态: 已付款的订单设置为"待取消", 等待后台审核
            return $this->save(['order_status' => $isPay ? OrderStatusEnum::APPLY_CANCEL : OrderStatusEnum::CANCELLED]);
        });
    }

    /**
     * 确认收货
     * @return bool|mixed
     */
    public function receipt()
    {
        // 验证订单是否合法
        // 条件1: 订单必须已发货
        // 条件2: 订单必须未收货
        if ($this['delivery_status'] != 20 || $this['receipt_status'] != 10) {
            $this->error = '该订单不合法';
            return false;
        }
        return $this->transaction(function () {
            // 更新订单状态
            $status = $this->save([
                'receipt_status' => 20,
                'receipt_time' => time(),
                'order_status' => 30
            ]);
            // 执行订单完成后的操作
            $OrderCompleteService = new OrderCompleteService();
            $OrderCompleteService->complete([$this], static::$storeId);
            return $status;
        });
    }

    /**
     * 获取当前用户订单数量
     * @param string $type 订单类型 (all全部 payment待付款 received待发货 deliver待收货 comment待评价)
     * @return int
     * @throws BaseException
     */
    public function getCount(string $type = 'all'): int
    {
        // 筛选条件
        $filter = [];
        // 订单数据类型
        switch ($type) {
            case 'all':
                break;
            case 'payment':
                $filter['pay_status'] = PayStatusEnum::PENDING;
                break;
            case 'received':
                $filter['pay_status'] = PayStatusEnum::SUCCESS;
                $filter['delivery_status'] = DeliveryStatusEnum::DELIVERED;
                $filter['receipt_status'] = ReceiptStatusEnum::NOT_RECEIVED;
                break;
            case 'delivery':
                $filter['pay_status'] = PayStatusEnum::SUCCESS;
                $filter['delivery_status'] = DeliveryStatusEnum::NOT_DELIVERED;
                $filter['order_status'] = OrderStatusEnum::NORMAL;
                break;
            case 'comment':
                $filter['is_comment'] = 0;
                $filter['order_status'] = OrderStatusEnum::COMPLETED;
                break;
        }
        // 当前用户ID
        $userId = UserService::getCurrentLoginUserId();
        // 查询数据
        return $this->where('user_id', '=', $userId)
            ->where('order_status', '<>', 20)
            ->where($filter)
            ->where('is_delete', '=', 0)
            ->count();
    }

    /**
     * 获取用户订单详情(含关联数据)
     * @param int $orderId 订单ID
     * @return Order|array|null
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getUserOrderDetail(int $orderId)
    {
        // 关联查询
        $with = [
            'goods' => ['image', 'goods', 'refund'],
            'address', 'express'
        ];
        // 查询订单记录
        $order = static::getDetail($orderId, $with);
        // 该订单是否允许申请售后
        $order['isAllowRefund'] = static::isAllowRefund($order);
        return $order;
    }

    /**
     * 获取用户订单详情(仅订单记录)
     * @param int $orderId
     * @param array $with
     * @return Order|array|null
     * @throws BaseException
     */
    public static function getDetail(int $orderId, array $with = [])
    {
        // 查询订单记录
        $order = static::detail([
            'order_id' => $orderId,
            'user_id' => UserService::getCurrentLoginUserId(),
        ], $with);
        empty($order) && throwError('订单不存在');
        return $order;
    }

    /**
     * 获取当前用户待处理的订单数量
     * @return array
     * @throws BaseException
     */
    public function getTodoCounts(): array
    {
        return [
            'payment' => $this->getCount('payment'),    // 待付款的订单
            'delivery' => $this->getCount('delivery'),  // 待发货的订单
            'received' => $this->getCount('received'),  // 待收货的订单
            'refund' => OrderRefundModel::getCountByUnderway(),  // 进行中的售后单
        ];
    }

    // 返回提示信息
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * 当前订单是否允许申请售后
     * @param Order $order
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private static function isAllowRefund(self $order): bool
    {
        // 必须是已发货的订单
        if ($order['delivery_status'] != DeliveryStatusEnum::DELIVERED) {
            return false;
        }
        // 允许申请售后期限(天)
        $refundDays = SettingModel::getItem(SettingEnum::TRADE)['order']['refund_days'];
        // 不允许售后
        if ($refundDays == 0) {
            return false;
        }
        // 当前时间超出允许申请售后期限
        if (
            $order['receipt_status'] == ReceiptStatusEnum::RECEIVED
            && time() > ($order->getData('receipt_time') + ((int)$refundDays * 86400))
        ) {
            return false;
        }
        return true;
    }

    /**
     * 查询商品
     */
    public function findgoods(int $goodsid){
        //根据获取到的商品ID查询该商品
        $goodsList=Db::name('goods')
            ->where('goods_id',$goodsid)
            ->find();

        if(!empty($goodsList)){//有数据返回true 无数据返回false
            return true;
        }
        return false;
    }

    /**
     * 新增盲盒
     */
    public function addbox($goodsid,$boxsum,$boxname){
        //获取数据
        $boxarr=[
            'box_name'  =>  $boxname,
            'box_sum'   =>  $boxsum,
            'goods_id'  =>  $goodsid
        ];

        //将数据新增到盲盒表
        $boxadd=Db::name('box')->insert($boxsum);

        if($boxadd==1){//盲盒添加成功
            return true;
        }
        return false;
    }

    /**
     * 抽取盲盒
     */
    public function cbox(){
        $user_id = User::getCurrentLoginUserId();
        //查询换取盲盒的积分数
        $ints=$this->integrals();
        $integral=$ints['integral'];

        //优先查询该用户的积分是否足够
        $points=Db::name('user')
            ->field('points')
            ->where('user_id',$user_id)
            ->find();
        if($points['points']<$integral){
            return ['code' => false,'msg' => '积分不足！'];
        }


        $probabilitys = GoodsModel::where(['is_box' => 1,'is_delete' => 0])->column('probability');
        if (empty($probabilitys)){
            return ['code' => false,'msg' => '盲盒已售空！'];
        }
        $goods = GoodsModel::where(['is_box' => 1,'is_delete' => 0])->select()->toArray();
        $key = get_rand($probabilitys);
        $good = $goods[$key];

        //将随机选中的盲盒数量减少一个
        Db::name('goods')
            ->alias('g')
            ->join('yoshop_goods_sku gs','g.goods_id=gs.goods_id')
            ->where('g.goods_id',$good['goods_id'])
            ->dec('gs.stock_num')
            ->update();

        //积分减少100
        Db::name('user')
            ->where('user_id',$user_id)
            ->dec('points',$integral)
            ->update();

        //将该盲盒的商品加入到对应的用户藏品库中
        $config = Integrals::find();
        $time = $config['setday'] * 86400;  //转赠时间
        //添加藏品到藏品库中
        $collarr=[
            'goods_id'  =>  $good['goods_id'],
            'user_id'   =>  $user_id,
            'addtime'   =>  time(),
            'zztime'    =>  time() + $time,
            'goods_name' =>  $good['goods_name'],
            'order_no'    =>  '',
            'type'    =>  1,
            'coll_no' => random(8)
        ];
        //将盲盒商品放入藏品库中
        $collId = Db::name('coll')
            ->insertGetId($collarr);

        //生成订单编号
        $order = new Order();
        $orderNo = $order->orderNo();
        //创建订单，进行处理!!!!
        $Checkout = new Checkout();
        $Checkout->addorder($user_id, $good['goods_id'], $orderNo, 1, $integral, 40);
        $good['code']=true;

        $log = [
            'user_id' => $user_id,
            'coll_id' => $collId,
            'type' => 0,
            'goods_name' => $good['goods_name'],
            'goods_id' => $good['goods_id']
        ];
        UserBoxLog::create($log);
        return $good;
    }

    /**
     * 查询我的藏品
     */
    public function myCountCool($type,$state,$status,$is_shop,$keyword){
        $userid = UserService::getCurrentLoginUserId();

        if($type == 1){

            $where[] = ['c.is_give' ,'=', 1];

        }else{

            $where[] = ['c.is_give' ,'=', 0];
        }

        if(!empty($state)){

            switch ($state){
                case 1;
                    $where[] = ['c.type' ,'=', 0];
                    break;
                case 2;
                    $where[] = ['c.type' ,'=', 1];
                    break;
                case 3;
                    $where[] = ['c.type' ,'=', 2];
                    break;
            }
        }

        if(!empty($keyword)){

            $where[] = ['c.goods_name' ,'like', "%".$keyword."%"];

        }

        $where_status  = '0,1';

        if(!empty($status) && $status > 0){

            $where_status = '3';

        }

        if(!empty($is_shop)){

            $where[] = ['c.shop_id' ,'=', $userid];

        }else{

            $where[] = ['c.user_id' ,'=', $userid];

        }

        //根据用户ID查询所有该用户的藏品
        $myCollList = Db::name('coll')
            ->alias('c')
            ->field('count(c.goods_id) as goods_total,c.coll_id,c.goods_id,c.addtime,c.zztime,c.coll_no,c.status,c.goods_name,yg.goods_address,yg.logo,yg.is_box,c.image_id,no.tx_hash,us.address as usaddress')
            ->where($where)
            ->where('c.status','in',$where_status)
            ->leftJoin('yoshop_goods yg','c.goods_id=yg.goods_id')
            ->leftJoin('yoshop_nft_order no','c.coll_id=no.order_id')
            ->leftJoin('yoshop_user us','c.give_id=us.user_id')
            ->order('addtime','desc')
            ->group('c.goods_id')
            ->select()
            ->toArray();

        $upload = new UploadFile();
        $image = new GoodsImage();
        //循环判断时间是否到达转赠时间
        foreach ($myCollList as $key => &$value){
            //获取图片
            if ($value['image_id'] != 0){
                $file = $upload->get($value['image_id']);
            }else{
                $file_id = $image->where('goods_id',$value['goods_id'])->value('image_id');
                $file = $upload->get($file_id);
            }
            $value['file_path'] = 'uploads/'.$file['file_path'];
            //获取转赠后的时间
            if($value['zztime'] > time()){
                $value['giveType']=0;
            }else{
                $value['giveType']=1;
            }
        }
        return $myCollList;
    }

    /**
     * 查询我的藏品
     */
    public function myColl($type,$state,$status,$keyword,$goods_id,$tran_type){
        $userid = UserService::getCurrentLoginUserId();

        if($type == 1){
            $where[] = ['c.is_give','=',1];
        }else{
            $where = [['c.is_give','=',0]];
        }

        if(!empty($state)){

            switch ($state){
                case 1;
                    $where[] = ['c.type','=',0];
                    break;
                case 2;
                    $where[] = ['c.type','=',1];
                    break;
                case 3;
                    $where[] = ['c.type','=',2];
                    break;
            }
        }

        if(!empty($keyword)){

            $where[] = ['c.goods_name' ,'like', "%".$keyword."%"];

        }

        $order_sort = 'c.addtime desc';

        if(!empty($goods_id)){

            $where[] = ['c.goods_id' ,'=', $goods_id];

        }

        if($tran_type == 0){

            $user_where[] = ['c.user_id','=',$userid];

        }else{

            $user_where = [];

        }


        if($status == 0){

            if($tran_type == 0){

                $where[]  = ['c.status','in','0,1'];

            }else{

                $where[]  = ['c.status','=',$status];

            }


        }else if($status == 3){

            $where[]  = ['c.status','=',$status];

        }else if($status == 1){

            $where[] = ['c.status','=',1];

        }

        //根据用户ID查询所有该用户的藏品
        $myCollList = Db::name('coll')
            ->alias('c')
            // ->field('c.coll_id,c.goods_id,c.addtime,c.zztime,c.coll_no,c.status,c.goods_name,yg.goods_address,yg.logo,yg.is_box,c.image_id,no.tx_hash,us.address as usaddress,sn.number,yg.xn_sale,c.status')
            ->field('c.coll_id,c.goods_id,no.create_time addtime,c.zztime,c.coll_no,c.status,c.goods_name,yg.goods_address,yg.logo,yg.is_box,c.image_id,no.tx_hash,us.address as usaddress,sn.number,yg.xn_sale,c.status')
            ->where($where)
            ->where($user_where)
            ->leftJoin('yoshop_goods yg','c.goods_id=yg.goods_id')
            ->leftJoin('yoshop_nft_order no','c.coll_id=no.order_id')
            ->leftJoin('yoshop_user us','c.give_id=us.user_id')
            ->leftJoin('yoshop_goods_sn sn','c.coll_id=sn.coll_id')
            ->order($order_sort)
            ->select()
            ->toArray();
        $upload = new UploadFile();
        $image = new GoodsImage();
        $config = Integrals::find();
        //循环判断时间是否到达转赠时间
        foreach ($myCollList as $key => &$value){
            //获取图片
//            if ($value['image_id'] != 0){
//                $file = $upload->get($value['image_id']);
//            }else{
                $file_id = $image->where('goods_id',$value['goods_id'])->value('image_id');
                $file = $upload->get($file_id);
//            }
            $value['file_path'] = 'uploads/'.$file['file_path'];
            if($value['addtime'] == 0) {
                $value['addtime'] = time();
            }
            //获取转赠后的时间
            if($value['zztime'] > time()){
                $value['zs_type']=0;
            }else{
                $value['zs_type']=1;
            }
            $value['giveType'] = $config['is_give'] == 0 ? 1:0;

        }
        return $myCollList;
    }

    /**
     * 查询所有盲盒
     */
    public function boxList(){
        $boxlist=Db::name('box')
            ->select()
            ->toArray();
        return $boxlist;
    }


    /**
     * 查询订单
     */
    public function orderLists(){

    }

    /**
     * 赠送模型
     * 获取参数
     * $thisUserId 我的用户ID
     * $heUserId 被赠送人的ID
     * $goodsId 商品ID
     */
    public function giveGoodModel($collId,$heUserId){

        $user_info = User::getCurrentLoginUser();

        $man=Db::name('user')
        ->where('address|mobile',$heUserId)
        ->find();

        if(empty($man) || $man['user_id'] == $user_info['user_id']){
            return 408;
        }

        //根据地址获取用户ID
        $userId=Db::name('user')
            ->where('user_id',$man['user_id'])
            ->find();

        if(!$userId['address']){

            return '该账号链账户地址不存在';
        }

        $give_info = Db::name('coll')->where('coll_id',$collId)->where('user_id', $user_info['user_id'])->find();
        if(!$give_info) {
            return '操作失败';
        }
        if($give_info['is_give'] != 0){
            return '你已操作';
        }

        //查询购买时间是否在商品发售时间之前不可转赠
        $goods_start_time = GoodsModel::where('goods_id',$give_info['goods_id'])->value('startTime');

        if( $give_info['addtime'] < strtotime($goods_start_time) && $give_info['type'] == 0){

            return '优先购藏品不可转售';

        }

        $this->startTrans();
        try {

            if($give_info['is_sl'] == 0){

                //根据藏品获取NFT ID
                $nft_info = NftOrder::where('order_id',$collId)->find();

                if(empty($nft_info)){

                    throw new Exception('正在铸造中');

                }

                $nft = Nft::instance();

                //转赠
                $res = $nft->transfer_nft([
                    'class_id'      => $nft_info['class_id'],
                    'nft_id'        => $nft_info['nft_id'],
                    'address'       => $nft_info['owner'],
                    'recipient'     => $userId['address'],
                    'operation_id'  => rand(100,999).rand(100,999)
                ]);

                if(!empty($res['data'])){

                    $give = true;

                }else{

                    throw new Exception($res['error']['message']);

                }

            }else {

                $give = true;

            }

            if(isset($give) && !empty($give)){

                //将数据库中的userid改为修改后的userID
                $giveGood= Db::name('coll')
                    ->where('coll_id',$collId)
                    ->data(['is_give' => 1,'give_id'=>$userId['user_id']])
                    ->update();

                $config = Integrals::find();
                $time = $config['setday'] * 86400;  //转赠时间

                $add_give_id = Db::name('coll')
                    ->insertGetId([
                        'user_id'   => $userId['user_id'],
                        'goods_id'  => $give_info['goods_id'],
                        'addtime'   => time(),
                        'zztime'    => time()+$time,
                        'order_no'  => $give_info['order_no'],
                        'goods_name'=> $give_info['goods_name'],
                        'type'      => $give_info['type'],
                        'status'    => $give_info['status'],
                        'store_id'  => $give_info['store_id'],
                        'coll_no'   => $give_info['coll_no'],
                        'is_fan'    => 2,
                        'receive_id'=> $give_info['coll_id'],
                        'image_id'  => $give_info['image_id']
                    ]);

                //获取该编号信息
                $goods_sn_info = GoodsSn::where(['coll_id'=>$give_info['coll_id'],'goods_id'=>$give_info['goods_id']])->find();

                Db::name('goods_sn')->insert([
                   'goods_id'   => $give_info['goods_id'],
                   'number'     => $goods_sn_info['number'],
                   'coll_id'    => $add_give_id
                ]);

                $this->commit();

                return true;

            }else{

                throw new Exception('转赠错误');

            }

        }catch (Exception $e){

            $this->rollback();
            return $e->getMessage();


        }


    }

    /**
     * 查询换取盲盒的积分数
     */
    public function integrals(){
        $int=DB::name('integrals')->where('id',1)->find();
        return $int;
    }


    /**
     *查询盲盒抽取所需要的积分
     */
    public function seleIntegral(){
        $integrals=Db::name('integrals')
            ->where('id=1')
            ->value('integral');
        return $integrals;
    }

    /**
     * 修改当前盲盒的抽奖所需积分
     */
    public function drawBoxInts($ints){

        $intsup=Db::name('integrals')
            ->where('id=1')
            ->update(['integral' => $ints]);

        if($intsup==0){
            return false;
        }
        return true;
    }

    /**
     * 修改转赠天数
     */
    public function setDays($newDay){
        $setDayUp=Db::name('integrals')
            ->where('id=1')
            ->update(['setday' => $newDay]);
        if($setDayUp==0){
            return false;
        }
        return true;
    }

    /**
     * 根据订单号查商品ID和用户ID
     */
    public function chaUserId($orderno){
        $list=Db::name('order')
            ->where('order_no',$orderno)
            ->field('user_id,goods_id')
            ->select()->toArray();
        return $list;
    }

    /**
     * 支付完成 修改订单编号
     * orders_no  订单编号
     */
    public function orderup($orders_no){
        //调用后接口直接变为完成支付
        $ordersup=Db::name('order')
            ->where('order_no',$orders_no)
            ->save(['pay_status' => 20]);
        return $ordersup;
    }

    /**
     * 每次购买后调用积分接口添加积分
     */
    public function pointss($userid,$pointsbonus){
        //将价格转为整数,变成积分
        $pointsbonus=round($pointsbonus);
        //支付完成后添加积分
        $point=Db::name('user')
            ->where('user_id',$userid)
            ->inc('pointsall',$pointsbonus)
            ->inc('points',$pointsbonus)
            ->update();

        //记录积分变动情况
        $arr=[
            'user_id'   =>  $userid,
            'value'     =>  $pointsbonus,
            'describe'  =>  '购买藏品',
            'create_time'   =>  time()
        ];
        $userPoint=Db::name('user_points_log')
            ->insert($arr);

        if($point or $userPoint){
            return true;
        }
        return false;
    }

    /**
     * 写入藏品表
     */
    public function addCollec($userid,$goodsid,$ordersNo,$total=1){
            //根据商品id查询商品名称
            $goods = GoodsModel::get($goodsid);
            $config = Integrals::find();
            $time = $config['setday'] * 86400;  //转赠时间
            for($i=1;$i<=$total;$i++){

                $collecList=[
                    'goods_id'  =>  $goodsid,
                    'goods_name'  =>  $goods['goods_name'],
                    'user_id'   =>  $userid,
                    'order_no'  =>  $ordersNo,
                    'addtime'   =>  time(),
                    'zztime'    =>  time() + $time,
                    'coll_no' => random(8),
                    'image_id' => $this->getImage($goodsid),
                    'is_sl'     => $goods['is_sl']
                ];
                $addcollec = Db::name('coll')->insertGetId($collecList);
                if ($addcollec){
                    //return true;
                }

                //赠送藏品券
                \app\common\model\User::setIncPoints((int)$userid,$goods['give_num'],"购买藏品赠送");

                GoodsSn::create([
                    'goods_id'  => $goodsid,
                    'number'    => 0,
                    'coll_id'   => $addcollec
                ]);



            }

        return true;

    }

    /**
     * 获取图片id
     * @param $goodsid
     * @return int|mixed
     */
    public function getImage($goodsid)
    {
        $ids = GoodsImage::where('goods_id',$goodsid)->column('image_id');
        $coll_ids = Coll::where('goods_id', $goodsid)->column('image_id');
        if (empty($ids)){
            return 0;
        }
        //去重
        $diff = array_diff($ids,$coll_ids);
        //图片已分配完后默认取第一张
        if (empty($diff)){
            return $ids[0];
        }
        //随机获取
        $key = array_rand($diff);
        return $diff[$key];
    }

    /**
     * 购买盲盒
     */
    public function drawboxs($userid,$orderNo,$goodsid,$blind_id){
        try {

        //查询换取盲盒的积分数
        $ints=$this->integrals();

            $goods = GoodsModel::get($goodsid);

            //将随机选中的盲盒数量减少一个
            $boxsum=Db::name('goods')
                ->alias('g')
                ->join('yoshop_goods_sku gs','g.goods_id=gs.goods_id')
                ->where('g.goods_id',$goodsid)
                ->dec('gs.stock_num')
                ->update();

            $rs = Db::name('goods')
                ->where('goods_id', $goodsid)
                ->dec('stock_total')
                ->update();

            Db::name('blind')->where('id',$blind_id)->dec('total')->update();

            Db::name('blind')->where('id',$blind_id)->inc('sales')->update();


            $config = Integrals::find();
        $time = $config['setday'] * 86400;  //转赠时间

        //添加藏品到藏品库中
        $collarr=[
            'goods_id'  =>  $goodsid,
            'goods_name'  =>  $goods['goods_name'],
            'user_id'   =>  $userid,
            'addtime'   =>  time(),
            'zztime'    =>  time() + $time,
            'coll_no'   => random(8),
            'image_id'  => $this->getImage($goodsid),
            'type'      => 1,
            'order_no'  => $orderNo
        ];
        //将盲盒商品放入藏品库中
        $collen=Db::name('coll')
            ->insert($collarr);
            return true;

        } catch (\Exception $e) {
            $e->getMessage();
            return false;
        }
    }



    /**
     * 状态完成接口
     */
    public function orderType($orderNo){
        //将订单改为已完成
        $orderType=Db::name('order')
            ->where('order_no',$orderNo)
            ->save(['order_status'=>30]);
        if($orderType){
            return true;
        }
        return false;

    }

    /**
     * 根据订单编号查询是商品还是盲盒
     */
    public function checkOrderType($orderNo){
        $list=Db::name('order')
            ->where('order_no',$orderNo)
            ->field('user_id,goods_id,is_Box')
            ->select()->toArray();

        return $list;
    }

    public function test(){
        $url = Request::instance()->domain();
        var_dump($url);die;

        //查询所有邀请过人的用户ID
        $getUsers=Db::name('user')
            ->distinct(true)
            ->where('extension_id','>',0)
            ->field('extension_id')
            ->select()->toArray();
        $arr=array();
        //然后把数据放入到数组中
        foreach($getUsers as $key => $value){
            $arr[]=$value['extension_id'];
        }

        $arrList=array();
        //根据ID循环查找
        foreach ($arr as $k =>$v){
            $arrList[$v]=Db::name('user')
                ->field('extension_id')
                ->where('extension_id',$v)
                ->count('extension_id');
        }

        $newArrList=array();
        //再次遍历 把所有超过一定次数的ID筛选出来
        foreach ($arrList as $ke => $va){
            if($va>=3){
                $newArrList[]=$ke;
            }
        }

    }
}
