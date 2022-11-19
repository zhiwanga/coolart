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

use app\api\model\Coll;
use app\api\model\Order as OrderModel;
use app\api\model\UserBank;
use app\api\service\CheckBuy;
use app\api\service\order\PaySuccess;

use app\api\service\passport\Login as LoginService;
use app\api\service\Payment;
use app\api\service\User as UserService;
use app\api\service\Cart as CartService;
use app\api\service\order\Checkout as CheckoutService;
use app\api\validate\order\Checkout as CheckoutValidate;
use app\common\enum\order\PayType as OrderPayTypeEnum;
use app\common\library\H5pay as Sd;
use app\common\model\BlindLog;
use app\common\model\Goods;
use app\common\model\Integrals;
use app\common\model\Test;
use app\common\model\UserIdcar;
use cores\exception\BaseException;

use think\cache\driver\Redis;
use think\Exception;
use think\response\Json;
use app\common\model\order;
use think\validate\ValidateRule;

use think\facade\Db;
use Yansongda\Pay\Log;
use app\api\model\Setting;
use app\controller\Rsa;
use app\model\market\RealtGoods;

/**
 * 订单结算控制器
 * Class Checkout
 * @package app\api\controller
 */
class Checkout extends Controller
{
    // 结算台验证器
    /* @var CheckoutValidate $validate */
    private $validate;

    /**
     * 结算台订单信息
     * @param string $mode
     * @return Json
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function order(string $mode = 'buyNow'): Json
    {
        if ($mode === 'buyNow') {
            return $this->buyNow();
        } elseif ($mode === 'cart') {
            return $this->cart();
        }
        return $this->renderError('结算模式不合法');
    }

    /**
     * 订单提交
     * @param string $mode
     * @return Json
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function submit(string $mode = 'buyNow'): Json
    {
        return $this->order($mode);
    }

    /**
     * 订单确认-立即购买
     * @return Json
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function buyNow(): Json
    {

        // 实例化结算台服务
//        $a = new CheckoutService();
        $Checkout = new CheckoutService();
//        var_dump(11);exit;
//        $Checkout->getParam();

        // 订单结算api参数
        $params = $Checkout->setParam($this->getParam([
            'goodsId' => 0,
            'goodsSkuId' => '',
            'goodsNum' => 0,
        ]));
//        var_dump(11);exit;

        // 表单验证
        if (!$this->getValidate()->scene('buyNow')->check($params)) {
            return $this->renderError($this->getValidate()->getError(), ['isCreated' => false]);
        }


        // 立即购买：获取订单商品列表
        $model = new OrderModel;

        $goodsList = $model->getOrderGoodsListByNow(
            (int)$params['goodsId'],
            (string)$params['goodsSkuId'],
            (int)$params['goodsNum']
        );
//var_dump(123);die;


        // 获取订单确认信息
        $orderInfo = $Checkout->onCheckout($goodsList);


        if ($this->request->isGet()) {
            return $this->renderSuccess([
                'order' => $orderInfo,
                'personal' => $Checkout->getPersonal(),
                'setting' => $Checkout->getSetting(),
            ]);
        }

        // 验证订单是否存在错误
        if ($Checkout->hasError()) {
            return $this->renderError($Checkout->getError(), ['is_created' => false]);
        }
//        var_dump($Checkout->createOrder($orderInfo));die;
        // 创建订单
        if (!$Checkout->createOrder($orderInfo)) {
            return $this->renderError($Checkout->getError() ?: '订单创建失败', ['is_created' => false]);
        }


        // 构建微信支付请求
        $payment = $model->onOrderPayment($Checkout->model, $params['payType']);

        // 返回结算信息
        return $this->renderSuccess([
            'orderId' => $Checkout->model['order_id'],   // 订单id
            'payType' => $params['payType'],            // 支付方式
            'payment' => $payment                         // 微信支付参数
        ]);
    }

    /**
     * 订单确认-购物车结算
     * @return Json
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function cart(): Json
    {
        // 实例化结算台服务
        $Checkout = new CheckoutService;
        // 订单结算api参数
        $params = $Checkout->setParam($this->getParam());
        // 购物车ID集
        $cartIds = $this->getCartIds();
        // 商品结算信息
        $CartModel = new CartService;
        // 购物车商品列表
        $goodsList = $CartModel->getOrderGoodsList($cartIds);
        // 获取订单结算信息
        $orderInfo = $Checkout->onCheckout($goodsList);
        if ($this->request->isGet()) {
            return $this->renderSuccess([
                'order' => $orderInfo,
                'personal' => $Checkout->getPersonal(),
                'setting' => $Checkout->getSetting(),
            ]);
        }
        // 验证订单是否存在错误
        if ($Checkout->hasError()) {
            return $this->renderError($Checkout->getError(), ['is_created' => false]);
        }
        // 创建订单
        if (!$Checkout->createOrder($orderInfo)) {
            return $this->renderError($Checkout->getError() ?: '订单创建失败');
        }
        // 移出购物车中已下单的商品
        $CartModel->clear($cartIds);
        // 构建微信支付请求
        $payment = $Checkout->onOrderPayment();
        // 返回状态
        return $this->renderSuccess([
            'orderId' => $Checkout->model['order_id'],   // 订单id
            'payType' => $params['payType'],  // 支付方式
            'payment' => $payment               // 微信支付参数
        ]);
    }

    /**
     * 获取结算台验证器
     * @return CheckoutValidate
     */
    private function getValidate(): CheckoutValidate
    {
        if (!$this->validate) {
            $this->validate = new CheckoutValidate;
        }
        return $this->validate;
    }

