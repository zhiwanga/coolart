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

use app\api\model\Order as OrderModel;
use app\api\model\Setting as SettingModel;
use app\store\model\Express as ExpressModel;
use app\common\enum\order\PayType as OrderPayTypeEnum;
use cores\exception\BaseException;
use think\Db;
use think\response\Json;



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
        $posta=$this->postData();
        $userid=$posta['userid'];
        
        
        
        //返回抽中的商品ID，name，图片路径
        $cqbox=$orderModel->cbox($userid);

        if(!$cqbox['code']){
            return $this->renderError($cqbox['msg']);
        }
        return $this->renderSuccess($cqbox,'兑换成功');
    }

    /**
     * 查询该用户所有藏品
     */
    public function myColl(){
        $orderModel=new OrderModel;
        $posta=$this->postData();
        $userid=$posta['userid'];

        //查询我的藏品
        $myCollModel=$orderModel->myColl($userid);

        if($myCollModel==false){
            return $this->renderError('您还没有藏品！');
        }
        return $this->renderSuccess($myCollModel);
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

        $orderCont=$orderModel->giveGoodModel($collId,$heUserId);
        
        if($orderCont===408){
            return $this->renderError('查无此人！');
        }
        if(!$orderCont){
            return $this->renderError('赠送失败！');
        }
        return $this->renderSuccess('赠送成功！');

    }

    /**
     * 查询抽取盲盒的积分
     */
    public function seleIntegrals(){
        $orderModel=new OrderModel;
        $integrals=$orderModel->seleIntegral();
        return $this->renderSuccess($integrals,'查询成功');
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
     * 测试
     */
    public function tet(){

        $orderModel=new OrderModel;
        $a=$orderModel->test();
    }

}
