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

use app\common\model\Integrals;
use app\api\model\{Goods as GoodsModel, OrderRefund as OrderRefundModel, Setting as SettingModel};
use think\facade\Db;
use think\facade\Request;
use app\api\service\{User as UserService, Payment as PaymentService};
use app\api\service\order\{PaySuccess as OrderPaySuccesService, source\Factory as OrderSourceFactory};
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
    public function onPaymentByBalance(string $orderNo): bool
    {
        // 获取订单详情
        $service = new OrderPaySuccesService($orderNo);
        // 发起余额支付
        $status = $service->onPaySuccess(OrderPayTypeEnum::BALANCE);
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
    public function cbox($userid){
        //查询换取盲盒的积分数
        $ints=$this->integrals();
        $integral=$ints['integral'];

        //优先查询该用户的积分是否足够
        $points=Db::name('user')
            ->field('points')
            ->where('user_id',$userid)
            ->find();
        if($points['points']<$integral){
            return ['code' => false,'msg' => '积分不足！'];
        }
        //查询所有盲盒有库存的盲盒
        $boxList=Db::name('goods')
            ->alias('g')
            ->join('yoshop_goods_sku gs','g.goods_id=gs.goods_id')
            ->where('gs.stock_num','>',0)
            ->where('g.is_delete','=',0)
            ->where('g.is_Box',1)
            ->select()->toArray();

        //统计有几个盲盒
        $boxcount=count($boxList);

        $boxid=[];
        //将盲盒的ID填进一个数组中
        foreach($boxList as $key=>$boxs){
            $boxid[$key]=$boxs['goods_id'];
        }
        //生成一个随机数
        $rands=rand(0,$boxcount-1);
        //根据下标，取出随机生成的ID号
        $boxsid=$boxid[$rands];

        //将随机选中的盲盒数量减少一个
        $boxsum=Db::name('goods')
            ->alias('g')
            ->join('yoshop_goods_sku gs','g.goods_id=gs.goods_id')
            ->where('g.goods_id',$boxsid)
            ->dec('gs.stock_num')
            ->update();

        //积分减少100
        $points=Db::name('user')
            ->where('user_id',$userid)
            ->dec('points',$integral)
            ->update();


        //将该盲盒的商品加入到对应的用户藏品库中

        $config = Integrals::find();
        $time = $config['setday'] * 86400;  //转赠时间

        //添加藏品到藏品库中
        $collarr=[
            'goods_id'  =>  $boxsid,
            'user_id'   =>  $userid,
            'addtime'   =>  time(),
            'zztime'    =>  time() + $time,
            'coll_no' => random(8)
        ];
        //将盲盒商品放入藏品库中
        $collen=Db::name('coll')
                ->insert($collarr);

        //根据该商品ID获取商品的信息
        $good=Db::name('goods_image')
            ->field('g.goods_id,g.goods_name,uf.file_path')
            ->alias('gi')
            ->where('g.goods_id',$boxsid)
            ->join('goods g','g.goods_id=gi.goods_id')
            ->join('yoshop_upload_file uf','gi.image_id=uf.file_id')
            ->find();
        $good['code']=true;

        return $good;
    }

    /**
     * 查询我的藏品
     */
    public function myColl($userid){
        //根据用户ID查询所有该用户的藏品
        $myCollList=Db::name('coll')
            ->alias('c')
            ->field('c.coll_id,c.goods_id,c.addtime,c.zztime,uf.file_path,c.goods_name,yg.goods_address,yg.logo')
            ->where('c.user_id',$userid)
            ->leftJoin('yoshop_goods yg','c.goods_id=yg.goods_id')
            ->leftJoin('yoshop_goods_image gi','c.goods_id=gi.goods_id')
            ->leftJoin('yoshop_upload_file uf','gi.image_id=uf.file_id')
            ->order('coll_id','desc')
            ->select()->toArray();
        
        //查询转赠天数
        $givaTime=Db::name('integrals')
                ->where('id',1)
                ->field('setday')
                ->find();

        //测试结束后修改回86400
        //转赠天数的时间戳
        $givaTime=$givaTime['setday']*30;
        //获取当前时间
        $newtime=time();

        //循环判断时间是否到达转赠时间
        foreach ($myCollList as $key => &$values){
            //获取转赠后的时间
            $givas=$values['addtime']+$givaTime;

            if($newtime<$givas){
                $values['giveType']=0;
            }
            else{
                $values['giveType']=1;
            }
        }


//        $myCollList=Db::name('collec')
//            ->where('user_id',$userid)
//            ->select()->toArray();
//        if($myCollList==""){
//            return false;
//        }
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
        $man=Db::name('user')
        ->where('address',$heUserId)
        ->find();
        
        if(empty($man)){
            return 408;
        }

        //根据地址获取用户ID
        $userId=Db::name('user')
            ->where('address',$heUserId)
            ->find();
            
            

        
        //将数据库中的userid改为修改后的userID
        $giveGood=Db::name('coll')
            ->where('coll_id',$collId)
            ->data(['user_id' => $userId['user_id']])
            ->update();
            
        return true;
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
            ->field('integral')
            ->where('id=1')
            ->find();
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
    public function pointss($userid,$pointsbonus,$ordersup){
        //首先判断是否支付完成
        if(!$ordersup){
            return false;
        }
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
    public function addCollec($userid,$goodsid,$ordersNo){
            //根据商品id查询商品名称
            $goodsname=Db::name('goods')
                ->where('goods_id',$goodsid)
                ->field('goods_name')
                ->select()->toArray();
            $config = Integrals::find();
            $time = $config['setday'] * 86400;  //转赠时间
            $collecList=[
                'goods_id'  =>  $goodsid,
                'goods_name'  =>  $goodsname[0]['goods_name'],
                'user_id'   =>  $userid,
                'order_no'  =>  $ordersNo,
                'addtime'   =>  time(),
                'zztime'    =>  time() + $time,
                'coll_no' => random(8)
            ];
            $addcollec=Db::name('coll')->insert($collecList);

            if($addcollec){
                return true;
            }
            return false;

    }

    /**
     * 购买盲盒
     */
    public function drawboxs($userid,$orderNo,$goodsid){
        try {

        //查询换取盲盒的积分数
        $ints=$this->integrals();
//        $integral=$ints['integral'];

//        //查询所有盲盒有库存的盲盒
//        $boxList=Db::name('goods')
//            ->alias('g')
//            ->join('yoshop_goods_sku gs','g.goods_id=gs.goods_id')
//            ->where('gs.stock_num','>',0)
//            ->where('g.is_delete','=',0)
//            ->where('g.is_Box',1)
//            ->select()->toArray();
//
//        //统计有几个盲盒
//        $boxcount=count($boxList);
//
//        $boxid=[];
//        //将盲盒的ID填进一个数组中
//        foreach($boxList as $key=>$boxs){
//            $boxid[$key]=$boxs['goods_id'];
//        }
//        //生成一个随机数
//        $rands=rand(0,$boxcount-1);
//        //根据下标，取出随机生成的ID号
//        $boxsid=$boxid[$rands];
//
//        //将随机选中的盲盒数量减少一个
//        $boxsum=Db::name('goods')
//            ->alias('g')
//            ->join('yoshop_goods_sku gs','g.goods_id=gs.goods_id')
//            ->where('g.goods_id',$boxsid)
//            ->dec('gs.stock_num')
//            ->update();
//        $goodsid=$boxsid;

            //将随机选中的盲盒数量减少一个
            $boxsum=Db::name('goods')
                ->alias('g')
                ->join('yoshop_goods_sku gs','g.goods_id=gs.goods_id')
                ->where('g.goods_id',$goodsid)
                ->dec('gs.stock_num')
                ->update();

        //修改订单表和订单商品关系表
//        $upGoods=Db::name('order_goods')
//            ->where('user_id',$userid)
//            ->where('order_no',$orderNo)
//            ->update(['goods_id'=>$goodsid]);
//        $upOrder=Db::name('order')
//            ->where('order_no',$orderNo)
//            ->update(['goods_id'=>$goodsid]);



        //将该盲盒的商品加入到对应的用户藏品库中

        $config = Integrals::find();
        $time = $config['setday'] * 86400;  //转赠时间

        //添加藏品到藏品库中
        $collarr=[
            'goods_id'  =>  $goodsid,
            'user_id'   =>  $userid,
            'addtime'   =>  time(),
            'zztime'    =>  time() + $time,
            'coll_no' => random(8)
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