    /**
     * 获取购物车ID集
     * @return false|string[]
     */
    private function getCartIds()
    {
        $cartIds = $this->request->param('cartIds');
        return explode(',', $cartIds);
    }

    /**
     * 订单结算提交的参数
     * @param array $define
     * @return array
     */
    private function getParam(array $define = []): array
    {
        return array_merge($define, $this->request->param());
    }


    /**
     * 订单类，用于生成立即购买的订单，并调用支付宝接口
     * userid 用户ID
     * goodsid 商品ID
     * head Access-Token 用户TOKEN
     * head storeId 商户ID 默认10001
     * head platform 平台 默认H5
     */
    public function orders()
    {
        return $this->renderError('暂未开放！');
        //创建订单，进行处理!!!!
        $Checkout = new CheckoutService;
        //获取所有POST内容
        $posta = $this->postData();
        //生成订单编号
        $order = new Order();

        //判断是否是待支付订单调起支付
        if (isset($posta['order_no'])) {
            $orderNo = $posta['order_no'];
            //查询这个订单的信息
            $totalamount = $Checkout->getOrder($orderNo);
            $order = [
                'out_trade_no' => $orderNo,//订单编号
                'total_amount' => $totalamount,//价格
                'subject' => '藏品购买',//默认订单名
            ];
            //支付宝接口
            //输出支付的HTML代码给前端调用
            return $ali = getAliConfig()->wap($order)->send();
        }

        $orderNo = $order->orderNo();
        //获取用户ID
        $userid = (int)$posta['userid'];
        //获取商品ID  盲盒0
        $goodsid = (int)$posta['goodsid'];
        //藏品类型 0藏品 1盲盒
        $isBox = $posta['isBox'];
        //判断是微信支付还是支付宝支付
        if (!isset($posta['pay_type'])) {
            $payType = 30;
        } else {
            $payType = $posta['pay_type'];
        }

        //判断购买的是商品还是盲盒
        if ($isBox == 0) {
            //购买藏品
            $boxMoneys = 0.00;
            //调用订单创建接口 查询这个商品还有没有东西
            $arrList = $Checkout->addorder($userid, $goodsid, $orderNo, $isBox, $boxMoneys, $payType);
            //获取订单总价格
            $totalamount = $arrList['total_price'];
        } else {
            //购买盲盒
            //查询盲盒价格
            $boxMoney = $Checkout->boxMoneys();
            $boxMoneys = $boxMoney['box_money'];

            //调用盲盒订单接口
            $arrList = $Checkout->addorder($userid, $goodsid, $orderNo, $isBox, $boxMoneys, $payType);
            $totalamount = $boxMoneys;
        }
        //如果返回值为110 标识库存为0 不可购买
        if ($arrList == 110) {
            return $this->renderError('该商品已被抢购一空！');
        }
        //获取订单赠送积分
//        $pointsbonus=$arrList['points_bonus'];

        $order = [
            'out_trade_no' => $orderNo,//订单编号
            'total_amount' => $totalamount,//价格
            'subject' => '藏品购买',//默认订单名
        ];

        //支付宝接口
        //输出支付的HTML代码给前端调用
        return $ali = getAliConfig()->wap($order)->send();
    }

