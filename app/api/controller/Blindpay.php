<?php


namespace app\api\controller;


use app\common\model\BlindLog;
use app\common\model\BlindOrder;
use app\common\model\Goods;
use app\common\model\UserIdcar;
use think\cache\driver\Redis;
use think\Model;

class Blindpay extends Controller
{

    /**盲盒支付**/
    public function pay($blind_id,$total=1,$pay_type='balance',$order_id=0){

        $user = \app\api\service\User::getCurrentLoginUser();

        $user_id = $user['user_id'];

        if($user['idcar_id'] == 0){

            return $this->renderError('您未完成实名认证,请先实名');

        }else{

            $user_car = UserIdcar::where('idcar_id',$user['idcar_id'])->find();

        }

        // 购买数量多会慢，避免重复点击造成造成损失和余额复数
        $redis = new Redis();
        if(!$redis->get('lock_'.$blind_id.$user_id)){

            $redis->set('lock_'.$blind_id.$user_id, 1, 5);
        }else{
            return $this->renderError('请勿重复点击');
        }

        $blind_info = \app\common\model\Blind::where('id',$blind_id)->where('is_delete',0)->find();

        if(empty($blind_info) || $blind_info['total']< $total){

            return $this->renderError('盲盒不存在或该盲盒已售罄');

        }

        if(strtotime($blind_info['starttime']) > time()){

            return $this->renderError('未到开售时间');

        }

        if(strtotime($blind_info['endtime']) < time()){

            return $this->renderError('购买时间已结束');

        }

        //购买盲盒
        $res = (new BlindOrder())->getPay($blind_info,$pay_type,$total,$user,$order_id,$user_car);

        if($res['code'] != 200){
            return $this->renderError($res['message']);
        }else{
            return $this->renderSuccess(['url'=>$res['data']],'支付成功');
        }
    }

    /**
     * 取消订单
     */
    public function qxorder(){

        $user = \app\api\service\User::getCurrentLoginUser();

        $order_id = $this->request->param('order_id','');

        $order_info = BlindOrder::where('id',$order_id)->where('user_id',$user['user_id'])->find();

        if(empty($order_info) || $order_info['is_delete'] == 1){

            return $this->renderError('订单不存在或该订单已关闭');

        }

        $res = $order_info->save([
            'is_delete' => 1,
        ]);

        if($res){

            return $this->renderSuccess([],'关闭成功');

        }else{

            return $this->renderError('关闭出错');

        }
    }
}