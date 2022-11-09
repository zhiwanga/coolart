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

namespace app\api\controller;

use app\api\model\recharge\Order as OrderModel;
use app\api\service\Payment as PaymentService;
use app\common\enum\OrderType as OrderTypeEnum;
use app\common\exception\BaseException;
use app\common\library\H5pay as Sd;
use app\common\model\UserIdcar;
use app\common\model\Transaction;
use app\api\model\User as UserModel;
use think\facade\Db;
use app\api\service\User as UserService;
use app\common\model\user\BalanceLog;
use app\common\enum\user\balanceLog\Scene as SceneEnum;

/**
 * 用户充值管理
 * Class Recharge
 * @package app\api\controller
 */
class Recharge extends Controller
{
    /**
     * 确认充值
     * @param int|null $planId 方案ID
     * @param float|string|null $customMoney 自定义金额
     * @return array|\think\response\Json
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function submit(int $planId = null, $customMoney = null,$pay_type='alipay')
    {

        $user = \app\api\service\User::getCurrentLoginUser();

        /*if (getPlatform() !== 'MP-WEIXIN') {
            return $this->renderError('很抱歉，余额充值暂时仅支持微信小程序端');
        }*/
        // 生成充值订单
        $model = new OrderModel();
        if (!$model->createOrder($planId, (float)$customMoney)) {
            return $this->renderError($model->getError() ?: '充值失败');
        }
        // 构建微信支付
        /*$payment = PaymentService::wechat(
            $model['order_id'],
            $model['order_no'],
            $model['pay_price'],
            OrderTypeEnum::RECHARGE
        );*/

        $order_no = $model->where('order_id',$model['order_id'])->value('order_no');

//        if($pay_type == 'dlb'){

            $alipay = new \app\common\library\Pay();

            $result = $alipay->createDLBPay([
                'ordersn'       => $order_no,
                'price'         => $model['pay_price'],
                'notify_url'    => 'api/callback/rechargenotify/'
            ]);

            if($result['code'] != 200){

                return $this->renderError('请求失败');

            }else{


            //   $payOrder = $model->where('order_id',$model['order_id'])->find();
            //   $transaction = Transaction::get($this->$model['transaction_id']);
            //   OrderModel::update(['order_id',$model['order_id'],'pay_status' => 20,'pay_time' => time(),'update_time' =>time()],['order_id' => $transaction['order_id']]); //转移到买房账上

            //   $user_id = UserService::getCurrentLoginUserId();

            //   $currentUser = Db::name('user')->field('balance')->where('user_id', $user_id)->find();
            //   $balance = $currentUser['balance']+$model['pay_price'];
       
            //   Db::name('user')
            //   ->where('user_id',$user['user_id'])
            //   ->update(['balance'=>$balance]);

            //       // 新增余额变动记录
            //       BalanceLog::add(SceneEnum::CONSUME, [
            //         'user_id' => (int)$user['user_id'],
            //         'money' => -$model['pay_price'],
            //     ], ['order_no' =>  $payOrder['order_no']]);

                return $this->renderSuccess(['url'=>$result['pay_url']],'成功');

            }



//        }else{
//
//            return $this->renderError('未找到支付方式');
//
//        }

        // 充值状态提醒
        $message = ['success' => '充值成功', 'error' => '订单未支付'];
        return $this->renderSuccess(compact('payment', 'message'));
    }
}