    /**
     * 支付宝回调
     */
    public function returnnotify()
    {
        $alipay = getAliConfig();

        $Checkout = new OrderModel();


        try {
            $data = $alipay->verify(); // 是的，验签就这么简单！
            // 请自行对 trade_status 进行判断及其它逻辑进行判断，在支付宝的业务通知中，只有交易通知状态为 TRADE_SUCCESS 或 TRADE_FINISHED 时，支付宝才会认定为买家付款成功。
            // 1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号；
            // 2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额）；
            // 3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）；
            // 4、验证app_id是否为该商户本身。
            // 5、其它业务逻辑情况
            if ($data['trade_status'] == 'TRADE_SUCCESS') {
                //成功然后做支付成功逻辑

                // $a=Db::name('aboutus')->where('id','=',1)->update(['text_us'=>$orderNo]);
                //获取订单编号
                $orderNo = $data['out_trade_no'];

                //根据订单编号查询是盲盒还是商品
                $orderTypeArr = $Checkout->checkOrderType($orderNo);

                //将订单改为已支付
                $orderup = $Checkout->orderup($orderNo);

                if ($orderup) {
                    //购买后添加的积分  total_amount订单价格四舍五入
                    $pointsbonus = round($data['total_amount']);

                    //获取当前订单的用户ID和商品ID
                    $lists = $Checkout->chaUserId($orderNo);

                    $userid = $lists[0]['user_id'];
                    $goodsid = $lists[0]['goods_id'];

                    //给用户添加积分,每次积分变动时统计一下剩余积分
                    $points = $Checkout->pointss($userid, $pointsbonus);
                    //判断积分是否已经增加
                    if ($points) {
                        if ($orderTypeArr[0]['is_Box'] == 1) {
                            //购买的是盲盒
                            //调用抽取盲盒接口
                            $collec = $Checkout->drawboxs($userid, $orderNo, $goodsid);

                        } else {
                            //购买的是商品

                            //调用新增藏品接口
                            $collec = $Checkout->addCollec($userid, $goodsid, $orderNo);
                        }
                        //如果已经新增藏品则将状态改成已完成
                        if ($collec) {
                            //调用改变状态接口
                            $collec = $Checkout->orderType($orderNo);
                            if (!$collec) {
                                return $this->renderError('失败！请联系管理员');
                            }
                        }
                    }
                }

            }
        } catch (\Exception $e) {
            $e->getMessage();
        }
        return $alipay->success()->send();// laravel 框架中请直接 `return $alipay->success()
    }


    /**
     * 订单类，用于生成立即购买的订单，并调用微信接口
     * userid 用户ID
     * goodsid 商品ID
     * head Access-Token 用户TOKEN
     * head storeId 商户ID 默认10001
     * head platform 平台 默认H5
     */
    public function ordersWe()
    {
        //购买盲盒个数
        if (!isset($posta['num'])) {
            $num = 1;
        } else {
            $num = $posta['num'];
        }

        $userId = UserService::getCurrentLoginUserId();
        //创建订单，进行处理!!!!
        $Checkout = new CheckoutService;
        //获取所有POST内容
        $posta = $this->postData();

        //判断是否是待支付订单调起支付
        if (isset($posta['order_no'])) {
            $orderNo = $posta['order_no'];
            //查询这个订单的信息
            $jiage = $Checkout->getOrder($orderNo);
            if (!empty($jiage)) {
                $url = url('/api/huishouqian/pay', [], true, true) . '?order_id=' . $orderNo . '&type=' . ($jiage['pay_type'] == 20 ? 'wechat' : 'alipay');
                return $url;
            }
        }
        //生成订单编号
        $order = new Order();
        $orderNo = $order->orderNo();
        //获取商品ID  盲盒0
        $goodsid = (int)$posta['goodsid'];
        if (!isset($posta['pay_type'])) {
            $payType = 20;
        } else {
            $payType = $posta['pay_type'];
        }
        //藏品类型 0藏品 1盲盒
        $isBox = $posta['isBox'];
        //判断购买的是商品还是盲盒
        if ($isBox == 0) {
            //购买藏品
            $boxMoneys = 0.00;
            //调用订单创建接口 查询这个商品还有没有东西
            $arrList = $Checkout->addorder($userId, $goodsid, $orderNo, $isBox, $boxMoneys, $payType);
            //获取订单总价格
            $totalamount = $arrList['total_price'];
        } else {
            //购买盲盒
            //查询盲盒价格
            $boxMoney = $Checkout->boxMoneys();
            $boxMoneys = $boxMoney['box_money'];
            //调用盲盒订单接口
            $arrList = $Checkout->addorder($userId, $goodsid, $orderNo, $isBox, $boxMoneys, $payType);
            $totalamount = $boxMoneys;
        }
        //如果返回值为110 标识库存为0 不可购买
        if ($arrList == 110) {
            return $this->renderError('该商品已被抢购一空！');
        }
        //获取订单赠送积分
        $url = url('/api/huishouqian/pay', [], true, true) . '?order_id=' . $orderNo . '&type=' . ($payType == 20 ? 'wechat' : 'alipay');
        return $url;
    }

