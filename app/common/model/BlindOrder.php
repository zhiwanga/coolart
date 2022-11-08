<?php


namespace app\common\model;


use app\common\enum\user\balanceLog\Scene as SceneEnum;
use app\common\library\H5pay as Sd;
use app\common\model\BlindTransaction as TransactionModel;
use app\common\model\user\BalanceLog;
use cores\BaseModel;
use think\Exception;
use think\facade\Db;

class BlindOrder extends BaseModel
{

    public $name = 'blind_order';

    protected $pk = 'id';

    public function getPay($blind,$payType,$total,$user,$order_id,$user_car){

        $my_blind = self::where('user_id',$user['user_id'])
                ->where('blind_id',$blind['id'])
                ->where('status',1)
                ->sum('total')+$total;

        if($my_blind > $blind['get_limit']){

            return ['code'=>-1,'message'=>'该盲盒限购'.$blind['get_limit'].'次'];

        }

        $ordersn = 'BLD'.date('YmdHis').time();

        if(!empty($order_id)){

            $res = self::where('id',$order_id)->find();

            if($res['status'] != 0){

                return ['code'=>-1,'message'=>'订单已支付'];

            }

            //更新订单编号
            self::where('id',$order_id)->update(['ordersn'=>$ordersn]);
            
            $pay_price =  $res['price'];

        }else{

            $pay_price = $blind['price'] * $total;

            $res = self::create([
                'blind_id'      => $blind['id'],
                'user_id'       => $user['user_id'],
                'price'         => $pay_price,
                'ordersn'       => $ordersn,
                'store_id'      => 10001,
                'total'         => $total,
                'status'        => 0,
                'type'          => 0,
            ]);

            if(!$res){

                return ['code'=>-1,'message'=>'创建订单失败'];

            }

        }

        self::startTrans();
        try {

            if ($payType == 'balance') {

                if ($user['balance'] < $pay_price) {

                    throw new Exception('用户余额不足，无法使用余额支付');

                }

            }else if ($payType == 'sd'){

                $sd = new Sd();

                $pid = $sd->pay([
                    'user_id'       => $user['user_id'],
                    'username'      => $user_car['idcar_name'],
                    'idCard'        => $user_car['idcar'],
                    'ordersn'       => $ordersn,
                    'price'         => $pay_price,
                    'goods_name'    => '购买盲盒',
                    'notify_url'    => 'api/callback/blind_notify/type/sd',
                ]);

            } else{

                throw new Exception('未找到支付方式');

            }

            if($payType == 'balance'){

                if($res['type'] == 0){

                    //将随机选中的盲盒数量减少一个
                    for($i=1;$i<=$total;$i++){

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
                            'user_id'   => $user['user_id'],
                            'store_id'  => 10001,
                        ]);

                    }

                    $dec_g = Db::name('blind')->where('id',$blind['id'])->dec('total',$total)->update();

                    if(!$dec_g){

                        throw new Exception('扣除库存失败');

                    }

                    Db::name('blind')->where('id',$blind['id'])->inc('sales',$total)->update();

                }else{

                    $transaction = TransactionModel::get($res['transaction_id']);
                    $config = Integrals::field('charges,copyright')->find();
                    $price = $transaction['price'] * (100 - ($config['charges'] + $config['copyright'])) / 100;
                    $transaction->save(['buyer_id' => $user['user_id'],'buytime' => time(),'status' => 1]); //交易状态修改
                    BlindLog::update(['user_id' => $user['user_id'],'status' => 0,'type'=>0],['id' => $transaction['log_id']]); //转移到买方账上
                    User::setIncBalance($transaction['user_id'],$price); //增加卖方余额

                }

                // 累积用户总消费金额
                User::setIncPayMoney($user['user_id'], (float)$pay_price);

                // 更新用户余额
                User::setDecBalance((int)$user['user_id'], (float)$pay_price);
                // 新增余额变动记录
                BalanceLog::add(SceneEnum::CONSUME, [
                    'user_id' => (int)$user['user_id'],
                    'money' => -$pay_price,
                ], ['order_no' => $ordersn]);

                self::where('id',$res['id'])->save(['status'=>1]);

            }

            self::commit();

            return ['code'=>200,'message'=>'ok','data'=>isset($pid) && !empty($pid)?$pid:0];

        }catch (Exception $e){

            self::rollback();
            return ['code'=>-1,'message'=>$e->getMessage()];
        }


    }

    public function file(){

        return $this->belongsTo('app\common\model\UploadFile','thumb','file_id');
    }

    public function blind(){

        return $this->belongsTo('app\common\model\Blind','blind_id','id');
    }

}