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

use app\api\model\Order as OrderModel;
use app\api\service\passport\Login as LoginService;
use app\api\service\User as UserService;
use app\api\service\Cart as CartService;
use app\api\service\order\Checkout as CheckoutService;
use app\api\validate\order\Checkout as CheckoutValidate;
use cores\exception\BaseException;
use dh2y\qrcode\QRcode;
use think\response\Json;
use app\common\model\order;
use think\validate\ValidateRule;
use Yansongda\Pay\Pay;
use think\facade\Db;
use Yansongda\Pay\Log;

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
        //创建订单，进行处理!!!!
        $Checkout = new CheckoutService;

        //获取所有POST内容
        $posta=$this->postData();
        //生成订单编号
        $order = new Order();
        
        
        //判断是否是待支付订单调起支付
        if(isset($posta['order_no'])){
            $orderNo=$posta['order_no'];
            //查询这个订单的信息
            $totalamount=$Checkout->getOrder($orderNo);
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
        $userid=(int)$posta['userid'];

        //获取商品ID  盲盒0
        $goodsid=(int)$posta['goodsid'];
        //藏品类型 0藏品 1盲盒
        $isBox=$posta['isBox'];
        
        //判断是微信支付还是支付宝支付
        if(!isset($posta['pay_type'])){
            $payType=30;
        }else{
            $payType=$posta['pay_type'];
        }
        
        //判断购买的是商品还是盲盒
        if($isBox==0){
            //购买藏品
            $boxMoneys=0.00;
            //调用订单创建接口 查询这个商品还有没有东西
            $arrList=$Checkout->addorder($userid,$goodsid,$orderNo,$isBox,$boxMoneys,$payType);
            //获取订单总价格
            $totalamount=$arrList['total_price'];
        }else{
            //购买盲盒
            
            //查询盲盒价格
            $boxMoney=$Checkout->boxMoneys();
            $boxMoneys=$boxMoney['box_money'];
            
            
            //调用盲盒订单接口
            $arrList=$Checkout->addorder($userid,$goodsid,$orderNo,$isBox,$boxMoneys,$payType);
            $totalamount=$boxMoneys;
        }
        //如果返回值为110 标识库存为0 不可购买
        if($arrList==110){
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
                    
        
        try{
            $data = $alipay->verify(); // 是的，验签就这么简单！
            // 请自行对 trade_status 进行判断及其它逻辑进行判断，在支付宝的业务通知中，只有交易通知状态为 TRADE_SUCCESS 或 TRADE_FINISHED 时，支付宝才会认定为买家付款成功。
            // 1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号；
            // 2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额）；
            // 3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）；
            // 4、验证app_id是否为该商户本身。
            // 5、其它业务逻辑情况
                    if($data['trade_status']='TRADE_SUCCESS'){
                        //成功然后做支付成功逻辑
                        
                        // $a=Db::name('aboutus')->where('id','=',1)->update(['text_us'=>$orderNo]);
                        //获取订单编号
                        $orderNo=$data['out_trade_no'];

                        //根据订单编号查询是盲盒还是商品
                        $orderTypeArr=$Checkout->checkOrderType($orderNo);

                        //将订单改为已支付
                        $orderup=$Checkout->orderup($orderNo);

                        if($orderup){
                            //购买后添加的积分  total_amount订单价格四舍五入
                            $pointsbonus=round($data['total_amount']);
                            
                            //获取当前订单的用户ID和商品ID
                            $lists=$Checkout->chaUserId($orderNo);
                            
                            $userid=$lists[0]['user_id'];
                            $goodsid=$lists[0]['goods_id'];

                            //给用户添加积分,每次积分变动时统计一下剩余积分
                            $points=$Checkout->pointss($userid,$pointsbonus,$orderup);
                            //判断积分是否已经增加
                            if($points){

                                if($orderTypeArr[0]['is_Box']==1){
                                    //购买的是盲盒
                                    //调用抽取盲盒接口
                                    $collec=$Checkout->drawboxs($userid,$orderNo,$goodsid);

                                }else{
                                    //购买的是商品

                                    //调用新增藏品接口
                                    $collec=$Checkout->addCollec($userid,$goodsid,$orderNo);
                                }
                                //如果已经新增藏品则将状态改成已完成
                                if($collec){
                                    //调用改变状态接口
                                    $collec=$Checkout->orderType($orderNo);
                                    if(!$collec){
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
        
        
        
        //创建订单，进行处理!!!!
        $Checkout = new CheckoutService;
        //获取所有POST内容
        $posta=$this->postData();
        
        //判断是否是待支付订单调起支付
        if(isset($posta['order_no'])){
            $orderNo=$posta['order_no'];
            //查询这个订单的信息
            
            $jiage=$Checkout->getOrder($orderNo);
            
            $fen=$jiage['total_price'];
            
            $qiangzhuan=$fen*100;
            
            $totalamount=(int)$qiangzhuan;
            
            $order = [
            'out_trade_no' => $orderNo,//订单编号
            'total_fee' => $totalamount,//价格
            'body' => '藏品购买',//默认订单名
//            'openid' => 'onkVf1FjWS5SBIixxxxxxx',
            ];

            //微信支付接口
            //输出支付的HTML代码给前端调用
            $response=getWeAliConfid()->wap($order);
            $url=$response->getTargetUrl();
            return $url;
        }
        
        
        //生成订单编号
        $order = new Order();
        $orderNo = $order->orderNo();

        //获取用户ID
        $userid=(int)$posta['userid'];

        //获取商品ID  盲盒0
        $goodsid=(int)$posta['goodsid'];
    
        if(!isset($posta['pay_type'])){
            $payType=20;
        }else{
            $payType=$posta['pay_type'];
        }

        //藏品类型 0藏品 1盲盒
        $isBox=$posta['isBox'];
        
        //判断购买的是商品还是盲盒
        if($isBox==0){
            //购买藏品
            $boxMoneys=0.00;
            //调用订单创建接口 查询这个商品还有没有东西
            $arrList=$Checkout->addorder($userid,$goodsid,$orderNo,$isBox,$boxMoneys,$payType);
            
            //获取订单总价格
            $totalamount=$arrList['total_price'];
        }else{
            //购买盲盒

            //查询盲盒价格
            $boxMoney=$Checkout->boxMoneys();
            $boxMoneys=$boxMoney['box_money'];
            //调用盲盒订单接口
            $arrList=$Checkout->addorder($userid,$goodsid,$orderNo,$isBox,$boxMoneys,$payType);
            
            $totalamount=$boxMoneys;
        }
        
        //如果返回值为110 标识库存为0 不可购买
        if($arrList==110){
            return $this->renderError('该商品已被抢购一空！');
        }
        //获取订单赠送积分
//        $pointsbonus=$arrList['points_bonus'];

        $a=$totalamount*100;
        $totalamount=(int)$a;
        
        //获取微信的openid
        
        $order = [
            'out_trade_no' => $orderNo,//订单编号
            'total_fee' => $totalamount,//价格
            'body' => '藏品购买',//默认订单名
//            'openid' => 'onkVf1FjWS5SBIixxxxxxx',
        ];

        //微信支付接口
        //输出支付的HTML代码给前端调用
        $response=getWeAliConfid()->wap($order);
        $url=$response->getTargetUrl();
        return $url;
    }

    /**
     * 微信回调
     */
    public function returnnotifywe()
    {
        $alipay = getWeAliConfid();

        $Checkout = new OrderModel();
        try{
            $data = $alipay->verify(); // 是的，验签就这么简单！
            // 请自行对 trade_status 进行判断及其它逻辑进行判断，在支付宝的业务通知中，只有交易通知状态为 TRADE_SUCCESS 或 TRADE_FINISHED 时，支付宝才会认定为买家付款成功。
            // 1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号；
            // 2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额）；
            // 3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）；
            // 4、验证app_id是否为该商户本身。
            // 5、其它业务逻辑情况
            if($data['trade_status']='TRADE_SUCCESS'){
                //成功然后做支付成功逻辑
                // $a=Db::name('aboutus')->where('id','=',1)->update(['text_us'=>$orderNo]);
                //获取订单编号
                $orderNo=$data['out_trade_no'];

                //根据订单编号查询是盲盒还是商品
                $orderTypeArr=$Checkout->checkOrderType($orderNo);

                //将订单改为已支付
                $orderup=$Checkout->orderup($orderNo);

                if($orderup){
                    //购买后添加的积分  total_amount订单价格四舍五入
                    $pointsbonus=round($data['total_amount']);

                    //获取当前订单的用户ID和商品ID
                    $lists=$Checkout->chaUserId($orderNo);

                    $userid=$lists[0]['user_id'];
                    $goodsid=$lists[0]['goods_id'];

                    //给用户添加积分,每次积分变动时统计一下剩余积分
                    $points=$Checkout->pointss($userid,$pointsbonus,$orderup);
                    //判断积分是否已经增加
                    if($points){

                        if($orderTypeArr[0]['is_Box']==1){
                            //购买的是盲盒
                            //调用抽取盲盒接口
                            $collec=$Checkout->drawboxs($userid,$orderNo,$goodsid);

                        }else{
                            //购买的是商品

                            //调用新增藏品接口
                            $collec=$Checkout->addCollec($userid,$goodsid,$orderNo);
                        }
                        //如果已经新增藏品则将状态改成已完成
                        if($collec){
                            //调用改变状态接口
                            $collec=$Checkout->orderType($orderNo);
                            if(!$collec){
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
     * 测试用直接购买接口
     * userid 用户ID
     * goodsid 商品ID
     * Access-Token 用户TOKEN
     * storeId 商户ID 默认10001
     * platform 平台 默认H5
     */
//    public function purchaseGoods(){
//        $Checkout = new CheckoutService;
//        //获取所有POST内容
//        $posta=$this->postData();
//        //生成订单编号
//        $order = new Order();
//        $orderNo = $order->orderNo();
//        //获取用户ID
//        $userid=(int)$posta['userid'];
//        //获取商品ID
//        $goodsid=(int)$posta['goodsid'];
//
//        //调用订单创建接口 先查询这个商品还有没有东西
//        $arrList=$Checkout->addorder($userid,$goodsid,$orderNo);
//
//        //如果返回值为110 标识库存为0 不可购买
//        if($arrList==110){
//            return $this->renderError('该商品已被抢购一空！');
//        }
//
//        //获取订单编号
////        $orderNo=$arrList['order_no'];
//
//
//
//        //获取订单赠送积分
//        $pointsbonus=$arrList['points_bonus'];
//
//        //获取订单总价格
//        $totalamount=$arrList['total_price'];
//
//        //调用支付接口
//        $payment=true;
//
//        if(!$payment){
//            //如果未支付，则调用支付失败库存+1功能
//            $Checkout->noPayment($goodsid);
//            return $this->renderError('客户取消购买！');
//        }
//        //将订单改为完成
//        $orderup=$Checkout->orderup($orderNo);
//        //商品库存-1
//
//        //给用户添加积分,每次积分变动时统计一下剩余积分
//        $points=$Checkout->points($userid,$pointsbonus,$orderup);
//;
//        //调用新增藏品接口
//        $collec=$Checkout->addCollec($userid,$goodsid,$orderNo);
//
//        if(!$collec){//新增或修改成功
//            return $this->renderError('购买失败！');
//        }
//        return $this->renderSuccess(['stocknum'=>$arrList['stocknum']],'购买成功!');
//    }


    /**
     * 查询我的订单
     */
    public function myOrderList(){

        $Checkout = new CheckoutService;
        //获取所有POST内容
        $posta=$this->postData();

        $userid=$posta['userid'];

        if(isset($posta['orderType'])){
            $orderType=$posta['orderType'];
        }else{
            $orderType=5;
        }
        $myOrderArr=$Checkout->myOrderFind($userid,$orderType);

        if(!$myOrderArr){
            return $this->renderError('无该类型订单');
        }
        return $this->renderSuccess(['order' => $myOrderArr],'查询成功！');
    }


    /**
     * 查询我的积分
     */
    public function myIntegral(){
        $Checkout = new CheckoutService;

        //获取所有POST内容
        $posta=$this->postData();
        $userid=$posta['userid'];

        $myIntegral=$Checkout->myIntegralFind($userid);

        if(!$myIntegral){
            return $this->renderError('查询失败，请稍后再试！');
        }
        return $this->renderSuccess($myIntegral,'查询成功！');
    }

    /**
     * 手动空投接口
     */
    public function airdrop(){
        //输入商品号，一键发送空投
        $Checkout = new CheckoutService;
        //获取所有POST内容
        $posta=$this->postData();
        //获取商品ID
        $goodsid=$posta['goodsid'];

        $kt=$Checkout->airdropNew($goodsid);

        if(empty($kt)){
            return $this->renderError('空投失败，请稍后再试！');
        }
        return $this->renderSuccess(['sum'=>$kt],'空投成功！');
    }

    /**
     * 我的推广页面
     * 查我的推广图
     * 查我推广的人的
     */
    public function myExten(){
        //输入商品号，一键发送空投
        $Checkout = new CheckoutService;
        //获取所有POST内容
        $posta=$this->postData();
        //获取商品ID
        $userid=$posta['userid'];
        //查询我的推广二维码和我推广的人
        $extArr=$Checkout->extenImg($userid);

        return $this->renderSuccess($extArr,'查询成功');
//        return $extArr;
    }

    /**
     * 查询盲盒价格
     */
    public function chaBOxM(){
        $Checkout = new CheckoutService;
        //查询盲盒价格
        $boxMoney=$Checkout->boxMoneys();

        return $this->renderSuccess(['date'=>$boxMoney],'查询成功');

    }
}