    /**
     * 微信回调
     */
    public function returnnotifywe()
    {
        $alipay = getWeAliConfid();

        $Checkout = new OrderModel();
        try {
            $data = $alipay->verify(); // 是的，验签就这么简单！
            // 请自行对 trade_status 进行判断及其它逻辑进行判断，在支付宝的业务通知中，只有交易通知状态为 TRADE_SUCCESS 或 TRADE_FINISHED 时，支付宝才会认定为买家付款成功。
            // 1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号；
            // 2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额）；
            // 3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）；
            // 4、验证app_id是否为该商户本身。
            // 5、其它业务逻辑情况
            if ($data['trade_status'] == 'TRADE_SUCCESS') {
                //成功然后做支付成功逻辑
                // $a=Db::name('aboutus')->where('id','=',1)->update(['text_us'=>$orderNo]);
                //获取订单编号
                $orderNo = $data['out_trade_no'];

                //根据订单编号查询是盲盒还是商品
                $orderTypeArr = $Checkout->checkOrderType($orderNo);

                //将订单改为已支付
                $orderup = $Checkout->orderup($orderNo);

                if ($orderup) {
                    //购买后添加的积分  total_amount订单价格四舍五入
                    $pointsbonus = round($data['total_amount']);

                    //获取当前订单的用户ID和商品ID
                    $lists = $Checkout->chaUserId($orderNo);

                    $userid = $lists[0]['user_id'];
                    $goodsid = $lists[0]['goods_id'];

                    //给用户添加积分,每次积分变动时统计一下剩余积分
                    $points = $Checkout->pointss($userid, $pointsbonus);
                    //判断积分是否已经增加
                    if ($points) {

                        if ($orderTypeArr[0]['is_Box'] == 1) {
                            //购买的是盲盒
                            //调用抽取盲盒接口
                            $collec = $Checkout->drawboxs($userid, $orderNo, $goodsid);

                        } else {
                            //购买的是商品

                            //调用新增藏品接口
                            $collec = $Checkout->addCollec($userid, $goodsid, $orderNo);
                        }
                        //如果已经新增藏品则将状态改成已完成
                        if ($collec) {
                            //调用改变状态接口
                            $collec = $Checkout->orderType($orderNo);
                            if (!$collec) {
                                return $this->renderError('失败！请联系管理员');
                            }
                        }
                    }
                }

            }
        } catch (\Exception $e) {
            $e->getMessage();
        }
        return $alipay->success()->send();// laravel 框架中请直接 `return $alipay->success()
    }

