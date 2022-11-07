<?php


namespace app\api\controller;


use app\api\model\Coll;
use app\common\enum\user\balanceLog\Scene as SceneEnum;
use app\common\library\Alipay;
use app\common\library\H5pay;
use app\common\library\Pay;
use app\common\library\Sd;
use app\common\model\Blind;
use app\common\model\BlindLog;
use app\common\model\BlindOrder;
use app\common\model\BlindTransaction as TransactionModel;
use app\common\model\Integrals;
use app\common\model\Transaction;
use app\common\model\User;
use app\common\model\user\BalanceLog;
use think\Exception;
use think\facade\Db;
use think\Model;

/**
 * Class Callback
 * @package app\api\controller回调接口
 */
class Callback extends Controller
{

    public function cs(){

        $order_data = [
            'user_id'       => rand(1,100),

            'ordersn'       => '202210232311128982',
            'price'         => '1.00',
            'goods_name'    => '测试',
            'notify_url'    => 'api/callback/rechargenotify/type/new_sd',
        ];

        $alipay = new Pay();

        $rs = $alipay->createDLBPay($order_data);

        header('location:'.$rs['res_url']);
    }

    public function ht_car(){

        file_put_contents($_SERVER['DOCUMENT_ROOT'].'/ht_car.txt',serialize($this->request->param()));


    }

    /**
     * 获取充值数据
     */
    public function get_pid(){

        $id = $this->request->param('pid','');

        $p_info = Db::name('pay_log')->where('id',$id)->find();

        return $this->renderSuccess([
            'data'  => unserialize($p_info['post_data']),
            'url'   => $p_info['url']
        ]);
    }

    /**
     * 充值
     */
    public function rechargenotify($type='alipay'){

        $ordersn = '';

        if($type == 'sd'){

            file_put_contents($_SERVER['DOCUMENT_ROOT'].'/new_sd.txt',serialize(json_decode(stripslashes($_POST['data']),true)));

            $result = json_decode(stripslashes($_POST['data']),true);

            if($result['head']['respCode'] != 000000){

                die('error');

            }

            $ordersn = $result['body']['tradeNo'];


        }else{

            file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logs.txt',serialize($this->request->param()));

            $result = $this->request->param();

            $ordersn = $result['requestNum'];

            if($result['status'] != 'SUCCESS'){

                die('error');

            }
        }

        if(empty($ordersn)){

            die('error');

        }

        $orderModel = new \app\api\model\recharge\Order();

        $order_info = $orderModel->where('order_no',$ordersn)->find();

        if(!empty($order_info) && $order_info['pay_status'] == 10) {

            $orderModel->startTrans();
            try {

                $order_info->save([
                    'pay_status' => 20,
                    'pay_time' => time()
                ]);

                // 更新用户余额
                User::setIncBalance((int)$order_info['user_id'], (float)$order_info['actual_money']);
                // 新增余额变动记录
                BalanceLog::add(SceneEnum::RECHARGE, [
                    'user_id' => (int)$order_info['user_id'],
                    'money' => $order_info['actual_money'],
                ], ['order_no' => $order_info['order_no']]);

                $orderModel->commit();

                echo 'SUCCESS';
            } catch (Exception $e) {

                $orderModel->rollback();

                file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/error.txt', serialize($e->getMessage()));

                echo 'FAIL';
            }

        }

    }

    /**盲盒**/
    public function blind_notify($type='alipay'){

        $ordersn = '';

        if($type == 'sd'){

            file_put_contents($_SERVER['DOCUMENT_ROOT'].'/sd.txt',serialize(json_decode(stripslashes($_POST['data']),true)));

            $result = json_decode(stripslashes($_POST['data']),true);

            if($result['head']['respCode'] != 000000){

                die('error');

            }

            $ordersn = $result['body']['tradeNo'];


        }else{

            file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logs.txt',serialize($this->request->param()));

            $result = $this->request->param();

            $ordersn = $result['out_trade_no'];

            if($result['trade_status'] != 'TRADE_SUCCESS'){

                die('error');

            }
        }

        if(empty($ordersn)){

            die('error');

        }


        $blindOrderModel = new BlindOrder();

        $blindorder = $blindOrderModel->where('ordersn',$ordersn)->find();

        if(empty($blindorder) || $blindorder['status']==1){

            return false;

        }

        $blindOrderModel->startTrans();
        try {


            $blind = Blind::where('id',$blindorder['blind_id'])->find();

            Db::name('blind')->where('id',$blind['id'])->dec('total',$blindorder['total'])->update();

            Db::name('blind')->where('id',$blind['id'])->inc('sales',$blindorder['total'])->update();

            if($blindorder['type'] == 0){

                //将随机选中的盲盒数量减少一个
                for($i=1;$i<=$blindorder['total'];$i++){

                    $wind_id = Blind::rand_goods($blind['goods_ids']);

                    if(empty($wind_id)){

                        //未知错误
                        continue;

                    }

                    Db::name('goods')
                        ->alias('g')
                        ->join('yoshop_goods_sku gs','g.goods_id=gs.goods_id')
                        ->where('g.goods_id',$wind_id)
                        ->dec('gs.stock_num')
                        ->update();

                    Db::name('goods')
                        ->where('goods_id', $wind_id)
                        ->dec('stock_total')
                        ->update();

                    BlindLog::create([
                        'blind_id'  => $blind['id'],
                        'goods_id'  => $wind_id,
                        'user_id'   => $blindorder['user_id'],
                        'store_id'  => 10001,
                    ]);

                }

            }else{

                $transaction = TransactionModel::get($blindorder['transaction_id']);
                $config = Integrals::field('charges,copyright')->find();
                $price = $transaction['price'] * (100 - ($config['charges'] + $config['copyright'])) / 100;
                $transaction->save(['buyer_id' => $blindorder['user_id'],'buytime' => time(),'status' => 1]); //交易状态修改
                BlindLog::update(['user_id' => $blindorder['user_id'],'status' => 0,'type'=>0],['id' => $transaction['log_id']]); //转移到买方账上
                User::setIncBalance($transaction['user_id'],$price); //增加卖方余额

            }



            $blindorder->save(['status'=>1]);


            $blindOrderModel->commit();
        }catch (Exception $e){

            $blindOrderModel->rollback();

            file_put_contents($_SERVER['DOCUMENT_ROOT'].'/error.txt',serialize($e->getMessage()));
        }
    }

