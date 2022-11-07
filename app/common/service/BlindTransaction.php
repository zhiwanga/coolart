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

use app\common\model\Blind;
use app\common\model\BlindLog;
use app\common\model\Coll;
use app\common\model\UploadFile;
use app\store\model\Setting;
use app\api\service\User;
use app\common\model\Order as OrderModel;
use app\common\model\BlindTransaction as TransactionModel;
use think\facade\Db;

/**
 * 订单服务类
 * Class Order
 * @package app\common\service
 */
class BlindTransaction extends BaseService
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
    public function sale($log_id,$price)
    {
        $user_id = User::getCurrentLoginUserId();
        $blind_log = BlindLog::where(['id' => $log_id,'user_id' => $user_id,'status' => 0,'type'=>0])->find();
        if (empty($blind_log)){
            return false;
        }
        $blind = Blind::where('id',$blind_log['blind_id'])->find();

        $log = [
            'user_id' => $user_id,
            'log_id' => $log_id,
            'name' => $blind['blind_name'],
            'level' => 1,
            'price' => $price,
            'blind_id' => $blind['id']
        ];
        $ids=TransactionModel::create($log);
        $blind_log->save(['status' => 1]);
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
    public function unsale($log_id)
    {
        $user_id = User::getCurrentLoginUserId();
        $blind_log = BlindLog::where(['id' => $log_id,'user_id' => $user_id])->find();
        if (empty($blind_log)){
            return false;
        }
        $transaction = TransactionModel::where(['log_id' => $log_id,'status' => 0,'user_id' => $user_id])->find();
        if (empty($transaction)){
            return false;
        }
        $transaction->save(['status' => -1]);
        $blind_log->save(['status' => 0]);
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
        $order = 'create_time';
        $type = 'desc';
        if (isset($param['order'])){
            $order = $param['order'];
        }
        /*if (isset($param['type'])){
            $type = $param['type'];
        }*/
        if (!isset($param['keyword'])){
            $keyword = '';
        }else{
            $keyword = $param['keyword'];
        }
        $list = TransactionModel::where('buyer_id',0)->where('status',0)->where('name','like','%'.$keyword.'%')->order($order,$type)->paginate($listRows);;
        if ($list){
            foreach ($list as &$value){
                $thumb_id = Blind::where('id',$value['blind_id'])->value('thumb');
                $file = UploadFile::where('file_id','=',$thumb_id)->find();
                $value['file_path'] = $file['preview_url'];
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
            $good= Db::name('blind')
                ->alias('b')
                ->where('b.id',$transaction['blind_id'])
                ->join(['yoshop_upload_file'=>'uf'],'b.thumb=uf.file_id')
                ->field('b.get_total,b.get_limit')
                ->field('b.id,b.blind_name as goods_name,b.total as stock_total,b.update_time,b.price as goods_price_min,uf.file_path,b.content,b.sales as stock_num,b.starttime as startTime,uf.file_id')
                ->find();

            $good['content'] = htmlspecialchars_decode($good['content']);
            $file = UploadFile::where('file_id',$good['file_id'])->find();
            $good['file_path'] = base_url() . '/' . $file['file_path'];
            $good['transaction_id'] = $transactionId;
            $good['price'] = $transaction['price'];
            $good['goods_address'] = '0xe01b312FD78C98451ccb2E9E38B7f561f7B95952';
        }
        return $good;
    }
}