    /**
     * 购买
     * @param int $transactionId
     * @return Json
     * @throws BaseException
     */
    public function new_buy(){

        $check_method = (new CheckBuy())->check_info();

        if($check_method !== true){

            return $this->renderError($check_method);

        }

        $user = UserService::getCurrentLoginUser();

        $goodsId = $this->request->param('goodsId','');

        $total = $this->request->param('total',1);

        $pay_type = $this->request->param('pay_type','');

        $order_sn = $this->request->param('order_no','');
        $cipcont = $this->request->param('cipcont','');

        // rsa密钥检测
        if(isset($cipcont) && $cipcont) {
            $res = Rsa::rsaContCheck(6, $cipcont, $user['user_id']);
            if(!$res) return $this->renderError('密码错误');
        }else{
            return $this->renderError('缺少传参');
        }

        $result = $this->validate([
            'goodsId'   => $goodsId,
            'user_info' => $user->toArray(),
            'total'     => $total,
            'pay_type'  => $pay_type,
            'order_sn'  => $order_sn
        ],'app\api\validate\checkout\Buy');

        if($result !== true){

            return $this->renderError($result);

        }

        // 查看是否到达优先购时间
        $goods_time = Db::name('goods')->field('first_time, startTime')->where('goods_id', $goodsId)->find();
        if($goods_time['first_time'] != 0) {
            $time = time();
            $diff_time = strtotime($goods_time['startTime']) - ($goods_time['first_time'] * 60 * 60);
            if($time < $diff_time) {
                return $this->renderError('购买时间未达到！');
            }
        }

        //事务处理
        Db::startTrans();
        try {

            //创建订单，进行处理!!!!
            $Checkout = new CheckoutService;

            $orderModel = new Order();

            $orderNo = 'JD'.$orderModel->orderNo();

            if(!empty($order_sn)){

                $res = Order::where('order_no', $order_sn)->find();

                if(empty($res) || $res['pay_status'] == 20 || $res['is_delete'] == 1){

                    throw new Exception('商品不存在或已支付');

                }

                $res->save(['order_no' => $orderNo]);

            }else{

                //调用订单创建接口 查询这个商品还有没有东西
                $res = $Checkout->addorder($user['user_id'], $goodsId, $orderNo,20,$total);

                if(!$res){

                    throw new Exception('产品已被一扫而空!');
                }
            }


            if($pay_type == 'balance'){

                // 发起余额支付
                $payModel = new PaySuccess($res['order_no']);

                $payModel->updateOrderInfo(10,[],0);
                // 累积用户总消费金额
                \app\common\model\User::setIncPayMoney($user['user_id'], (float)$res['pay_price']);
                // 记录订单支付信息
                $payModel->updatePayInfo(10);

                // 对接第三方市场，实时更新藏品价格 2022/11/07
                RealtGoods::add($goodsId, $res['order_no'], $res['pay_price']);
                // 更新订单状态为已支付
                Db::name('order')->where('order_no', $res['order_no'])->update(['order_status' => 30]);
                Db::commit();

                return $this->renderSuccess('购买成功');

            }else{
                throw new Exception('请求失败');
                $alipay = new \app\common\library\Pay();

                $result = $alipay->createDLBPay([
                    'ordersn'       => $res['order_no'],
                    'price'         => $res['pay_price'],
                ]);

                if($result['code'] != 200){

                    throw new Exception('请求失败');

                }else{
                    Db::commit();
                    return $this->renderSuccess(['url'=>$result['pay_url']],'成功');

                }

            }

        }catch (Exception $e){

            Db::rollback();
            return $this->renderError('购买失败');

        }

    }
//    public function buy(int $goodsId, int $isBox, $order_no = 0,$blind_id=0,$total=1,$first_types=0)
//    {
//
//        $user = UserService::getCurrentLoginUser();
//
//        $user_id = $user['user_id'];
//
//        if($user['idcar_id'] == 0){
//
//            return $this->renderError('您未完成实名认证,请先实名');
//
//        }else{
//
//            $user_car = UserIdcar::where('idcar_id',$user['idcar_id'])->find();
//
//        }
//
//        $redis = new Redis();
//        if(!$redis->get('lock_'.$goodsId.$user_id)){
//
//            $redis->set('lock_'.$goodsId.$user_id,1,2);
//
//        }else{
//
//            return $this->renderError('请勿重复点击');
//        }
//
//
//        //创建订单，进行处理!!!!
//        $Checkout = new CheckoutService;
//        $orderModel = new Order();
//        $orderNo = $orderModel->orderNo();
//        $pay_type = input('pay_type');
//
//        //判断是否是待支付订单调起支付
//        if (!empty($order_no)) {
//
//            $order = Order::where('order_no', $order_no)->find();
//            if($order['type'] == 0){
//
//                //队列
//                $name = $order['goods_id'] . '_list';
//                $len = $redis->lLen($name);  //获取队列长度
//                if ($len < $total) {
//                    return $this->renderError('该藏品已售罄');
//                }
//
//            }
//            if ($order['pay_status'] == 10 && $order['order_status'] == 10) {
//                $order_no = 'JD' . $orderNo;
//                $order->save(['order_no' => $order_no]);
//                if($pay_type == 'balance' || $pay_type == 'jdpay'){
//
//                    // 获取订单详情
//                    $model = OrderModel::getUserOrderDetail($order['order_id']);
//
//                    // 余额支付
//                    if(!(new OrderModel()) ->onPaymentByBalance($order['order_no'])){
//
//                        return $this->renderError('余额不足');
//
//                    }else{
//
//                        return $this->renderSuccess('购买成功');
//                    }
//
//
//                }else if($pay_type == 'sd'){
//
//                    $sd = new Sd();
//
//                    $result = $sd->pay([
//                        'user_id'       => $user_id,
//                        'username'      => $user_car['idcar_name'],
//                        'idCard'        => $user_car['idcar'],
//                        'ordersn'       => $order_no,
//                        'price'         => $order['pay_price'],
//                        'goods_name'    => '购买商品',
//                        'notify_url'    => 'api/callback/notify/type/sd',
//                    ]);
//
//                    return $this->renderSuccess(['url'=>$result],'成功');
//
//                }
//            } else {
//                return $this->renderError('订单状态异常，请刷新重试！');
//            }
//
//        } else {
//
//            if($isBox != 0){
//
//                return $this->renderError('请求错误');
//
//            }
//
//            $goods_info = Goods::where('goods_id',$goodsId)->find();
//
//            if($goods_info['stock_total'] < $total){
//
//                return $this->renderError('该藏品库存不足');
//
//            }
//
//            //获取商品ID  盲盒0
//            $data = jdConfig();
//            $data['requestNum'] = 'JD' . $orderNo;
//
//            if($goods_info['hot_type'] == 1){
//
//                //获取时间段是否购买了产品
//                $hot_start = explode(':',$goods_info['hot_starttime']);
//
//                $hot_end = explode(':',$goods_info['hot_endtime']);
//
//                $start = mktime((int)$hot_start[0],(int)$hot_start[1],00,(int)date('m'),(int)date('d'),(int)date('Y'));
//
//                $end   = mktime((int)$hot_end[0],(int)$hot_end[1],00,(int)date('m'),(int)date('d'),(int)date('Y'));
//
//                $coll_total= Coll::where('user_id',$user_id)
//                    ->where('goods_id','in',json_decode($goods_info['hot_ids'],true))
//                    ->whereBetweenTime('addtime',$start,$end)
//                    ->count();
//
//                if(empty($coll_total)){
//
//                    return $this->renderError("无法购买,您在".$goods_info['hot_starttime']."~".$goods_info['hot_endtime']."未购买过指定藏品");
//                }
//
//            }
//
//            if($goods_info['get_limit'] > 0){
//
//                $my_coll = OrderModel::where('user_id',$user_id)
//                        ->where('is_delete',0)
//                        ->where('goods_id',$goods_info['goods_id'])
//                        ->sum('goods_sum')+$total;
//
//                /**优先购份数**/
//                $first_num = 0;
//
//               /* if(!empty($goods_info['first_goods_num']) && time() < strtotime($goods_info['startTime'])){
//
//                    foreach($goods_info['first_goods_num'] as $f=>$n){
//
//                        //获取指定藏品总数
//                        $coll_num = Coll::where('user_id',$user_id)
//                            ->where('goods_id',$f)
//                            ->where('is_give',0)
//                            ->count()*$n;
//
//                        $first_num += $coll_num;
//
//                    }
//
//                    if($my_coll > $first_num){
//
//                        return $this->renderError('可优先购'.$first_num.'次');
//
//                    }
//
//                }else{*/
//
//                    if($my_coll > $goods_info['get_limit']){
//
//                        return $this->renderError('该商品限购'.$goods_info['get_limit'].'次');
//
//                    }
//                /*}*/
//
//
//            }
//
//
//            //队列判断库存
//            $name = $goodsId . '_list';
//            $len = $redis->lLen($name);
//            if ($len < $total) {
//                return $this->renderError('该藏品已售罄');
//            }
//            for($i=1;$i<=$total;$i++){
//                $redis->rpop($name); //出列
//            }
//
//            $goods = Goods::where(['status' => 10, 'is_delete' => 0, 'goods_id' => $goodsId])->find();
//
//            if (empty($goods)) {
//
//                return $this->renderError('藏品不存在或已下架！');
//
//            }
//
//            $data['amount'] = $goods['goods_price_min'] * $total;
//
//            //调用订单创建接口 查询这个商品还有没有东西
//            $arrList = $Checkout->addorder($user_id, $goodsId, $data['requestNum'], $isBox, $data['amount'], 20,0,$total);
//
//            //如果返回值为110 标识库存为0 不可购买
//            if ($arrList == 110) {
//                return $this->renderError('该藏品已被抢购一空！');
//            }
//
//        }
//
//        if($pay_type == 'balance'){
//
//
//            // 获取订单详情
//            $model = OrderModel::getUserOrderDetail(intval($arrList['order_id']));
//
//
//            if($first_types == 1){
//
//                \app\common\model\User::setIncPoints((int)$user['user_id'],-1,"优先购扣除");
//
//            }
//
//
//            // 余额支付
//            if(!(new OrderModel()) ->onPaymentByBalance($model['order_no'],intval($blind_id))){
//
//                return $this->renderError('余额不足');
//
//            }else{
//
//                return $this->renderSuccess('购买成功');
//            }
//
//        }else if($pay_type == 'sd'){
//
//            $sd = new Sd();
//
//            $result = $sd->pay([
//                'user_id'       => $user_id,
//                'username'      => $user_car['idcar_name'],
//                'idCard'        => $user_car['idcar'],
//                'ordersn'       => $arrList['order_no'],
//                'price'         => $arrList['pay_price'],
//                'goods_name'    => '购买商品',
//                'notify_url'    => 'api/callback/notify/type/sd',
//            ]);
//
//            return $this->renderSuccess(['url'=>$result],'成功');
//
//        }
//
//    }

