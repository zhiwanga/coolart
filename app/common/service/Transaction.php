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

use app\api\model\TransactionOrder;
use app\common\model\Coll;
use app\common\model\GoodsSn;
use app\common\model\UploadFile;
use app\store\model\Setting;
use app\api\service\User;
use app\common\model\Order as OrderModel;
use app\common\model\OrderGoods;
use app\common\model\Transaction as TransactionModel;
use app\controller\Rsa;
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
    public function sale($collId,$price, $cipcont)
    {
        $res = [
            'code' => 0,
            'msg'  => 'SUCCESS'
        ];
        if($price <= 0) {
            $res['code'] = 1;
            $res['msg'] = '价格设置错误';
            return $res;
        }
        $user_id = User::getCurrentLoginUserId();
        $coll = Coll::where(['coll_id' => $collId,'user_id' => $user_id,'status' => 0])->find();
        if (empty($coll)){
            $res['code'] = 1;
            $res['msg'] = '藏品信息不存在';
            return $res;
        }

        // if($coll['goods_id'] != 97) {
        //     $res['code'] = 1;
        //     $res['msg'] = '该商品暂时不能挂售';
        //     return $res;
        // }

        // 转售价格不能大于现价价格
        $limit_price = Db::name('goods')->where('goods_id', $coll['goods_id'])->value('limit_price');
        
        if($limit_price != 0) {
            if($price > $limit_price) {
                $res['code'] = 1;
                $res['msg'] = '挂售价格不能大于限价';
                return $res;
            }
        }
        // rsa密钥检测
        if(isset($cipcont) && $cipcont) {
            $res = Rsa::rsaContCheck(5, $cipcont, $user_id);
            if(!$res){
                $res['code'] = 1;
                $res['msg'] = '密钥检测失败';
                return $res;
            }
        }else{
            $res['code'] = 1;
            $res['msg'] = '密钥检测失败';
            return $res;
        }

        // 启动事务
        Db::startTrans();
        try {
            $log = [
                'user_id' => $user_id,
                'coll_id' => $collId,
                'name' => $coll['goods_name'],
                'level' => 1,
                'type' => $coll['type'],
                'price' => $price,
                'createtime' => time(),
                'updatetime' => time(),
                'goods_id' => $coll['goods_id']
            ];
            TransactionModel::create($log);
            $coll->save(['status' => 1]);
            //记录该编号为寄售中
            Db::name('goods_sn')
                ->where('coll_id',$collId)
                ->where('goods_id',$coll['goods_id'])
                ->update(['status'=>1]);

            Db::commit();
            return $res;
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            $res['code'] = 1;
            $res['msg'] = 'error';
            return $res;
        }
    }

    /**
     * 转售列表
     * @param [type] $collId
     * @param [type] $price
     * @param [type] $cipcont
     * @return void
     */
    public function salelist($param)
    {
        $user_id = User::getCurrentLoginUserId();
        $list = TransactionModel::alias('a')
                                ->leftJoin('yoshop_goods b', 'a.goods_id = b.goods_id')
                                ->leftJoin('yoshop_goods_image c','a.goods_id = c.goods_id')
                                ->leftJoin('yoshop_upload_file d','c.image_id = d.file_id')
                                ->leftJoin('yoshop_goods_sn e', 'a.coll_id = e.coll_id')
                                ->field('a.status, a.createtime, a.buytime, a.updatetime, a.price, a.coll_id, b.goods_name, b.xn_sale, d.file_path, e.number')
                                ->where('a.user_id', $user_id);
        if(isset($param['status']) && $param['status'] != '') {
            $status = intval($param['status']);
            if($status || $status === 0) {
                $list->where('a.status', $status);
            }
        }
        $list = $list->order('a.id', 'desc')
                    ->paginate(15)->toArray();
        return $list;
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
        $transaction->save(['status' => -1, 'updatetime' => time()]);
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
    public function list($param,int $listRows = 20)
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
        $list = TransactionModel::alias('a')
                        ->leftJoin('goods b', 'a.goods_id = b.goods_id')
                        ->leftJoin('goods_image c', 'a.goods_id = c.goods_id')
                        ->leftJoin('upload_file d', 'c.image_id = d.file_id')
                        ->leftJoin('goods_sn e', 'a.coll_id = e.coll_id')
                        ->leftJoin('order f', 'a.id = f.transaction_id and f.order_status = 10 and f.type = 1')
                        ->field('a.id, a.user_id, a.coll_id, a.name, a.level, a.type, a.price, a.createtime, a.buyer_id, a.buytime, a.updatetime, a.status, a.goods_id, b.get_total, b.xn_sale, d.file_path, e.number, f.order_id occupy')
                        ->where('a.buyer_id', 0)
                        ->where('a.status', 0)
                        ->where('a.name', 'like', '%'.$keyword.'%')
                        ->order('occupy', 'desc')
                        ->order($order, $type)
                        ->paginate($listRows)
                        ->toArray();

        return $list;
    }

    /**
     * 二级市场订单详情
     * @param [type] $param
     * @return array
     */
    public function info($param)
    {
        $order = 'a.create_time';
        $type = 'desc';
        if (isset($param['type'])){
            $order = 'b.price';
            $type = $param['type'];
        }
        $status = false;
        if (isset($param['status'])){
            $sta = intval($param['status']);
            $status = $sta;
        }

        $list = Db::name('goods_sn')
                    ->alias('a')
                    ->leftJoin('transaction_log b', 'a.coll_id = b.coll_id')
                    ->leftJoin('goods c', 'a.goods_id = c.goods_id')
                    ->field('a.number, c.xn_sale, c.goods_price_min, b.price, a.status, a.coll_id')
                    ->where('a.goods_id', $param['goods_id'])
                    ->where('a.number', '<>', $param['number']);
        if($status || ($status === 0)) {
            $list->where('a.status', $status);
        }
        $list = $list->order($order, $type)
                    ->select()
                    ->toArray();
        $list = $this->array_unique_fb($list);
        return $list;
    }

    public function array_unique_fb($array2D=[]){
        $temp = [];
        foreach ($array2D as $k=>$v){
            $temp[$v['coll_id']] = $v;
        }
        return array_values($temp);
    }

    /**
     * 二级市场点击购买生成临时订单（2022/11/11弃用）
     * @param [type] $param
     * @return void
     */
    public function creteTempOrder($param)
    {
        $user_id = User::getCurrentLoginUserId();
        $insert = [
            'user_id'           => $user_id,
            'goods_id'          => $param['goods_id'],
            'transaction_id'    => $param['transaction_id'],
            'status'            => 1,
            'create_time'       => time()
        ];
        return TransactionOrder::insert($insert);
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
                ->field('g.goods_id,g.goods_name,g.stock_total,g.update_time,g.xn_sale,g.goods_price_min,g.status,uf.file_path,g.content,gs.stock_num,g.startTime,g.author,g.goods_address,g.logo,uf.file_id')
                ->find();
         
             $coll = Db::name('coll') ->where('coll_id',$transaction['coll_id'])->find();
            $sn = Db::name('goods_sn') ->where('coll_id',$coll['coll_id'])->find();
            $good['number'] = $sn['number'];

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