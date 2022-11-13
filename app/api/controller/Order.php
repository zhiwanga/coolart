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
use app\api\model\Order as OrderModel;
use app\api\model\Setting as SettingModel;
use app\api\model\TransactionOrder;
use app\api\model\UserBank;
use app\common\library\H5pay as Sd;
use app\common\library\helper;
use app\common\model\GoodsSn;
use app\common\model\Integrals;
use app\common\model\Transaction as TransactionModel;
use app\common\model\UserIdcar;
use app\common\service\Transaction;
use app\store\model\Express as ExpressModel;
use app\common\enum\order\PayType as OrderPayTypeEnum;
use cores\exception\BaseException;
use app\store\model\Setting;
use app\api\service\User;
use think\cache\driver\Redis;
use think\Exception;
use think\facade\Db;
use think\response\Json;
use app\api\model\User as UserModel;
use app\common\model\Goods;
use app\api\service\User as UserService;
use app\controller\Rsa;

/**
 * 我的订单控制器
 * Class Order
 * @package app\api\controller
 */
class Order extends Controller
{
    /**
     * 获取当前用户待处理的订单数量
     * @return Json
     * @throws BaseException
     */
    public function todoCounts(): Json
    {
        $model = new OrderModel;
        $counts = $model->getTodoCounts();
        return $this->renderSuccess(compact('counts'));
    }

