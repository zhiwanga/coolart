<?php


namespace app\api\controller;


use app\api\model\Coll;
use app\api\model\GoodsImage;
use app\common\enum\user\balanceLog\Scene as SceneEnum;
use app\common\library\H5pay as Sd;
use app\common\library\helper;
use app\common\model\BlindLog;
use app\common\model\BlindOrder;
use app\common\model\Goods;
use app\common\model\GoodsSn;
use app\common\model\Integrals;
use app\common\model\BlindTransaction as TransactionModel;
use app\common\model\User;
use app\common\model\user\BalanceLog;
use app\common\model\UserIdcar;
use app\common\service\BaseService;
use app\common\service\BlindTransaction;
use cores\exception\BaseException;
use think\App;
use app\common\model\Blind as BlindModel;
use think\cache\driver\Redis;
use think\Exception;
use think\facade\Db;

use think\response\Json;

class Blind extends Controller
{

    protected $model;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new BlindModel();
    }

    public function lists(){

        $keyword = $this->request->param('keyword','');

        $where = [];

        if(!empty($keyword)){

            $where[] = ['blind_name','like',"%".$keyword."%"];

        }

        $lists = $this->model
            ->where($where)
            ->where('is_delete',0)
            ->order('id desc')
            ->paginate();

        return $this->renderSuccess(['product'=>$lists,'time'=>date('Y-m-d H:i:s')]);
    }

    public function detail($blindid,$log_id=0){

        // 获取商品详情
        $goodsInfo = $this->model->where('id',$blindid)->find();

        $goodsInfo['log_id'] = $log_id;

        //商品详情
        $goods = \app\common\model\Goods::with(['images.file'])
            ->where('goods_id','in',$goodsInfo['goods_ids'])
            ->where('stock_total','>',0)
            ->where('is_box',1)
            ->field('goods_id,goods_name,sales_initial,sales_actual,probability,goods_price_min,starttime')
            ->select();

        $goodsInfo['goods'] = $goods;

        foreach($goodsInfo['goods'] as $k=>&$g){

            $goods_images = helper::getArrayColumn( $g['images'], 'file');

            // 商品主图
            $g['goods_image'] = current($goods_images)['preview_url'];

        }unset($g);

        $goodsInfo['win_id'] =0;

        return $this->renderSuccess(['goodsinfo'=>$goodsInfo,'time'=>date('Y-m-d H:i:s')]);

    }

    /**
     * 盲盒订单
     */
    public function orders()
    {

        $userid = \app\api\service\User::getCurrentLoginUserId();

        $blind_log = BlindOrder::with(['blind' => function ($query) {

            return $query->field('id,blind_name,price,thumb');
        }])
            ->where('user_id', $userid)
            ->where('is_delete',0)
            ->order('id desc')
            ->paginate();

        return $this->renderSuccess(['lists' => $blind_log]);

    }

    /**
     * 未拆分盲盒列表
     */
    public function logs(){

        $keyword = $this->request->param('keyword','');

        $where = [];

        if(!empty($keyword)){

            $where[] = ['b.blind_name','like',"%".$keyword."%"];

        }

        $userid = \app\api\service\User::getCurrentLoginUserId();

        $blind_log = BlindLog::with(['file'])
            ->alias('l')
            ->field('l.*,b.blind_name,b.price,b.thumb')
            ->where($where)
            ->where('l.user_id',$userid)
            ->where('l.type',0)
            ->join(['yoshop_blind'=>'b'],'b.id=l.blind_id')
            ->order('l.id desc')
            ->paginate();

        return $this->renderSuccess(['lists'=>$blind_log]);

    }

    /**
     * 打开盲盒
     */
    public function upblind(int $log_id){

        $userid = \app\api\service\User::getCurrentLoginUserId();

        $blindlog_info = BlindLog::where('id',$log_id)->find();

        if(empty($blindlog_info) || $blindlog_info['type'] != 0 || $blindlog_info['status'] != 0){

            return $this->renderError('您已打开盲盒或不存在该盲盒');

        }

        $goods = \app\api\model\Goods::get($blindlog_info['goods_id']);

        $blind = BlindModel::where('id',$blindlog_info['blind_id'])->find();

        $config = Integrals::find();

        $time = $config['setday'] * 86400;  //转赠时间

        $blindlog_info->startTrans();
        try {

            //添加藏品到藏品库中
            $collarr=[
                'goods_id'  =>  $goods['goods_id'],
                'goods_name'  =>  $goods['goods_name'],
                'user_id'   =>  $userid,
                'addtime'   =>  time(),
                'zztime'    =>  time() + $time,
                'coll_no'   => random(8),
                'image_id'  => $this->getImage($goods['goods_id']),
                'type'      => 1,
                'order_no'  => 'Art'.date('YmdHis').time(),
                'is_sl'     => $goods['is_sl']
            ];

            //将盲盒商品放入藏品库中
            $collen=Db::name('coll')
                ->insertGetId($collarr);

            $blindlog_info->save(['type'=>1]);

            GoodsSn::create([
                'goods_id'  => $goods['goods_id'],
                'number'    => 0,
                'coll_id'   => $collen
            ]);

            $blindlog_info->commit();
            return $this->renderSuccess([
                'coll_id'   => $collen
            ],'操作成功');
        }catch (Exception $e){

            $blindlog_info->rollback();
            return $this->renderError($e->getMessage());
        }

    }

    /**
     * 获取所有藏品
     */
    public function tran_goods(){
        $goods_list = BlindModel::where('is_delete',0)
            ->field('id,blind_name')
            ->select();

        return $this->renderSuccess([
            'data'  => $goods_list
        ]);

    }
    /**
     * 转售盲盒
     */
    public function sale($log_id,$price){

        $transaction = new BlindTransaction();
        $res = $transaction->sale($log_id,$price);
        if(!$res){
            return $this->renderError('转售失败！');
        }
        return $this->renderSuccess('转售成功！');
    }

    /**
     * 下架转售盲盒
     */
    public function unsale($log_id)
    {
        $transaction = new BlindTransaction();
        $res = $transaction->unsale($log_id);
        if(!$res){
            return $this->renderError('下架失败！');
        }
        return $this->renderSuccess('下架成功！');
    }

    /**
     * 二级市场列表
     * @return Json
     */
    public function market()
    {
        $transaction = new BlindTransaction();
        $list = $transaction->list($this->request->param());
        return $this->renderSuccess(compact('list'));
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
        $transaction = new BlindTransaction();
        $data = $transaction->get($transactionId);
        return $this->renderSuccess($data,'success');
    }

    /**
     * 交易
     * @param int $transactionId
     * @return Json
     * @throws BaseException
     */
    public function transaction(int $transactionId)
    {
        $user_id = \app\api\service\User::getCurrentLoginUserId();
        $pay_type = $this->request->param('pay_type','balance');
        $user_car = UserIdcar::where('user_id',$user_id)->find();
        if(empty($user_car)){

            return $this->renderError('您未完成实名认证,请先实名');

        }
        $orderModel = new BlindOrder();
        $orderNo = 'BLD'.date('YmdHis').time();
        Db::startTrans();
        try {
            $transaction = TransactionModel::where('id',$transactionId)->lock(true)->find();
            if (empty($transaction) || $transaction['status'] != 0){
                return $this->renderError('盲盒不存在或已下架！');
            }
            if ($user_id == $transaction['user_id']){
                return $this->renderError('不能购买自己上架的盲盒！');
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
                'pay_type' => 20,   //京东付款
                'type' => 1,   //京东付款
                'transaction_id' => $transaction['id'],   //交易id
            ];
            $goodsarr = [
                'blind_id'      => $transaction['blind_id'],
                'user_id'       => $user_id,
                'price'         => $transaction['price'],
                'ordersn'       => $orderNo,
                'store_id'      => 10001,
                'total'         => 1,
                'status'        => 0,
                'type'          => 1,
                'transaction_id'=> $transactionId
            ];

            $arrList = BlindOrder::create($goodsarr); //创建订单

            $transaction->save(['status' => 1]);

            $pay_price = $transaction['price'];

            if($pay_type == 'balance'){

                $user = \app\api\service\User::getCurrentLoginUser();

                if ($user['balance'] < $pay_price) {

                    throw new Exception('用户余额不足，无法使用余额支付');

                }

                // 累积用户总消费金额
                User::setIncPayMoney($user['user_id'], (float)$pay_price);

                // 更新用户余额
                User::setDecBalance((int)$user['user_id'], (float)$pay_price);
                // 新增余额变动记录
                BalanceLog::add(SceneEnum::CONSUME, [
                    'user_id' => (int)$user['user_id'],
                    'money' => -$pay_price,
                ], ['order_no' => $orderNo]);

                $transaction = TransactionModel::get($transactionId);
                $config = Integrals::field('charges,copyright')->find();
                $price = $transaction['price'] * (100 - ($config['charges'] + $config['copyright'])) / 100;
                $transaction->save(['buyer_id' => $user_id,'buytime' => time(),'status' => 1]); //交易状态修改
                BlindLog::update(['user_id' => $user_id,'status' => 0,'type'=>0],['id' => $transaction['log_id']]); //转移到买方账上
                User::setIncBalance($transaction['user_id'],$price); //增加卖方余额

            }else if($pay_type == 'sd'){

                $sd = new Sd();

                $result = $sd->pay([
                    'user_id'       => $user_id,
                    'username'      => $user_car['idcar_name'],
                    'idCard'        => $user_car['idcar'],
                    'ordersn'       => $arrList['ordersn'],
                    'price'         => $arrList['price'],
                    'goods_name'    => '购买盲盒',
                    'notify_url'    => 'api/callback/blind_notify/type/sd',
                ]);

            }

            Db::commit();

            return $this->renderSuccess(['url'=>isset($result) && !empty($result)?$result:''],'成功');

        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return $this->renderError('购买失败！');
        }

        return $this->renderError('购买失败！');
    }


    /**
     * 清理超时未支付订单
     */
    public function timeoutOrder(){
        die('1');
        $time = time();
        $order = OrderModel::where(['order_status' => 10,'pay_status' => 10])->select();
        $redis = new Redis();
        if (!empty($order)){
            foreach ($order as $value){
                if ($time - strtotime($value['create_time']) > 600){

                    //二级市场订单变更为上架中
                    $transaction = \app\common\model\Transaction::get($value['transaction_id']);
                    $transaction->save(['status' => 0]);

                    $value->save(['order_status' => 20]);
                }
            }
        }
        return $this->renderSuccess('操作成功！');
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

}