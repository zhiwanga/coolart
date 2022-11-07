<?php


namespace app\api\controller;


use app\common\model\BlindLog;
use app\common\model\BlindOrder;
use app\common\model\Goods;
use think\Model;

class BlindPay extends Controller
{

    /**盲盒支付**/
    public function pay($blind_id,$total=1,$pay_type='balance',$order_id=0){

        $user = \app\api\service\User::getCurrentLoginUser();

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
        $res = (new BlindOrder())->getPay($blind_info,$pay_type,$total,$user,$order_id);

        if($res['code'] != 200){

            return $this->renderError($res['message']);

        }else{

            return $this->renderSuccess(['url'=>$res['data']['res_url'],'pid'=>$res['data']['pid']],'支付成功');

        }

    }

    /**
     * 取消订单
     */
    public function qxorder(){

        $order_id = $this->request->param('order_id','');

        $order_info = BlindOrder::where('id',$order_id)->find();

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