    /**
     * 购买
     * @param int $transactionId
     * @return Json
     * @throws BaseException
     */
    public function buyBank(int $goodsId, int $isBox,int $bank_id, $order_no = 0)
    {
        $user_id = UserService::getCurrentLoginUserId();
        //创建订单，进行处理!!!!
        $Checkout = new CheckoutService;
        //查询银行绑定信息
        $bank = UserBank::where(['user_id' => $user_id,'id' => $bank_id,'is_delete' => 0,'status' => 10])->find();
        if (empty($bank)){
            return $this->renderError('银行卡信息不存在！');
        }
        $redis = new Redis();
        $orderModel = new Order();
        $orderNo = $orderModel->orderNo();
        //判断是否是待支付订单调起支付
        if (!empty($order_no)) {
            $order = Order::where('order_no', $order_no)->find();
            $goods = Goods::get($order['goods_id']);
            if (empty($goods)){
                return $this->renderError('藏品不存在！');
            }
            //队列
            $name = $order['goods_id'] . '_list';
            $len = $redis->lLen($name);  //获取队列长度
            if ($len == 0) {
                return $this->renderError('该藏品已售罄');
            }
            if ($order['pay_status'] == 10 && $order['order_status'] == 10) {
                $data = jdConfigBank();
                $data['requestNum'] = 'JD' . $orderNo;
                $data['orderAmount'] = $order['total_price'];
                $data['goodsName'] = $goods['goods_name'];
                $data['goodsQuantity'] = $order['goods_sum'];
                $data['userId'] = (string)$order['user_id'];
                $data['bindId'] = $bank['bindId'];
                $data['userAccount'] = $bank['phone'];
                $order->save(['order_no' => $data['requestNum']]);
            } else {
                return $this->renderError('订单状态异常，请刷新重试！');
            }
        } else {
            //获取商品ID  盲盒0
            $data = jdConfigBank();
            $data['requestNum'] = 'JD' . $orderNo;
            //判断购买的是商品还是盲盒
            if ($isBox == 0) {
                //队列判断库存
                $name = $goodsId . '_list';
                $len = $redis->lLen($name);
                if ($len == 0) {
                    return $this->renderError('该藏品已售罄');
                }
                $redis->rpop($name); //出列
                $goods = Goods::where(['is_delete' => 0, 'goods_id' => $goodsId])->find();
                if (empty($goods)) {
                    return $this->renderError('藏品不存在或已下架！');
                }
                $data['orderAmount'] = $goods['goods_price_min'];
                $data['goodsName'] =  $goods['goods_name'];

                //调用订单创建接口 查询这个商品还有没有东西
                $arrList = $Checkout->addorder($user_id, $goodsId, $data['requestNum'], $isBox, $data['orderAmount'], 30);
            } else {
                //购买盲盒
                $box_price = Setting::getItem('box');
                $data['orderAmount'] = (string)$box_price['price'];
                $data['goodsName'] =  '盲盒';
                //调用盲盒订单接口
                $arrList = $Checkout->addorder($user_id, $goodsId, $data['requestNum'], $isBox, $box_price['price'], 30);
            }
            //如果返回值为110 标识库存为0 不可购买
            if ($arrList == 110) {
                return $this->renderError('该藏品已被抢购一空！');
            }
            $data['goodsQuantity'] =  1;
            $data['userId'] = (string)$user_id;
            $data['bindId'] = $bank['bindId'];
            $data['userAccount'] = $bank['phone'];
        }
        $pay_type = input('pay_type');
        if ($pay_type == 'hsq_alipay' || $pay_type == 'hsq_wechat') {
            return $this->renderError('暂未开放！');
            $url = url('/api/huishouqian/pay', [], true, true) . '?order_id=' . $data['requestNum'] . '&type=' . ($pay_type == 'hsq_alipay' ? 'alipay' : 'wechat');
            OrderModel::update(['pay_price' => $data['amount']], ['order_no' => $data['requestNum']]);
            return $url;
        } else {
            $res = $this->jdPayBank($data);
            if (!empty($res) && $res['success'] == true && $res['code'] == 'success') {
                OrderModel::update(['jd_order_no' => $res['orderNum']], ['order_no' => $res['requestNum']]);
                return $this->renderSuccess(['requestNum' => $res['requestNum']]);
            }
        }

        return $this->renderError('购买失败！');
    }

