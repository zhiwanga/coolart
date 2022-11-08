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

namespace app\common\service;

use app\common\model\Coll;
use app\common\model\GoodsSn;
use app\common\model\UploadFile;
use app\store\model\Setting;
use app\api\service\User;
use app\common\model\Order as OrderModel;
use app\common\model\OrderGoods;
use app\common\model\Transaction as TransactionModel;
use think\facade\Db;

/**
 * 订单服务类
 * Class Order
 * @package app\common\service
 */
class Transaction extends BaseService
{
    /**
     * 转售
     * @param $collId
     * @return bool
     * @throws \cores\exception\BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function sale($collId,$price)
    {
        $user_id = User::getCurrentLoginUserId();
        $coll = Coll::where(['coll_id' => $collId,'user_id' => $user_id,'status' => 0])->find();
        if (empty($coll)){
            return false;
        }
//        $order = OrderModel::where('order_no',$coll['order_no'])->find();
//        $values = Setting::getItem('box');
        $log = [
            'user_id' => $user_id,
            'coll_id' => $collId,
            'name' => $coll['goods_name'],
            'level' => 1,
            'type' => $coll['type'],
            'price' => $price,
            'createtime' => time(),
            'goods_id' => $coll['goods_id']
        ];
        $res = TransactionModel::create($log);
        $coll->save(['status' => 1]);
        //记录该编号为寄售中
        Db::name('goods_sn')
            ->where('coll_id',$collId)
            ->where('goods_id',$coll['goods_id'])
            ->update(['status'=>1]);

        // 转售增加订单
        $orderInsert = [
            'order_no'          => (new OrderModel)->orderNo(),
            'total_price'       => $price,
            'pay_price'         => $price,
            'order_price'       => $price,
            'user_id'           => $user_id,
            'goods_id'          => $coll['goods_id'],
            'transaction_id'    => $res->id,
            'type'              => 1,
        ];
        Db::name('order')->insert($orderInsert);

        return true;
    }

    /**
     * 下架
     * @param $collId
     * @return bool
     * @throws \cores\exception\BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function unsale($collId)
    {
        $user_id = User::getCurrentLoginUserId();
        $coll = Coll::where(['coll_id' => $collId,'user_id' => $user_id])->find();
        if (empty($coll)){
            return false;
        }
        $transaction = TransactionModel::where(['coll_id' => $collId,'status' => 0,'user_id' => $user_id])->find();
        if (empty($transaction)){
            return false;
        }
        $transaction->save(['status' => -1]);
        $coll->save(['status' => 0]);
        //退出寄售
        Db::name('goods_sn')
            ->where('coll_id',$collId)
            ->where('goods_id',$coll['goods_id'])
            ->update(['status'=>0]);

        Db::name('order')->where('transaction_id', $transaction['id'])->update(['order_status' => 20]);
        return true;
    }

    /**
     * 二级市场列表
     * @param $param
     * @param int $listRows
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function list($param,int $listRows = 6)
    {
        $order = 'createtime';
        $type = 'desc';
        if (isset($param['order'])){
            $order = $param['order'];
        }
        if (isset($param['type'])){
            $type = $param['type'];
        }
        if (!isset($param['keyword'])){
            $keyword = '';
        }else{
            $keyword = $param['keyword'];
        }
        $list = TransactionModel::with(['goods.file','goodssn','goodsinfo'])
            ->alias('t')
            ->where('t.buyer_id',0)
            ->where('t.status',0)
            ->where('t.name','like','%'.$keyword.'%')
            ->order($order,$type)
            ->paginate($listRows);;
        if ($list){
            foreach ($list as &$value){
                $value['file_path'] = $value['goods']['file']['preview_url'];
                $value['get_total'] = $value['goodsinfo']['xn_sale'];
                $value['number']    = $value['goodssn']['number'];
                unset($value['goods']);unset($value['goodssn']);
            }
        }
        return $list;
    }


    /**
     * 获取单个交易详情
     * @param $transactionId
     * @return array|Db|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function get($transactionId)
    {
        $transaction = TransactionModel::get($transactionId);
        $good = [];
        if ($transaction){
            $good= Db::name('goods')
                ->alias('g')
                ->where('g.goods_id',$transaction['goods_id'])
                ->join(['yoshop_goods_image'=>'gi'],'g.goods_id=gi.goods_id')
                ->join(['yoshop_upload_file'=>'uf'],'gi.image_id=uf.file_id')
                ->join(['yoshop_goods_sku'=>'gs'],'g.goods_id=gs.goods_id')
                ->field('g.get_total,g.get_limit')
                ->field('g.goods_id,g.goods_name,g.stock_total,g.update_time,g.goods_price_min,g.status,uf.file_path,g.content,gs.stock_num,g.startTime,g.author,g.goods_address,g.logo,uf.file_id')
                ->find();

            $good['content'] = htmlspecialchars_decode($good['content']);
            $good['transaction_id'] = $transactionId;
            $file = UploadFile::where('file_id',$good['file_id'])->find();
            if (empty($file['domain'])){
                $good['file_path'] = base_url() . 'uploads/' . $file['file_path'];
            }else{
                $good['file_path'] = $file['domain'] . '/' . $file['file_path'];
            }
            $good['price'] = $transaction['price'];
            $good['goods_address'] = '0xe01b312FD78C98451ccb2E9E38B7f561f7B95952';
        }
        return $good;
    }
}