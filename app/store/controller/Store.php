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
declare (strict_types = 1);

namespace app\store\controller;

use app\api\model\User as UserModel;
use app\store\model\Order as OrderModel;
use app\store\model\Store as StoreModel;
use app\store\service\order\Checkout;

/**
 * 商家中心控制器
 * Class Store
 * @package app\store\controller
 */
class Store extends Controller
{
    /**
     * 获取当前登录的商城信息
     * @return array
     */
    public function info()
    {
        // 商城详情
        $model = StoreModel::detail($this->getStoreId());
        return $this->renderSuccess(['storeInfo' => $model]);
    }

    /**
     * 更新商城信息
     * @return array
     */
    public function update()
    {
        // 商城详情
        $model = StoreModel::detail($this->getStoreId());
        // 更新记录
        if (!$model->edit($this->postForm())) {
            return $this->renderError($model->getError() ?: '更新失败');
        }
        return $this->renderSuccess('更新成功');
    }
    
    
    /**
     * 查询关于我们
     */
    public function aboutUsColl(){
        $abotUs = new UserModel;

        $abotUsMod=$abotUs->aboutUsMod();

        if($abotUsMod==0){
            return $this->renderError('查询失败，请稍后再试！');
        }
        return $this->renderSuccess($abotUsMod,'查询成功！');
    }

    /**
     *修改关于我们
     */
    public function aboutUpColl(){
        $abotUs = new UserModel;

        $posts=$this->postData();

        //获取传过来的用户id
        $textend=$posts['textend'];

        $abotUsMod=$abotUs->aboutUpMod($textend);

        if($abotUsMod){
            return $this->renderSuccess(['type'=> true],'修改成功！');
        }
        return $this->renderError('新内容与旧内容不能相同！');
    }
    
    /**
     * 查询抽取盲盒的积分
     */
    public function seleIntegrals(){
        $orderModel=new OrderModel;
        $integrals=$orderModel->seleIntegral();
        return $this->renderSuccess($integrals);
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

        return $this->renderSuccess(['date'=> $drawBoxints],'修改成功！');
    }
    
    
    

    /**
     * 修改转赠天数
     */
    public function dayUp(){
        $orderModel = new OrderModel;
        //获取post的数据
        $data = $this->postData();
        $setDay = $orderModel->setDays($data);

        if(!$setDay){
            return $this->renderError('修改失败！');
        }
        return $this->renderSuccess(['type'=> true],'修改成功！');

    }
    
    /**
     * 查询转赠天数
     */
    public function chaGiveDay(){
        $orderModel=new OrderModel;
        $chaGiveDay=$orderModel->getChangedDays();
        return $this->renderSuccess($chaGiveDay,'查询成功！');
    }


   /**
     * 修改空投设置
     */
    public function setAirdrop(){
        //获取修改的内容
        $posta=$this->postData();
        $type = isset($posta['type'])?$posta['type']:0;
        $manSum=1;
        $setDay=1;
        $goodsid=$posta['goodsid'];
        $user_ids=$posta['user_ids'];
        $storeModel=new StoreModel;

        $upkong=$storeModel->upkong($manSum,$setDay);

        $checkout = new Checkout();

        if($type==0){

            $res = $checkout->airdropNew($goodsid,$user_ids);

            if($res['code'] != 200){

                return $this->renderError($res['message']);

            }

        }else{

            $res = $checkout->airdropBlind($goodsid,$user_ids);

            if($res['code'] != 200){

                return $this->renderError($res['message']);

            }

        }



        return $this->renderSuccess(['date'=>$upkong],'空投成功！');

    }


    /**
     * 查询空投设置
     */
    public function getAirdrop(){
        $storeModel=new StoreModel;

        $getkong=$storeModel->getkong();
        return $this->renderSuccess(['date'=>$getkong],'查询成功！');
    }

//    /**
//     * 空投
//     */
//    public function airdrop(){
//
//        $storeModel=new StoreModel;
//
//        //获取邀请人数限制和空投间隔天数
//        $arr=$storeModel->getkong();
//
//        $getManCount=$arr['invita'];
//        $getFatime=$arr['fatime'];
//        $getnewtime=$arr['newtime'];
//
//        //执行空投功能
//        $arrList=$storeModel->getUsers($getManCount,$getFatime,$getnewtime);
//
//        if($arrList==false){
//            return $this->renderError('空投失败，请联系管理员！');
//        }
//        return $this->renderSuccess($arrList,'空投成功！');
//
//    }


    /**
     * 查找反馈
     */
    public function feedbackColl(){
        $feedbacks = new UserModel;

        $feedbaMod=$feedbacks->feedbaMod();

        if ($feedbaMod==0){
            return $this->renderError('查询失败，请稍后再试！');
        }
        return $this->renderSuccess($feedbaMod,'查询成功！');
    }
}