    /**
     * 京东支付
     * @throws BaseException
     */
    public function jdPayBank($data)
    {
        $data1 = json_encode($data);
        $time = time();
        $url = "https://openapi.duolabao.com/api/applyQuickPayWithCheck";
        $info = "secretKey=" . "03f8d570699c4ebf8ae12164dedc1e51e9fbf868" . "&timestamp=" . $time . "&path=/api/applyQuickPayWithCheck&body=" . $data1;
        $token = sha1($info);
        $token = strtoupper($token);
        $header = [
            'Content-Type: application/json',
            'accesskey: 38931903c42d47cb95e20d6e079133202ac23322',
            'timestamp: ' . $time,
            'token: ' . $token
        ];
        $res = post($url, $data1, $header);
        return json_decode($res, true);
    }

    /**
     * 京东支付
     * @throws BaseException
     */
    public function jdPay($data)
    {
        $data1 = json_encode($data);
        $time = time();
        $url = 'https://openapi.duolabao.com/v3/order/pay/create';
        $info = "secretKey=" . "03f8d570699c4ebf8ae12164dedc1e51e9fbf868" . "&timestamp=" . $time . "&path=/v3/order/pay/create&body=" . $data1;
        $token = sha1($info);
        $token = strtoupper($token);
        $header = [
            'Content-Type: application/json',
            'accesskey: 38931903c42d47cb95e20d6e079133202ac23322',
            'timestamp: ' . $time,
            'token: ' . $token
        ];
        $res = post($url, $data1, $header);
        return json_decode($res, true);
    }

