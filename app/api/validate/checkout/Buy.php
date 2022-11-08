<?php
declare (strict_types = 1);

namespace app\api\validate\checkout;

use app\api\model\Coll;
use app\api\model\UserFirst;
use app\common\model\Goods;
use app\common\model\Order;
use think\cache\driver\Redis;
use think\Validate;

class Buy extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'goodsId'   => ['require','checkout'],
        'total'     => ['require','between:1,1'],
        'pay_type'  => ['require']
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [
        'goodsId.require'   => '参数错误',
        'total'             => '每单限购一个',
        'pay_type'          => '请选择支付方式'
    ];

    public function checkout($value,$rule,$data){

        $goods_info = Goods::where('goods_id',$value)->find();

        if($data['user_info']['idcar_id'] == 0){

            return '请先完成实名认证';

        }

        //商品状态
        if($goods_info['status'] != 10){

            return '产品已下架';

        }

        //判断库存
        if($goods_info['stock_total'] < $data['total']){

            return '该藏品库存不足';

        }

        if($data['pay_type'] == 'balance'){

            $amount = $goods_info['goods_price_min'] * $data['total'];

            if($data['user_info']['balance'] < $amount){
                return '余额不足，请尽快充值';
            }

        }

        if(empty($data['order_sn'])){

            //是否有未付款的订单
            $order_total= Order::where('user_id',$data['user_info']['user_id'])
                ->where('pay_status','10')
                ->where('is_delete','0')
                ->count();

            if($order_total > 0){

                return '有未付款的藏品订单'.$order_total.'个';

            }
        }



        $redis = new Redis();
        //队列判断库存
        $name = $value . '_list';
        $len = $redis->lLen($name);

        if ($len < $data['total']) {

            return '该藏品已售罄';

        }

        //判断购买时间
        if(!empty($goods_info['endtime']) && strtotime($goods_info['endtime']) <= time()){

            return '该藏品购买时间已结束';

        }

        $user_id = $data['user_info']['user_id'];

        if($goods_info['hot_type'] == 1){

            //获取时间段是否购买了产品
            $hot_start = explode(':',$goods_info['hot_starttime']);

            $hot_end = explode(':',$goods_info['hot_endtime']);

            $start = mktime((int)$hot_start[0],(int)$hot_start[1],00,(int)date('m'),(int)date('d'),(int)date('Y'));

            $end   = mktime((int)$hot_end[0],(int)$hot_end[1],00,(int)date('m'),(int)date('d'),(int)date('Y'));

            $coll_total= Coll::where('user_id',$user_id)
                ->where('goods_id','in',json_decode($goods_info['hot_ids'],true))
                ->whereBetweenTime('addtime',$start,$end)
                ->count();

            if(empty($coll_total)){

                return "无法购买,您在".$goods_info['hot_starttime']."~".$goods_info['hot_endtime']."未购买过指定藏品";

            }

        }

        if($goods_info['get_limit'] > 0){

            $my_coll = Order::where('user_id',$user_id)
                    ->where('is_delete',0)
                    ->where('goods_id',$goods_info['goods_id'])
                    ->sum('goods_sum')+$data['total'];

            /**优先购份数**/
           /* $first_num = 0;

            if(!empty($goods_info['first_goods_num']) && time() < strtotime($goods_info['startTime'])){

                foreach($goods_info['first_goods_num'] as $f=>$n){

                    //获取指定藏品总数
                    $coll_num = Coll::where('user_id',$user_id)
                            ->where('goods_id',$f)
                            ->where('is_give',0)
                            ->count()*$n;

                    $first_num += $coll_num;

                }

                if($my_coll > $first_num){

                    return '可优先购'.$first_num.'次';

                }

            }else{*/

                if($my_coll > $goods_info['get_limit']){

                   return '该商品限购'.$goods_info['get_limit'].'次';

                }
            //}

        }

        for($i=1;$i<=$data['total'];$i++){

            $redis->rpop($name); //出列

        }

        return true;
    }
}