    /**
     * 我的订单列表
     * @param string $dataType 订单类型 (all全部 payment待付款 received待发货 deliver待收货 comment待评价)
     * @return Json
     * @throws \think\db\exception\DbException
     * @throws BaseException
     */
    public function list(string $dataType): Json
    {
        $model = new OrderModel;
        $list = $model->getList($dataType);
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 订单详情信息
     * @param int $orderId 订单ID
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws BaseException
     */
    public function detail(int $orderId): Json
    {
        // 订单详情
        $model = OrderModel::getUserOrderDetail($orderId);
        return $this->renderSuccess([
            'order' => $model,  // 订单详情
            'setting' => [
                // 积分名称
                'points_name' => SettingModel::getPointsName(),
            ],
        ]);
    }

    /**
     * 获取物流信息
     * @param int $orderId 订单ID
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws BaseException
     */
    public function express(int $orderId): Json
    {
        // 订单信息
        $order = OrderModel::getDetail($orderId);
        if (!$order['express_no']) {
            return $this->renderError('没有物流信息');
        }
        // 获取物流信息
        $model = ExpressModel::detail($order['express_id']);
        $express = $model->dynamic($model['express_name'], $model['kuaidi100_code'], $order['express_no']);
        if ($express === false) {
            return $this->renderError($model->getError());
        }
        return $this->renderSuccess(compact('express'));
    }

    /**
     * 取消订单
     * @param int $orderId
     * @return Json
     * @throws BaseException
     */
    public function cancel(int $orderId): Json
    {
        $model = OrderModel::getDetail($orderId);
        if ($model->cancel()) {
            return $this->renderSuccess('订单取消成功');
        }
        return $this->renderError($model->getError() ?: '订单取消失败');
    }

    /**
     * 确认收货
     * @param int $orderId
     * @return Json
     * @throws BaseException
     */
    public function receipt(int $orderId): Json
    {
        $model = OrderModel::getDetail($orderId);
        if ($model->receipt()) {
            return $this->renderSuccess('确认收货成功');
        }
        return $this->renderError($model->getError());
    }

    /**
     * 立即支付
     * @param int $orderId 订单ID
     * @param int $payType 支付方式
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws BaseException
     */
    public function pay(int $orderId, int $payType = OrderPayTypeEnum::WECHAT): Json
    {
        // 获取订单详情
        $model = OrderModel::getUserOrderDetail($orderId);
        // 订单支付事件
        if (!$model->onPay($payType)) {
            return $this->renderError($model->getError() ?: '订单支付失败');
        }
        // 构建微信支付请求
        $payment = $model->onOrderPayment($model, $payType);
        // 支付状态提醒
        return $this->renderSuccess([
            'order_id' => $model['order_id'],   // 订单id
            'pay_type' => $payType,             // 支付方式
            'payment' => $payment               // 微信支付参数
        ]);
    }


    /**
     * 新建盲盒
     * 商品ID goods_id
     * 商品名称 goods_name
     * 盲盒数量 box_sum
     * 盲盒名称 box_name
     */
    public function addbox(){

        $posta=$this->postData();
        $goodsid=$posta['goodsid'];
        $boxsum=$posta['boxsum'];
        $boxname=$posta['boxname'];


        //验证有没有这个商品
        $orderModel=new OrderModel;
        $goods=$orderModel->findgoods($goodsid);

        if($goods){
            return $this->renderError('没有这个商品');
        }

        //新增数据到盲盒库
        $goods=$orderModel->addbox($goodsid,$boxsum,$boxname);
        if(!$goods){//不成功
            return $this->renderError('盲盒添加失败，请稍后再试');
        }
        return $this->renderSuccess('盲盒添加成功!');
    }

    /**
     * 抽取盲盒
     */
    public function drawbox(){
        //抽取盲盒的时候随机抽取一个盲盒ID
        $orderModel=new OrderModel;
        //返回抽中的商品ID，name，图片路径
        $cqbox=$orderModel->cbox();

        if(!$cqbox['code']){
            return $this->renderError($cqbox['msg']);
        }
        return $this->renderSuccess($cqbox,'兑换成功');
    }

    /**
     * 统计购买藏品次数
     */
    public function myCountCool(){

        $orderModel=new OrderModel;
        $type = $this->request->param('type','0');//我的藏品0正常 1=赠送记录
        $state = $this->request->param('state','0');//藏品类型,0=全部,1=普通藏品,1=盲盒,2=空投
        $status = $this->request->param('status','0');//藏品状态,0=正常,3=合成
        $is_shop = $this->request->param('is_shop','0');//藏品状态,0=正常,3=合成
        $keyword = $this->request->param('keyword','');//藏品状态,0=正常,3=合成
        //查询我的藏品
        $myCollModel=$orderModel->myCountCool($type,$state,$status,$is_shop,$keyword);

        if($myCollModel==false){
            return $this->renderError('您还没有藏品！');
        }
        return $this->renderSuccess($myCollModel);
    }


    /**
     * 查询该用户所有藏品
     */
    public function myColl(){
        $orderModel=new OrderModel;
        $type = $this->request->param('type','0');//我的藏品0正常 1=赠送记录
        $state = $this->request->param('state','0');//藏品类型,0=全部,1=普通藏品,1=盲盒,2=空投
        $status = $this->request->param('status','0');//藏品状态,0=正常,1=挂售中,3=合成
        $keyword = $this->request->param('keyword','');//藏品状态,0=正常,3=合成
        $goods_id = $this->request->param('goods_id','');//藏品状态,0=正常,3=合成
        $tran_type = $this->request->param('tran_type','0');//藏品状态,0=正常,1=二级市场

        //查询我的藏品
        $myCollModel=$orderModel->myColl($type,$state,$status,$keyword,$goods_id,$tran_type);

        if($myCollModel==false){
            return $this->renderError($tran_type==0?'您还没有藏品！':'没有未挂售的藏品');
        }
        return $this->renderSuccess($myCollModel);
    }

    /**
     * 藏品详情
     */
    public function my_detail(){

        $coll_id = $this->request->param('coll_id','');

        if(empty($coll_id)){

            return $this->renderError('参数错误');

        }

        $coll = Db::name('coll')
            ->alias('c')
            ->field('c.coll_id,c.goods_id,c.addtime,c.zztime,c.coll_no,c.status,c.goods_name,yg.goods_address,yg.logo,yg.is_box,c.image_id,no.tx_hash,us.address as usaddress,sn.number,yg.get_total')
            ->where('c.coll_id',$coll_id)
            ->leftJoin('yoshop_goods yg','c.goods_id=yg.goods_id')
            ->leftJoin('yoshop_nft_order no','c.coll_id=no.order_id')
            ->leftJoin('yoshop_user us','c.give_id=us.user_id')
            ->leftJoin('yoshop_goods_sn sn','c.coll_id=sn.coll_id')
            ->find();

        if($coll['zztime'] > time()){
            $coll['giveType']=0;
        }else{
            $coll['giveType']=1;
        }

        return $this->renderSuccess(compact('coll'),'ok');

    }

    /**
     * 后台管理系统查询所有盲盒
     */
    public function boxAll(){
        $orderModel=new OrderModel;
        $boxList=$orderModel->boxList();
        return $this->renderSuccess($boxList);
    }

    /**
     * 转赠 限制十天后才能转赠
     */
    public function giveGoods(){
        $orderModel=new OrderModel;
        $posta=$this->postData();
        //获取藏品ID
        $collId=$posta['collId'];
        //获取赠送目标用户ID
        $heUserId=$posta['heuserid'];
        
        // $second_pswd = $posta['second_pswd'];
        // return $this->renderError('限时关闭转赠功能！');

        $user_id = UserService::getCurrentLoginUserId();

        // rsa密钥检测
        if(isset($posta['cipcont']) && $posta['cipcont']) {
            $res = Rsa::rsaContCheck(4, $posta['cipcont'], $user_id);
            if(!$res) return $this->renderError('密码错误');
        }else{
            return $this->renderError('缺少传参');
        }

        $orderCont=$orderModel->giveGoodModel($collId,$heUserId);
        
        if($orderCont===408){
            return $this->renderError('查无此人！');
        }

        if($orderCont !== true){

            return $this->renderError($orderCont);
            
        }

        if(!$orderCont){
            return $this->renderError('赠送失败！');
        }
        return $this->renderSuccess('赠送成功！');

    }

    /**
     * 查询抽取盲盒的积分和价格
     */
    public function seleIntegrals(){
        $orderModel=new OrderModel;
        $integrals=$orderModel->seleIntegral();
        $values = Setting::getItem('box');
        $data = [
            'price' => $values['price'],
            'integrals' => $integrals
        ];
        return $this->renderSuccess($data,'查询成功');
    }

    /**
     * 修改当前抽取盲盒的积分数量
     */
    public function drawBoxint(){

        $orderModel=new OrderModel;

        //获取post的数据
        $posta=$this->postData();

        //获取输入框的积分
        $ints=$posta['ints'];
        $drawBoxints=$orderModel->drawBoxInts($ints);

        if(!$drawBoxints){
            return $this->renderError('修改失败！');
        }
        return $this->renderSuccess('修改成功！');
    }

    /**
     * 修改转赠天数
     */
    public function dayUp(){
        $orderModel=new OrderModel;

        //获取post的数据
        $posta=$this->postData();
        $newDay=$posta['newday'];

        $setDay=$orderModel->setDays($newDay);

        if(!$setDay){
            return $this->renderError('修改失败！');
        }
        return $this->renderSuccess('修改成功！');
    }

    /**
     * 转售
     * @param $collId
     * @return Json
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function sale($collId, $price, $cipcont)
    {
        $transaction = new Transaction();
        $res = $transaction->sale($collId,$price, $cipcont);
        if(!$res){
            return $this->renderError('转售失败！');
        }
        return $this->renderSuccess('转售成功！');
    }

    /**
     * 下架
     * @param $collId
     * @return Json
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function unsale($collId)
    {
        $transaction = new Transaction();
        $res = $transaction->unsale($collId);
        if(!$res){
            return $this->renderError('下架失败！');
        }
        return $this->renderSuccess('下架成功！');
    }

    public function salelist($status)
    {
        $transaction = new Transaction();
        $res = $transaction->salelist($status);
        if(!$res){
            return $this->renderError('获取失败');
        }
        return $this->renderSuccess($res, 'SUCCESS');
    }

    /**
     * 二级市场列表
     * @return Json
     */
    public function market()
    {
        $transaction = new Transaction();
        $list = $transaction->list($this->request->param());
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 二级市场藏品详情
     * @return json
     */
    public function marketdetails()
    {
        $transaction = new Transaction();
        $info = $transaction->info($this->request->param());
        return $this->renderSuccess($info);
    }

    /**
     * 二级市场购买创建临时订单
     * @return void
     */
    public function addcretetemporder()
    {
        $param = $this->request->param();
        $user_id = User::getCurrentLoginUserId();

        if(!isset($param['transaction_id']) || !isset($param['goods_id'])) return $this->renderError('缺少传参');
        // 判断是否正在有人占用（查找未付款的订单）
        $res = OrderModel::where('transaction_id', $param['transaction_id'])->where('order_status', 10)->where('is_delete', 0)->find();

        if(!$res) {
            // 检查此人取消订单数不超过三个
            $daysum = OrderModel::where('user_id', $user_id)->where('type', 1)->whereDay('create_time')->where('is_delete', 1)->count();
            if($daysum >= 3) {
                return $this->renderError('当天购买次数受限');
            }else{
                $daysum = OrderModel::where('user_id', $user_id)->where('type', 1)->whereDay('create_time')->where('order_status', 10)->count();
                if($daysum >= 3) {
                    return $this->renderError('当天购买次数受限，请尽快购买待支付订单');
                }else{
                    $orderModel = new OrderModel();
                    $orderNo = $orderModel->orderNo();
                    Db::startTrans();
                    try {
                        $transaction = TransactionModel::where('id', $param['transaction_id'])->lock(true)->find();
                        if (empty($transaction) || $transaction['status'] != 0){
                            return $this->renderError('藏品不存在或已下架！');
                        }
                        if ($user_id == $transaction['user_id']){
                            return $this->renderError('不能购买自己上架的藏品！');
                        }
                        $data = jdConfig();
                        $data['requestNum'] = 'JD' . $orderNo;
                        $data['amount'] = $transaction['price'];

                        //填充需要的数据
                        $goodsarr = [
                            'total_price' => $transaction['price'],
                            'order_price' => $transaction['price'],
                            'pay_price' => $transaction['price'],
                            'pay_status' => 10,//默认为未付款
                            'order_status' => 10,//默认为进行中
                            'user_id' => $user_id,
                            'store_id' => 10001,
                            'create_time' => time(),
                            'goods_id' => $transaction['goods_id'],
                            'goods_sum' => 1,
                            'order_no' => $data['requestNum'],
                            'points_bonus' => 0, //赠送的积分数量
                            'is_box' => 0,
                            'type' => 1,   // 二级市场购买
                            'transaction_id' => $transaction['id'],   //交易id
                        ];
                        $arrList = OrderModel::create($goodsarr); //创建订单

                        //根据商品id获取商品信息
                        $goodsList = Db::name('goods')
                            ->alias('g')
                            ->join('yoshop_goods_image gi', 'g.goods_id=gi.goods_id')
                            ->where('g.goods_id', $transaction['goods_id'])
                            ->find();
                        //将数据插入到商品和订单的关系表中
                        $orderGoods = [
                            'goods_id' => $goodsList['goods_id'],
                            'goods_name' => $goodsList['goods_name'],
                            'image_id' => $goodsList['image_id'],
                            'deduct_stock_type' => 10,
                            'spec_type' => 10,
                            'goods_sku_id' => 0,
                            'content' => $goodsList['content'],
                            'goods_price' => $goodsList['goods_price_max'],
                            'total_num' => 1,
                            'total_price' => $goodsList['goods_price_max'],
                            'total_pay_price' => $goodsList['goods_price_max'],
                            'order_id' => $arrList['order_id'],
                            'user_id' => $user_id,
                            'create_time' => time()
                        ];
                        //将数据填充到商品订单关系表中
                        Db::name('order_goods')
                            ->insert($orderGoods);

                        Db::commit();
                        return $this->renderSuccess(['orderTime' =>$arrList['create_time'],'order_id' =>$arrList['order_id']], '临时订单创建成功！');
                    } catch (\Exception $e) {
                        // 回滚事务
                        Db::rollback();
                        return $this->renderError($e->getMessage());
                    }
                }
            }
        }else{
            if($res['user_id'] == $user_id) {
                $order = OrderModel::where('user_id', $user_id)
                 ->where('transaction_id', $param['transaction_id'])
                 ->where('order_status', 10)
                 ->find();
          
                return $this->renderSuccess(['orderTime' =>$order['create_time'],'order_id' =>$order['order_id']], '请尽快前往待支付订单购买！');
            
            }else{
                return $this->renderSuccess([], '商品已被占用！');
            }
        }
    }

    /**
     * 获取某个交易详情
     * @param int $transactionId
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function details(int $transactionId)
    {
        $transaction = new Transaction();
        $data = $transaction->get($transactionId);
        return $this->renderSuccess($data,'success');
    }

    /**
     * 获取所有藏品
     */
    public function tran_goods(){
        $goods_list = Goods::where('is_delete',0)
            ->field('goods_id,goods_name,sales_initial,sales_actual')
            ->select();

        return $this->renderSuccess([
            'data'  => $goods_list
        ]);

    }

    /**
     * 获取指定藏品的编号状态
     */
    public function tran_goods_sn($goods_id,$status=0){

        $goods_sn_list = GoodsSn::with(['goods'])
            ->field('id,goods_id,number,coll_id')
            ->where('goods_id',$goods_id)
            ->where('status',$status)
            ->paginate(15);

        if ($goods_sn_list){
            foreach ($goods_sn_list as &$value){

                if($value['status'] == 1){

                    $value['price'] = TransactionModel::where('goods_id',$value['goods_id'])
                                        ->where('coll_id',$value['coll_id'])
                                        ->where('status',0)
                                        ->value('price');

                }else{

                    $value['price'] = 0;

                }

                $value['get_total'] = $value['goods']['get_total'];
                unset($value['goods']);
            }
        }

        return $this->renderSuccess([
            'data'=>$goods_sn_list
        ]);
    }

    /**
     * 交易
     * @param int $transactionId
     * @return Json
     * @throws BaseException
     */
    public function transaction(int $transactionId, $cipcont)
    {
        $user_id = User::getCurrentLoginUserId();

        $user_car = UserIdcar::where('user_id',$user_id)->find();

        if(empty($user_car)){
            return $this->renderError('您未完成实名认证,请先实名');
        }

        // rsa密钥检测
        if(isset($cipcont) && $cipcont) {
            $res = Rsa::rsaContCheck(7, $cipcont, $user_id);
            if(!$res) return $this->renderError('密码错误');
        }else{
            return $this->renderError('缺少传参');
        }

        $pay_type = $this->request->param('pay_type','balance');
        $orderModel = new OrderModel();
        $orderNo = $orderModel->orderNo();
        Db::startTrans();
        try {
            $transaction = TransactionModel::where('id',$transactionId)->lock(true)->find();
            if (empty($transaction) || $transaction['status'] != 0){
                return $this->renderError('藏品不存在或已下架！');
            }
            if ($user_id == $transaction['user_id']){
                return $this->renderError('不能购买自己上架的藏品！');
            }
            $data = jdConfig();
            $data['requestNum'] = 'JD' . $orderNo;
            $data['amount'] = $transaction['price'];
            $arrList = OrderModel::field('order_id, order_no, pay_price')->where('order_status', 10)->where('user_id', $user_id)->where('transaction_id', $transactionId)->find();

            // 如果已有临时订单
            if($arrList) {
                OrderModel::where('order_id', $arrList['order_id'])->update(['order_status' => 30]);

                // 修改临时订单为已购买（2022/11/11 废弃）
                // TransactionOrder::where('transaction_id', $transactionId)->where('user_id', $user_id)->update(['status' => 2]);
            }else{
                //填充需要的数据
                $goodsarr = [
                    'total_price' => $transaction['price'],
                    'order_price' => $transaction['price'],
                    'pay_price' => $transaction['price'],
                    'pay_status' => 10,//默认为未付款
                    'order_status' => 10,//默认为进行中
                    'user_id' => $user_id,
                    'store_id' => 10001,
                    'create_time' => time(),
                    'goods_id' => $transaction['goods_id'],
                    'goods_sum' => 1,
                    'order_no' => $data['requestNum'],
                    'points_bonus' => 0, //赠送的积分数量
                    'is_box' => 0,
                    'pay_type' => 20,   //京东付款
                    'type' => 1,   // 二级市场购买
                    'transaction_id' => $transaction['id'],   //交易id
                ];
                $arrList = OrderModel::create($goodsarr); //创建订单
            }

            //根据商品id获取商品信息
            $goodsList = Db::name('goods')
                ->alias('g')
                ->join('yoshop_goods_image gi', 'g.goods_id=gi.goods_id')
                ->where('g.goods_id', $transaction['goods_id'])
                ->find();
            //将数据插入到商品和订单的关系表中
            $orderGoods = [
                'goods_id' => $goodsList['goods_id'],
                'goods_name' => $goodsList['goods_name'],
                'image_id' => $goodsList['image_id'],
                'deduct_stock_type' => 10,
                'spec_type' => 10,
                'goods_sku_id' => 0,
                'content' => $goodsList['content'],
                'goods_price' => $goodsList['goods_price_max'],
                'total_num' => 1,
                'total_price' => $goodsList['goods_price_max'],
                'total_pay_price' => $goodsList['goods_price_max'],
                'order_id' => $arrList['order_id'],
                'user_id' => $user_id,
                'create_time' => time()
            ];
            //将数据填充到商品订单关系表中
            Db::name('order_goods')
                ->insert($orderGoods);
            
            $transaction->save(['status' => 2]);

            if($pay_type == 'balance'){

                // 获取订单详情
                $model = $orderModel->getUserOrderDetail(intval($arrList['order_id']));

                // 余额支付
                if(!$orderModel->onPaymentByBalance($model['order_no'])){

                    throw new Exception('余额不足');
                }

            }else if($pay_type == 'sd'){

                $sd = new Sd();

                $result = $sd->pay([
                    'user_id'       => $user_id,
                    'username'      => $user_car['idcar_name'],
                    'idCard'        => $user_car['idcar'],
                    'ordersn'       => $arrList['order_no'],
                    'price'         => $arrList['pay_price'],
                    'goods_name'    => '购买商品',
                    'notify_url'    => 'api/callback/notify/type/sd',
                ]);

            }

            Db::commit();

            return $this->renderSuccess(['url'=>isset($result) && !empty($result)?$result:''],'成功');

        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return $this->renderError($e->getMessage());
        }
    }

    /**
     * 银行卡支付交易
     * @param int $transactionId
     * @return Json
     * @throws BaseException
     */
    public function transactionBank(int $transactionId,int $bank_id)
    {
        $user_id = User::getCurrentLoginUserId();
        //查询银行绑定信息
        $bank = UserBank::where(['user_id' => $user_id,'id' => $bank_id,'is_delete' => 0,'status' => 10])->find();
        if (empty($bank)){
            return $this->renderError('银行卡信息不存在！');
        }
        $orderModel = new OrderModel();
        $orderNo = $orderModel->orderNo();
        Db::startTrans();
        try {
            $transaction = TransactionModel::where('id',$transactionId)->lock(true)->find();
            if (empty($transaction) || $transaction['status'] != 0){
                return $this->renderError('藏品不存在或已下架！');
            }
            if ($user_id == $transaction['user_id']){
                return $this->renderError('不能购买自己上架的藏品！');
            }
            $data = jdConfigBank();
            $data['requestNum'] = 'JD' . $orderNo;
            $data['orderAmount'] = $transaction['price'];
            $data['goodsName'] = $transaction['name'];
            $data['goodsQuantity'] = 1;
            $data['userId'] = (string)$user_id;
            $data['bindId'] = $bank['bindId'];
            $data['userAccount'] = $bank['phone'];
            //填充需要的数据
            $goodsarr = [
                'total_price' => $transaction['price'],
                'order_price' => $transaction['price'],
                'pay_price' => $transaction['price'],
                'pay_status' => 10,//默认为未付款
                'order_status' => 10,//默认为进行中
                'user_id' => $user_id,
                'store_id' => 10001,
                'create_time' => time(),
                'goods_id' => $transaction['goods_id'],
                'goods_sum' => 1,
                'order_no' => $data['requestNum'],
                'points_bonus' => 0, //赠送的积分数量
                'is_box' => 0,
                'pay_type' => 20,   //京东付款
                'type' => 1,   //京东付款
                'transaction_id' => $transaction['id'],   //交易id
            ];
            OrderModel::create($goodsarr); //创建订单
            $transaction->save(['status' => 2]);
//             提交事务
            Db::commit();
            $res = $this->jdPayBank($data);
            if (!empty($res) && $res['success'] == true && $res['code'] == 'success') {
                OrderModel::update(['jd_order_no' => $res['orderNum']], ['order_no' => $res['requestNum']]);
                return $this->renderSuccess(['requestNum' => $res['requestNum']]);
            }
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return $this->renderError('购买失败！');
        }

        return $this->renderError('购买失败！');
    }

    /**
     * 京东银行卡支付
     * @throws BaseException
     */
    public function jdPayBank($data)
    {
        $data1 = json_encode($data);
        $time = time();
        $url = "https://openapi.duolabao.com/api/applyQuickPayWithCheck";
        $info = "secretKey=" . "03f8d570699c4ebf8ae12164dedc1e51e9fbf868" . "&timestamp=" . $time . "&path=/api/applyQuickPayWithCheck&body=" . $data1;
        $token = sha1($info);
        $token = strtoupper($token);
        $header = [
            'Content-Type: application/json',
            'accesskey: 38931903c42d47cb95e20d6e079133202ac23322',
            'timestamp: ' . $time,
            'token: ' . $token
        ];
        $res = post($url, $data1, $header);
        return json_decode($res, true);
    }

    /**
     * 京东支付
     * @throws BaseException
     */
    public function jbPay($data)
    {
        $data1 = json_encode($data);
        $time = time();
        $url = 'https://openapi.duolabao.com/v3/order/pay/create';
        $info = "secretKey="."03f8d570699c4ebf8ae12164dedc1e51e9fbf868" . "&timestamp=" . $time . "&path=/v3/order/pay/create&body=" . $data1;
        $token = sha1($info);
        $token = strtoupper($token);
        $header = [
            'Content-Type: application/json',
            'accesskey: 38931903c42d47cb95e20d6e079133202ac23322',
            'timestamp: '. $time,
            'token: ' . $token
        ];
        $res = post($url,$data1,$header);
        return json_decode($res,true);
    }

    /**
     * 获取转赠配置
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function config()
    {
        $config = Integrals::field('setday,charges,copyright')->find()->toArray();
        return $this->renderSuccess($config,'获取成功');
    }


    /**
     * 清理超时未支付订单
     */
    public function timeoutOrder(){
        $time = time();
        $order = OrderModel::where(['order_status' => 10,'pay_status' => 10])->select();
        $redis = new Redis();
        if (!empty($order)){
            foreach ($order as $value){
                if ($time - strtotime($value['create_time']) > 600){
                    if ($value['type'] == 0){
                        $name = $value['goods_id'] . '_list';
                        $redis->lpush($name,$value['order_id']);
                        //补库存
//                        Db::name('goods_sku')->where('goods_id',$value['goods_id'])->inc('stock_num')->update();
                    }else{
                        //二级市场订单变更为上架中
                        $transaction = TransactionModel::get($value['transaction_id']);
                        $transaction->save(['status' => 0]);
                    }
                    $value->save(['order_status' => 20]);
                }
            }
        }
        return $this->renderSuccess('操作成功！');
    }

}
