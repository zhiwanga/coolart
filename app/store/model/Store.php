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

namespace app\store\model;

use app\common\model\Store as StoreModel;
use think\facade\Db;

/**
 * 商家记录表模型
 * Class Store
 * @package app\store\model
 */
class Store extends StoreModel
{
    /**
     * 更新记录
     * @param array $data
     * @return bool
     */
    public function edit(array $data)
    {
        // 是否删除图片
        !isset($data['logo_image_id']) && $data['logo_image_id'] = 0;
        return $this->save($data) !== false;
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
     * 查询转赠天数
     */
    public function getChangedDays(){
        $cha=Db::name('integrals')
            ->where('id',1)
            ->field('setday')
            ->select()->toArray();
        return $cha;

    }

    /**
     * 修改空投设置
     */
    public function upkong($manSum,$setDay){
        $uparr=Db::name('aboutus')
            ->where('id',1)
            ->update([
                'invita'    =>  $manSum,
                'fatime'    =>  $setDay
            ]);

        return $uparr;
    }

    /**
    * 查询空投设置
    */
    public function getkong(){
        $getarr=Db::name('aboutus')
            ->where('id',1)
            ->field('id,invita,fatime,newtime')
            ->find();
        return $getarr;
    }


//    /**
//     * 空投
//     * $getnewtime 上一次空投投放时间
//     * $getFatime 空投投放间隔
//     */
//    public function getUsers($getManCount,$getFatime,$getnewtime){
//        //查询所有用户的邀请人数
//
//        //查询所有邀请过人的用户ID
//        $getUsers=Db::name('user')
//            ->distinct(true)
//            ->where('extension_id','>',0)
//            ->field('extension_id')
//            ->select()->toArray();
//        $arr=array();
//        //然后把数据放入到数组中
//        foreach($getUsers as $key => $value){
//            $arr[]=$value['extension_id'];
//        }
//
//        $arrList=array();
//        //根据ID循环查找
//        foreach ($arr as $k =>$v){
//            $arrList[$v]=Db::name('user')
//                ->field('extension_id')
//                ->where('extension_id',$v)
//                ->count('extension_id');
//        }
//
//        $newArrList=array();
//        //再次遍历 把所有超过一定次数的ID筛选出来
//        foreach ($arrList as $ke => $va){
//            if($va>=$getManCount){
//                $newArrList[]=$ke;
//            }
//        }
//        //$getnewtime上一次空投时间
//        //计算应该投放空投的时间
//        $cheng=$getnewtime+$getFatime*86400;
//
//        //判断当前是否已经到了发送空投的时间
//        if($cheng<time()){
//            //符合条件，发放空投
//            foreach ($newArrList as $kk => $vv){
//                //查询所有商品
//                $goodsAll=Db::name('goods')
//                    ->alias('g')
//                    ->join('yoshop_goods_sku gs','g.goods_id=gs.goods_id')
//                    ->where('gs.stock_num','>',1)
//                    ->where('g.is_box',0)
//                    ->field('goods_id')
//                    ->select()->toArray();
//                //将商品放到数组中
//                foreach ($goodsAll as $keys => $val){
//                    $goodsArr[]=$goodsAll[$keys]['goods_id'];
//                }
//                //生成随机数字
//                $rands=rand(0,count($goodsArr)-1);
//                //获取到随机下标的商品ID
//                $goodsId=$goodsArr[$rands];
//
//                //将该商品发送给符合条件的用户
//
//                //根据商品ID查询商品名称
//                $goodslist=Db::name('goods')
//                    ->where('goods_id',$goodsId)
//                    ->field('goods_name')
//                    ->find();
//                //新增藏品库
//                $newArr=[
//                    'user_id'   =>  $newArrList[$kk],
//                    'goods_id'  =>  $goodsId,
//                    'addtime'   =>  time(),
//                    'zztime'    =>  0,
//                    'order_no'  =>  0,
//                    'goods_name'=>  $goodslist
//                ];
//
//                //新增
//                $setst=Db::name('coll')
//                    ->insert($newArr);
//                //新增成功后库存-1
//                if($setst==1){
//                    $kc=Db::name('goods')
//                        ->alias('g')
//                        ->join('yoshop_goods_sku gs','g.goods_id=gs.goods_id')
//                        ->where('gs.goods_id',$goodsId)
//                        ->dec('gs.stock_num')
//                        ->update();
//                }
//            }
//            //将当前发放时间记录到数据库，以便下次使用
//            $a=Db::name('aboutus')
//                ->where('id',1)
//                ->update(['newtime'=>time()]);
//            //返回所有发送了空投的用户ID
//            return $newArrList;
//        }
//        return false;
//    }
}