    /**订单***/
    public function notify($type='alipay'){

        $ordersn = '';

        if($type == 'sd'){

            file_put_contents($_SERVER['DOCUMENT_ROOT'].'/sd.txt',serialize(json_decode(stripslashes($_POST['data']),true)));

            $result = json_decode(stripslashes($_POST['data']),true);

            if($result['head']['respCode'] != 000000){

                die('error');

            }

            $ordersn = $result['body']['tradeNo'];


        }else{

            file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logs.txt',serialize($this->request->param()));

            $result = $this->request->param();

            $ordersn = $result['out_trade_no'];

            if($result['trade_status'] != 'TRADE_SUCCESS'){

                die('error');

            }
        }

        if(empty($ordersn)){

            die('error');

        }

        $orderModel = new \app\api\model\Order();

        $order = $orderModel->where('order_no',$ordersn)
            ->where('pay_status',10)
            ->find();

        if(!empty($order)){

            //添加藏品+更新订单
            $orderModel->startTrans();
            try {

                if ($order['type'] == 0){
                    if ($order['is_Box'] == 1) {
                        //调用抽取盲盒接口
                        $orderModel->drawboxs($order['user_id'], $order['order_no'], $order['goods_id']);
                    } else {
                        //调用新增藏品接口
                        $orderModel->addCollec($order['user_id'], $order['goods_id'], $order['order_no'],$order['goods_sum']);
                    }
                }else{
                    $transaction = Transaction::get($order['transaction_id']);
                    $config = Integrals::field('charges,copyright')->find();
                    $price = $transaction['price'] * (100 - ($config['charges'] + $config['copyright'])) / 100;
                    $transaction->save(['buyer_id' => $order['user_id'],'buytime' => time(),'status' => 1]); //交易状态修改
                    Coll::update(['user_id' => $order['user_id'],'status' => 0,'addtime' => time()],['coll_id' => $transaction['coll_id']]); //转移到买房账上
                    User::setIncBalance($transaction['user_id'],$price); //增加卖方余额
                    Db::name('goods_sn')
                        ->where('coll_id',$transaction['coll_id'])
                        ->where('goods_id',$transaction['goods_id'])
                        ->update(['status'=>0]);
                }

                $order->save(['pay_status' => 20,'order_status' => 30,'pay_time' => time()]);

                $goodsModel = new \app\api\model\Goods();

                $goods_info = $goodsModel->where('goods_id',$order['goods_id'])->find();

                $goodsModel->setIncSales($order['goods_id'],$order['goods_sum']);

                if($goods_info['deduct_stock_type'] == 20 && $order['type'] == 0){

                    Db::name('goods_sku')
                        ->where('goods_id', $order['goods_id'])
                        ->dec('stock_num',$order['goods_sum'])
                        ->update();
                    Db::name('goods')
                        ->where('goods_id', $order['goods_id'])
                        ->dec('stock_total',$order['goods_sum'])
                        ->update();

                }

                Db::commit();

                //处理二级分销
                $this->commission($order);

                $orderModel->commit();

            }catch (Exception $e){

                $orderModel->rollback();

                file_put_contents($_SERVER['DOCUMENT_ROOT'].'/error.txt',serialize($e->getMessage()));

            }
        }


    }

    /**
     * 二级分销
     * @param $order
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function commission($order){
        $user = new User();
        if ($order['total_price'] < 1){
            return;
        }
        $config = \app\api\model\Setting::getItem('setting');
        //上级id
        $pid = $user->where('user_id',$order['user_id'])->value('extension_id');
        if (!empty($pid)){
            $price = $order['total_price'] * $config['ratio_one'] / 100;
            $user->setIncBalance($pid,(int)$price); //一级分佣
            //上上级id
            $pid1 = $user->where('user_id',$pid)->value('extension_id');
            if (!empty($pid1)){
                $price1 = $order['total_price'] * $config['ratio_two'] / 100;
                $user->setIncBalance($pid1,(int)$price1); //二级分佣
            }
        }
    }
}