    /**
     * 查询我的订单
     */
    public function myOrderList()
    {
        $Checkout = new CheckoutService;
        $myOrderArr = $Checkout->myOrderFind($this->postData());
        if (!$myOrderArr) {
            return $this->renderError('无该类型订单');
        }
        return $this->renderSuccess(['order' => $myOrderArr], '查询成功！');
    }


    /**
     * 查询我的积分
     */
    public function myIntegral()
    {
        $Checkout = new CheckoutService;

        //获取所有POST内容
        $posta = $this->postData();
        $userid = $posta['userid'];

        $myIntegral = $Checkout->myIntegralFind($userid);

        if (!$myIntegral) {
            return $this->renderError('查询失败，请稍后再试！');
        }
        return $this->renderSuccess($myIntegral, '查询成功！');
    }

    /**
     * 手动空投接口
     */
    public function airdrop()
    {
        //输入商品号，一键发送空投
        $Checkout = new CheckoutService;
        //获取所有POST内容
        $posta = $this->postData();
        //获取商品ID
        $goodsid = $posta['goodsid'];

        $kt = $Checkout->airdropNew($goodsid);

        if (empty($kt)) {
            return $this->renderError('空投失败，请稍后再试！');
        }
        return $this->renderSuccess(['sum' => $kt], '空投成功！');
    }

    /**
     * 我的推广页面
     * 查我的推广图
     * 查我推广的人的
     */
    public function myExten()
    {
        //输入商品号，一键发送空投
        $Checkout = new CheckoutService;
        //获取所有POST内容
        $posta = $this->postData();
        //获取商品ID
        $userid = $posta['userid'];
        //查询我的推广二维码和我推广的人
        $extArr = $Checkout->extenImg($userid);

        return $this->renderSuccess($extArr, '查询成功');
//        return $extArr;
    }

    /**
     * 查询盲盒价格
     */
    public function chaBOxM()
    {
        $Checkout = new CheckoutService;
        //查询盲盒价格
        $boxMoney = $Checkout->boxMoneys();

        return $this->renderSuccess(['date' => $boxMoney], '查询成功');

    }

    /**
     * 取消订单
     */
    public function qxorder(){

        $order_id = $this->request->param('order_id','');
        $user_id = UserService::getCurrentLoginUserId();

        $order_info = OrderModel::where('order_id',$order_id)->where('user_id', $user_id)->find();

        if(empty($order_info) || $order_info['is_delete'] == 1){
            return $this->renderError('参数错误');
        }

       $res = $order_info->save([
            'order_status' => 20,
        ]);
        
        if($res){

            Db::name('goods_sku')
                ->where('goods_id', $order_info['goods_id'])
                ->inc('stock_num',$order_info['goods_sum'])
                ->update();
            Db::name('goods')
                ->where('goods_id', $order_info['goods_id'])
                ->inc('stock_total',$order_info['goods_sum'])
                ->update();

            return $this->renderSuccess([],'关闭成功');

        }else{

            return $this->renderError('关闭出错');

        }
    }
}
