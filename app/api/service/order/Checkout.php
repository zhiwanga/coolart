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

namespace app\api\service\order;

use app\api\model\GoodsImage;
use app\api\model\Order as OrderModel;

use app\api\model\Setting;
use app\api\model\User as UserModel;
use app\api\model\Goods as GoodsModel;
use app\api\model\Setting as SettingModel;
use app\api\model\UserCoupon as UserCouponModel;

use app\api\service\User as UserService;
use app\api\service\Payment as PaymentService;
use app\api\service\user\Grade as UserGradeService;
use app\api\service\coupon\GoodsDeduct as GoodsDeductService;
use app\api\service\points\GoodsDeduct as PointsDeductService;
use app\api\service\order\source\checkout\Factory as CheckoutFactory;

use app\common\enum\Setting as SettingEnum;
use app\common\enum\order\PayType as OrderPayTypeEnum;
use app\common\enum\order\OrderStatus as OrderStatusEnum;
use app\common\enum\order\OrderSource as OrderSourceEnum;
use app\common\enum\order\DeliveryType as DeliveryTypeEnum;
use app\common\model\Goods;
use app\common\model\Integrals;
use app\common\model\Transaction;
use app\common\service\BaseService;
use app\common\service\delivery\Express as ExpressService;
use app\common\service\goods\source\Factory as StockFactory;
use app\common\library\helper;
use app\store\controller\files\Group;
use cores\exception\BaseException;
use think\db\Where;
use think\facade\Db;


/**
 * 订单结算台服务类
 * Class Checkout
 * @package app\api\service\order
 */
class Checkout extends BaseService
{
    /* $model OrderModel 订单模型 */
    public $model;

    /* @var UserModel $user 当前用户信息 */
    private $user;

    // 订单结算商品列表
    private $goodsList = [];

    /**
     * 订单结算api参数
     * @var array
     */
    private $param = [
        'delivery' => null, // 配送方式
        'couponId' => 0,    // 优惠券id
        'isUsePoints' => 0,    // 是否使用积分抵扣
        'remark' => '',    // 买家留言
        'payType' => OrderPayTypeEnum::WECHAT,  // 默认支付方式为微信
    ];

    /**
     * 订单结算的规则
     * @var array
     */
    private $checkoutRule = [
        'isUserGrade' => true,    // 会员等级折扣
        'isCoupon' => true,        // 优惠券抵扣
        'isUsePoints' => true,        // 是否使用积分抵扣
    ];

    /**
     * 订单来源
     * @var array
     */
    private $orderSource = [
        'source' => OrderSourceEnum::MASTER,
        'source_id' => 0,
    ];

    /**
     * 订单结算数据
     * @var array
     */
    private $orderData = [];

    /**
     * 构造函数
     * Checkout constructor.
     * @throws BaseException
     */
    public function __construct()
    {
        parent::__construct();
//        var_dump(11);exit;
        $this->user = UserService::getCurrentLoginUser(true);
        $this->model = new OrderModel;
        $this->storeId = $this->getStoreId();
    }

    /**
     * 设置结算台请求的参数
     * @param $param
     * @return array
     */
    public function setParam($param): array
    {
        $this->param = array_merge($this->param, $param);
        return $this->getParam();
    }

    /**
     * 获取结算台请求的参数
     * @return array
     */
    public function getParam(): array
    {
        return $this->param;
    }

    /**
     * 订单结算的规则
     * @param $data
     * @return $this
     */
    public function setCheckoutRule($data): Checkout
    {
        $this->checkoutRule = array_merge($this->checkoutRule, $data);
        return $this;
    }

    /**
     * 设置订单来源(普通订单)
     * @param $data
     * @return $this
     */
    public function setOrderSource($data): Checkout
    {
        $this->orderSource = array_merge($this->orderSource, $data);
        return $this;
    }

    /**
     * 订单确认-结算台
     * @param $goodsList
     * @return array
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function onCheckout($goodsList): array
    {
        // 订单确认-立即购买
        $this->goodsList = $goodsList;

        return $this->checkout();
    }

    /**
     * 订单结算台
     * @return array
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function checkout(): array
    {
        // 整理订单数据
        $this->orderData = $this->getOrderData();
        // 验证商品状态, 是否允许购买
        $this->validateGoodsList();
        // 订单商品总数量
        $orderTotalNum = helper::getArrayColumnSum($this->goodsList, 'total_num');
        // 设置订单商品会员折扣价
        $this->setOrderGoodsGradeMoney();
        // 设置订单商品总金额(不含优惠折扣)
        $this->setOrderTotalPrice();
        // 当前用户可用的优惠券列表
        $couponList = $this->getUserCouponList((float)$this->orderData['orderTotalPrice']);
        // 计算优惠券抵扣
        $this->setOrderCouponMoney($couponList, (int)$this->param['couponId']);
        // 计算可用积分抵扣
        $this->setOrderPoints();
        // 计算订单商品的实际付款金额
        $this->setOrderGoodsPayPrice();
        // 设置默认配送方式
        if (!$this->param['delivery']) {
            $deliveryType = SettingModel::getItem(SettingEnum::DELIVERY)['delivery_type'];
            $this->param['delivery'] = current($deliveryType);
        }
        // 处理配送方式
        if ($this->param['delivery'] == DeliveryTypeEnum::EXPRESS) {
            $this->setOrderExpress();
        }
        // 计算订单最终金额
        $this->setOrderPayPrice();
        // 计算订单积分赠送数量
        $this->setOrderPointsBonus();
        // 返回订单数据
        return array_merge([
            'goodsList' => $this->goodsList,   // 商品信息
            'orderTotalNum' => $orderTotalNum,        // 商品总数量
            'couponList' => array_values($couponList), // 优惠券列表
            'hasError' => $this->hasError(),
            'errorMsg' => $this->getError(),
        ], $this->orderData);
    }

    /**
     * 计算订单可用积分抵扣
     * @return void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function setOrderPoints(): void
    {
        // 设置默认的商品积分抵扣信息
        $this->setDefaultGoodsPoints();
        // 积分设置
        $setting = SettingModel::getItem('points');
        // 条件：后台开启下单使用积分抵扣
        if (!$setting['is_shopping_discount'] || !$this->checkoutRule['isUsePoints']) {
            return;
        }
        // 条件：订单金额满足[?]元
        if (helper::bccomp($setting['discount']['full_order_price'], $this->orderData['orderTotalPrice']) === 1) {
            return;
        }
        // 计算订单商品最多可抵扣的积分数量
        $this->setOrderGoodsMaxPointsNum();
        // 订单最多可抵扣的积分总数量
        $maxPointsNumCount = helper::getArrayColumnSum($this->goodsList, 'max_points_num');
        // 实际可抵扣的积分数量
        $actualPointsNum = min($maxPointsNumCount, $this->user['points']);
        if ($actualPointsNum < 1) {
            return;
        }
        // 计算订单商品实际抵扣的积分数量和金额
        $GoodsDeduct = new PointsDeductService($this->goodsList);
        $GoodsDeduct->setGoodsPoints($maxPointsNumCount, $actualPointsNum);
        // 积分抵扣总金额
        $orderPointsMoney = helper::getArrayColumnSum($this->goodsList, 'points_money');
        $this->orderData['pointsMoney'] = helper::number2($orderPointsMoney);
        // 积分抵扣总数量
        $this->orderData['pointsNum'] = $actualPointsNum;
        // 允许积分抵扣
        $this->orderData['isAllowPoints'] = true;
    }

    /**
     * 计算订单商品最多可抵扣的积分数量
     * @return void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function setOrderGoodsMaxPointsNum(): void
    {
        // 积分设置
        $setting = SettingModel::getItem('points');
        foreach ($this->goodsList as &$goods) {
            // 商品不允许积分抵扣
            if (!$goods['is_points_discount']) continue;
            // 积分抵扣比例
            $deductionRatio = helper::bcdiv($setting['discount']['max_money_ratio'], 100);
            // 最多可抵扣的金额
            $totalPayPrice = helper::bcsub($goods['total_price'], $goods['coupon_money']);
            $maxPointsMoney = helper::bcmul($totalPayPrice, $deductionRatio);
            // 最多可抵扣的积分数量
            $goods['max_points_num'] = helper::bcdiv($maxPointsMoney, $setting['discount']['discount_ratio'], 0);
        }
    }

    /**
     * 设置默认的商品积分抵扣信息
     * @return void
     */
    private function setDefaultGoodsPoints(): void
    {
        foreach ($this->goodsList as &$goods) {
            // 最多可抵扣的积分数量
            $goods['max_points_num'] = 0;
            // 实际抵扣的积分数量
            $goods['pointsNum'] = 0;
            // 实际抵扣的金额
            $goods['points_money'] = 0.00;
        }
    }

    /**
     * 整理订单数据(结算台初始化)
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function getOrderData(): array
    {

        // 系统支持的配送方式 (后台设置)
        $deliveryType = SettingModel::getItem(SettingEnum::DELIVERY)['delivery_type'];
        return [
            // 当前配送类型
            'delivery' => $this->param['delivery'] > 0 ? $this->param['delivery'] : $deliveryType[0],
            // 默认地址
            'address' => $this->user['address_default'],
            // 是否存在收货地址
            'existAddress' => $this->user['address_id'] > 0,
            // 配送费用
            'expressPrice' => 0.00,
            // 当前用户收货城市是否存在配送规则中
            'isIntraRegion' => true,
            // 是否允许使用积分抵扣
            'isAllowPoints' => false,
            // 是否使用积分抵扣
            'isUsePoints' => $this->param['isUsePoints'],
            // 积分抵扣金额
            'pointsMoney' => 0.00,
            // 赠送的积分数量
            'pointsBonus' => 0,
            // 支付方式
            'payType' => $this->param['payType'],
            // 系统设置 TODO: 废弃
            'setting' => $this->getSetting(),
        ];
    }

    /**
     * 获取订单页面中使用到的系统设置
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getSetting(): array
    {
        // 系统支持的配送方式 (后台设置)
        $deliveryType = SettingModel::getItem(SettingEnum::DELIVERY)['delivery_type'];

        // 积分设置
        $pointsSetting = SettingModel::getItem(SettingEnum::POINTS);
        return [
            'deliveryType' => $deliveryType,                     // 支持的配送方式
            'points_name' => $pointsSetting['points_name'],      // 积分名称
            'points_describe' => $pointsSetting['describe'],     // 积分说明
        ];
    }

    // 获取订单结算时的个人信息
    public function getPersonal(): array
    {
        return [
            'user_id' => $this->user['user_id'],
            'balance' => $this->user['balance'],
            'points' => $this->user['points'],
            'address_id' => $this->user['address_id'],
        ];
    }

    /**
     * 当前用户可用的优惠券列表
     * @param float $orderTotalPrice 总金额
     * @return array|mixed
     * @throws \think\db\exception\DbException
     */
    private function getUserCouponList(float $orderTotalPrice)
    {
        // 是否开启优惠券折扣
        if (!$this->checkoutRule['isCoupon']) {
            return [];
        }
        // 整理当前订单所有商品ID集
        $orderGoodsIds = helper::getArrayColumn($this->goodsList, 'goods_id');
        // 当前用户可用的优惠券列表
        $couponList = UserCouponModel::getUserCouponList($this->user['user_id'], $orderTotalPrice);
        // 判断当前优惠券是否满足订单使用条件 (优惠券适用范围)
        return UserCouponModel::couponListApplyRange($couponList, $orderGoodsIds);

    }

    /**
     * 验证订单商品的状态
     * @return void
     */
    private function validateGoodsList(): void
    {
        $Checkout = CheckoutFactory::getFactory(
            $this->user,
            $this->goodsList,
            $this->orderSource['source']
        );
        $status = $Checkout->validateGoodsList();
        $status == false && $this->setError($Checkout->getError());
    }

    /**
     * 设置订单的商品总金额(不含优惠折扣)
     */
    private function setOrderTotalPrice()
    {
        // 订单商品的总金额(不含优惠券折扣)
        $this->orderData['orderTotalPrice'] = helper::number2(helper::getArrayColumnSum($this->goodsList, 'total_price'));
    }

    /**
     * 设置订单的实际支付金额(含配送费)
     */
    private function setOrderPayPrice()
    {
        // 订单金额(含优惠折扣)
        $this->orderData['orderPrice'] = helper::number2(helper::getArrayColumnSum($this->goodsList, 'total_pay_price'));
        // 订单实付款金额(订单金额 + 运费)
        $this->orderData['orderPayPrice'] = helper::number2(helper::bcadd($this->orderData['orderPrice'], $this->orderData['expressPrice']));
    }

    /**
     * 计算订单积分赠送数量
     * @return void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function setOrderPointsBonus(): void
    {
        // 初始化商品积分赠送数量
        foreach ($this->goodsList as &$goods) {
            $goods['points_bonus'] = 0;
        }
        // 积分设置
        $setting = SettingModel::getItem('points');
        // 条件：后台开启开启购物送积分
        if (!$setting['is_shopping_gift']) {
            return;
        }
        // 设置商品积分赠送数量
        foreach ($this->goodsList as &$goods) {
            // 积分赠送比例
            $ratio = $setting['gift_ratio'] / 100;
            // 计算抵扣积分数量
            $goods['points_bonus'] = !$goods['is_points_gift'] ? 0 : helper::bcmul($goods['total_pay_price'], $ratio, 0);
        }
        //  订单积分赠送数量
        $this->orderData['pointsBonus'] = helper::getArrayColumnSum($this->goodsList, 'points_bonus');
    }

    /**
     * 计算订单商品的实际付款金额
     * @return void
     */
    private function setOrderGoodsPayPrice(): void
    {
        // 商品总价 - 优惠抵扣
        foreach ($this->goodsList as &$goods) {
            // 减去优惠券抵扣金额
            $value = helper::bcsub($goods['total_price'], $goods['coupon_money']);
            // 减去积分抵扣金额
            if ($this->orderData['isAllowPoints'] && $this->orderData['isUsePoints']) {
                $value = helper::bcsub($value, $goods['points_money']);
            }
            $goods['total_pay_price'] = helper::number2($value);
        }
    }

    /**
     * 设置订单商品会员折扣价
     * @return void
     * @throws BaseException
     */
    private function setOrderGoodsGradeMoney(): void
    {
        // 设置默认数据
        helper::setDataAttribute($this->goodsList, [
            // 标记参与会员折扣
            'is_user_grade' => false,
            // 会员等级抵扣的金额
            'grade_ratio' => 0,
            // 会员折扣的商品单价
            'grade_goods_price' => 0.00,
            // 会员折扣的总额差
            'grade_total_money' => 0.00,
        ], true);

        // 是否开启会员等级折扣
        if (!$this->checkoutRule['isUserGrade']) {
            return;
        }
        // 获取当前登录用户的会员等级信息
        $gradeInfo = UserGradeService::getCurrentGradeInfo();
        // 判断商品是否参与会员折扣
        if (empty($gradeInfo)) {
            return;
        }
        // 计算抵扣金额
        foreach ($this->goodsList as &$goods) {
            // 判断商品是否参与会员折扣
            if (!$goods['is_enable_grade']) {
                continue;
            }
            // 折扣比例
            $discountRatio = $gradeInfo['equity']['discount'];
            // 商品单独设置了会员折扣
            if ($goods['is_alone_grade'] && isset($goods['alone_grade_equity'][$this->user['grade_id']])) {
                $discountRatio = $goods['alone_grade_equity'][$gradeInfo['grade_id']];
            }
            if (empty($discountRatio)) {
                continue;
            }
            // 会员折扣后的商品总金额
            $gradeTotalPrice = UserGradeService::getDiscountPrice($goods['total_price'], $discountRatio);
            helper::setDataAttribute($goods, [
                'is_user_grade' => true,
                'grade_ratio' => $discountRatio,
                'grade_goods_price' => UserGradeService::getDiscountPrice($goods['goods_price'], $discountRatio),
                'grade_total_money' => helper::bcsub($goods['total_price'], $gradeTotalPrice),
                'total_price' => $gradeTotalPrice,
            ], false);
        }
    }

    /**
     * 设置订单优惠券抵扣信息
     * @param array $couponList 当前用户可用的优惠券列表
     * @param int $couponId 当前选择的优惠券id
     * @return void
     * @throws BaseException
     */
    private function setOrderCouponMoney(array $couponList, int $couponId): void
    {
        // 设置默认数据：订单信息
        helper::setDataAttribute($this->orderData, [
            'couponId' => 0,       // 用户优惠券id
            'couponMoney' => 0,    // 优惠券抵扣金额
        ], false);
        // 设置默认数据：订单商品列表
        helper::setDataAttribute($this->goodsList, [
            'coupon_money' => 0,    // 优惠券抵扣金额
        ], true);
        // 验证选择的优惠券ID是否合法
        if (!$this->verifyOrderCouponId($couponId, $couponList)) {
            return;
        }
        // 获取优惠券信息
        $couponInfo = $this->getCouponInfo($couponId, $couponList);
        // 计算订单商品优惠券抵扣金额
        $goodsListTemp = helper::getArrayColumns($this->goodsList, ['goods_id', 'goods_sku_id', 'total_price']);
        $CouponMoney = new GoodsDeductService;
        $rangeGoodsList = $CouponMoney->setGoodsList($goodsListTemp)
            ->setCouponInfo($couponInfo)
            ->setGoodsCouponMoney()
            ->getRangeGoodsList();
        // 分配订单商品优惠券抵扣金额
        foreach ($this->goodsList as &$goods) {
            $goodsKey = "{$goods['goods_id']}-{$goods['goods_sku_id']}";
            if (isset($rangeGoodsList[$goodsKey])) {
                $goods['coupon_money'] = $rangeGoodsList[$goodsKey]['coupon_money'] / 100;
            }
        }
        // 记录订单优惠券信息
        $this->orderData['couponId'] = $couponId;
        $this->orderData['couponMoney'] = helper::number2($CouponMoney->getActualReducedMoney() / 100);
    }

    /**
     * 验证用户选择的优惠券ID是否合法
     * @param int $couponId
     * @param $couponList
     * @return bool
     * @throws BaseException
     */
    private function verifyOrderCouponId(int $couponId, $couponList): bool
    {
        // 是否开启优惠券折扣
        if (!$this->checkoutRule['isCoupon']) {
            return false;
        }
        // 如果没有可用的优惠券，直接返回
        if ($couponId <= 0 || empty($couponList)) {
            return false;
        }
        // 判断优惠券是否存在
        $couponInfo = $this->getCouponInfo($couponId, $couponList);
        if (!$couponInfo) {
            throwError('未找到优惠券信息');
        }
        // 判断优惠券适用范围是否合法
        if (!$couponInfo['is_apply']) {
            throwError($couponInfo['not_apply_info']);
        }
        return true;
    }

    /**
     * 查找指定的优惠券信息
     * @param int $couponId 优惠券ID
     * @param array $couponList 优惠券列表
     * @return false|mixed
     */
    private function getCouponInfo(int $couponId, array $couponList)
    {
        return helper::getArrayItemByColumn($couponList, 'user_coupon_id', $couponId);
    }

    /**
     * 订单配送-快递配送
     * @return void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function setOrderExpress(): void
    {
        // 设置默认数据：配送费用
        helper::setDataAttribute($this->goodsList, [
            'expressPrice' => 0,
        ], true);
        // 当前用户收货城市id
        $cityId = $this->user['address_default'] ? (int)$this->user['address_default']['city_id'] : 0;
        // 初始化配送服务类
        $ExpressService = new ExpressService($cityId, $this->goodsList);
        // 验证商品是否在配送范围
        $isIntraRegion = $ExpressService->isIntraRegion();
        if ($cityId > 0 && $isIntraRegion == false) {
            $notInRuleGoodsName = $ExpressService->getNotInRuleGoodsName();
            $this->setError("很抱歉，您的收货地址不在商品 [{$notInRuleGoodsName}] 的配送范围内");
        }
        // 订单总运费金额
        $this->orderData['isIntraRegion'] = $isIntraRegion;
        $this->orderData['expressPrice'] = $ExpressService->getDeliveryFee();
    }

    /**
     * 创建新订单
     * @param array $order 订单信息
     * @return bool
     */
    public function createOrder(array $order): bool
    {

        // 表单验证
        if (!$this->validateOrderForm($order)) {
            return false;
        }
        // 创建新的订单
        $status = $this->model->transaction(function () use ($order) {
            // 创建订单事件
            return $this->createOrderEvent($order);
        });
        // 余额支付标记订单已支付
        if ($status && $order['payType'] == OrderPayTypeEnum::BALANCE) {
            return $this->model->onPaymentByBalance($this->model['order_no']);
        }
        return $status;
    }

    /**
     * 创建订单事件
     * @param $order
     * @return bool
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function createOrderEvent($order): bool
    {
        // 新增订单记录
        $status = $this->add($order, $this->param['remark']);
        if ($order['delivery'] == DeliveryTypeEnum::EXPRESS) {
            // 记录收货地址
            $this->saveOrderAddress($order['address']);
        }
        // 保存订单商品信息
        $this->saveOrderGoods($order);
        // 更新商品库存 (针对下单减库存的商品)
        $this->updateGoodsStockNum($order);
        // 设置优惠券使用状态
        $order['couponId'] > 0 && UserCouponModel::setIsUse((int)$order['couponId']);
        // 积分抵扣情况下扣除用户积分
        if ($order['isAllowPoints'] && $order['isUsePoints'] && $order['pointsNum'] > 0) {
            $describe = "用户消费：{$this->model['order_no']}";
            UserModel::setIncPoints($this->user['user_id'], -$order['pointsNum'], $describe);
        }
        // 获取订单详情
        $detail = OrderModel::getUserOrderDetail((int)$this->model['order_id']);
        return $status;
    }

    /**
     * 构建支付请求的参数
     * @return array
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function onOrderPayment(): array
    {
        return PaymentService::orderPayment($this->model, $this->param['payType']);
    }

    /**
     * 表单验证 (订单提交)
     * @param array $order 订单信息
     * @return bool
     */
    private function validateOrderForm(array $order): bool
    {

//        var_dump(123);die;
        $order['address'] = '无';
//        var_dump($order);die;
        if ($order['delivery'] == DeliveryTypeEnum::EXPRESS) {
            if (empty($order['address'])) {
                $this->error = '您还没有选择配送地址';
                return false;
            }
        }

//        var_dump($order['payType']);die;
        // 余额支付时判断用户余额是否足够
        if ($order['payType'] == OrderPayTypeEnum::BALANCE) {
            if ($this->user['balance'] < $order['orderPayPrice']) {
                $this->error = '您的余额不足，无法使用余额支付';
                return false;
            }
        }
        return true;
    }

    /**
     * 当前订单是否存在和使用积分抵扣
     * @param $order
     * @return bool
     */
    private function isExistPointsDeduction($order): bool
    {
        return $order['isAllowPoints'] && $order['isUsePoints'];
    }

    /**
     * 新增订单记录
     * @param $order
     * @param string $remark
     * @return bool|false
     */
    private function add($order, string $remark = ''): bool
    {
        // 当前订单是否存在和使用积分抵扣
        $isExistPointsDeduction = $this->isExistPointsDeduction($order);
        // 订单数据
        $data = [
            'user_id' => $this->user['user_id'],
            'order_no' => $this->model->orderNo(),
            'total_price' => $order['orderTotalPrice'],
            'order_price' => $order['orderPrice'],
            'coupon_id' => $order['couponId'],
            'coupon_money' => $order['couponMoney'],
            'points_money' => $isExistPointsDeduction ? $order['pointsMoney'] : 0.00,
            'points_num' => $isExistPointsDeduction ? $order['pointsNum'] : 0,
            'pay_price' => $order['orderPayPrice'],
            'delivery_type' => $order['delivery'],
            'pay_type' => $order['payType'],
            'buyer_remark' => trim($remark),
            'order_source' => $this->orderSource['source'],
            'order_source_id' => $this->orderSource['source_id'],
            'points_bonus' => $order['pointsBonus'],
            'order_status' => OrderStatusEnum::NORMAL,
            'platform' => getPlatform(),
            'store_id' => $this->storeId,
        ];
        if ($order['delivery'] == DeliveryTypeEnum::EXPRESS) {
            $data['express_price'] = $order['expressPrice'];
        }
        // 保存订单记录
        return $this->model->save($data);
    }

    /**
     * 保存订单商品信息
     * @param $order
     * @return void
     */
    private function saveOrderGoods($order): void
    {
        // 当前订单是否存在和使用积分抵扣
        $isExistPointsDeduction = $this->isExistPointsDeduction($order);
        // 订单商品列表
        $goodsList = [];
        foreach ($order['goodsList'] as $goods) {
            /* @var GoodsModel $goods */
            $item = [
                'user_id' => $this->user['user_id'],
                'store_id' => $this->storeId,
                'goods_id' => $goods['goods_id'],
                'goods_name' => $goods['goods_name'],
                'goods_no' => $goods['goods_no'] ?: '',
                'image_id' => (int)current($goods['goods_images'])['file_id'],
                'deduct_stock_type' => $goods['deduct_stock_type'],
                'spec_type' => $goods['spec_type'],
                'goods_sku_id' => $goods['skuInfo']['goods_sku_id'],
                'goods_props' => $goods['skuInfo']['goods_props'] ?: '',
                'content' => $goods['content'] ?? '',
                'goods_sku_no' => $goods['skuInfo']['goods_sku_no'] ?: '',
                'goods_price' => $goods['skuInfo']['goods_price'],
                'line_price' => $goods['skuInfo']['line_price'],
                'goods_weight' => $goods['skuInfo']['goods_weight'],
                'is_user_grade' => (int)$goods['is_user_grade'],
                'grade_ratio' => $goods['grade_ratio'],
                'grade_goods_price' => $goods['grade_goods_price'],
                'grade_total_money' => $goods['grade_total_money'],
                'coupon_money' => $goods['coupon_money'],
                'points_money' => $isExistPointsDeduction ? $goods['points_money'] : 0.00,
                'points_num' => $isExistPointsDeduction ? $goods['points_num'] : 0,
                'points_bonus' => $goods['points_bonus'],
                'total_num' => $goods['total_num'],
                'total_price' => $goods['total_price'],
                'total_pay_price' => $goods['total_pay_price']
            ];
            // 记录订单商品来源id
            $item['goods_source_id'] = isset($goods['goods_source_id']) ? $goods['goods_source_id'] : 0;
            $goodsList[] = $item;
        }
        $this->model->goods()->saveAll($goodsList) !== false;
    }

    /**
     * 更新商品库存 (针对下单减库存的商品)
     * @param $order
     * @return void
     */
    private function updateGoodsStockNum($order): void
    {
        StockFactory::getFactory($this->model['order_source'])->updateGoodsStock($order['goodsList']);
    }

    /**
     * 记录收货地址
     * @param $address
     * @return void
     */
    private function saveOrderAddress($address): void
    {
        $this->model->address()->save([
            'user_id' => $this->user['user_id'],
            'store_id' => $this->storeId,
            'name' => $address['name'],
            'phone' => $address['phone'],
            'province_id' => $address['province_id'],
            'city_id' => $address['city_id'],
            'region_id' => $address['region_id'],
            'detail' => $address['detail'],
        ]);
    }

    /**
     * 创建订单
     */
    public function addorder($userid, $goodsid, $orderNo, $payType,$goodssum)
    {

        //根据商品ID查询商品信息
        $goodlist = Db::name('goods')
            ->alias('g')
            ->field('g.*,gs.*,gi.image_id as gimageid')
            ->where('g.goods_id', $goodsid)
            ->join('yoshop_goods_image gi', 'g.goods_id=gi.goods_id')
            ->join('goods_sku gs', 'g.goods_id=gs.goods_id')
            ->cache('new_buy',300)
            ->find();

        $boxMoneys = $goodlist['goods_price_min'] * $goodssum;

        //填充需要的数据
        $goodsarr = [
            'total_price'       => $boxMoneys,
            'order_price'       => $boxMoneys,
            'pay_price'         => $boxMoneys,
            'pay_status'        => 10,//默认为未付款
            'order_status'      => 10,//默认为进行中
            'user_id'           => $userid,
            'store_id'          => 10001,
            'create_time'       => time(),
            'goods_id'          => $goodsid,
            'goods_sum'         => $goodssum,
            'order_no'          => $orderNo,
            'points_bonus'      => $goodlist['goods_price_max'], //赠送的积分数量
            'is_box'            => 0   ,
            'pay_type'          => $payType,
        ];

        $order_info = OrderModel::create($goodsarr); //创建订单

        $goodsarr['order_id']   = $order_info['order_id'];

        //将数据插入到商品和订单的关系表中
        $orderGoods = [
            'goods_id'          => $goodsid,
            'goods_name'        => $goodlist['goods_name'],
            'image_id'          => $goodlist['gimageid'],
            'deduct_stock_type' => 10,
            'spec_type'         => 10,
            'goods_sku_id'      => 0,
            'content'           => $goodlist['content'],
            'goods_price'       => $goodlist['goods_price_max'],
            'total_num'         => 1,
            'total_price'       => $goodlist['goods_price_max'],
            'total_pay_price'   => $goodlist['goods_price_max'],
            'order_id'          => $order_info['order_id'],
            'user_id'           => $userid,
            'create_time'       => time()
        ];

        //将数据填充到商品订单关系表中
        Db::name('order_goods')
            ->insert($orderGoods);

        //针对下单减库存
        if ($goodlist['deduct_stock_type'] == 10){
            //创建订单后立刻库存-1
            $res1 = Db::name('goods_sku')
                ->where('goods_id', $goodsid)
                ->dec('stock_num',intval($goodssum))
                ->update();
            $res2 = Db::name('goods')
                ->where('goods_id', $goodsid)
                ->dec('stock_total',intval($goodssum))
                ->update();

            if(!$res1 || !$res2){

                return false;
            }

        }
        return $goodsarr;

    }
//    public function addorder($userid, $goodsid, $orderNo, $isBox, $boxMoneys, $payType,$blind_id=0,$goodssum)
//    {
//        //设置默认的商品数量
//
//        //判断生成的订单是商品还是盲盒
//        if ($isBox == 1) {
//            //根据概率随机盲盒
//           /* $probabilitys = Goods::where(['is_box' => 1,'is_delete' => 0,'status' => 10])->column('probability');
//            $goods = Goods::where(['is_box' => 1,'is_delete' => 0,'status' => 10])->select()->toArray();
//            if (empty($goods)){
//                return 110;
//            }
//            $key = get_rand($probabilitys);
//            $good = $goods[$key];*/
//
//            //根据商品ID查询商品信息
//            $good = Db::name('goods')
//                ->alias('g')
//                ->where('g.goods_id', $goodsid)
//                ->join('goods_sku gs', 'g.goods_id=gs.goods_id')
//                ->find();
//
//            //如果库存=0 将产品的状态改为下架
//            if (empty($good['stock_num'])) {
//                /*$goodsType = Db::name('goods')
//                    ->where('goods_id', $goodsid)
//                    ->update(['status' => 20]);*/
//                //并且返回错误状态
//                return 110;
//            }
//
//            //填充需要的数据
//            $goodsarr = [
//                'blind_id'  => $blind_id,
//                'total_price' => $boxMoneys,
//                'order_price' => $boxMoneys,
//                'pay_status' => 10,//默认为未付款
//                'order_status' => 10,//默认为进行中
//                'user_id' => $userid,
//                'store_id' => 10001,
//                'create_time' => time(),
//                'goods_id' => $good['goods_id'],
//                'goods_sum' => $goodssum,
//                'order_no' => $orderNo,
//                'points_bonus' => $boxMoneys, //赠送的积分数量
//                'is_box' => $isBox,
//                'pay_type' => $payType
//            ];
//            $orderId  = OrderModel::create($goodsarr);  //新增订单
//
//            $goodsarr['order_id'] = $orderId['order_id'];
//
//            //根据订单编号查询订单ID
//            $orderId = Db::name('order')
//                ->where('order_no', $orderNo)
//                ->find();
//
//            //根据商品id获取商品信息
//            $goodsList = Db::name('goods')
//                ->alias('g')
//                ->join('yoshop_goods_image gi', 'g.goods_id=gi.goods_id')
//                ->where('g.goods_id', $good['goods_id'])
//                ->find();
//
//            //查询购买盲盒的价格
//            $box_price = Setting::getItem('box');
//            $boxMach = $boxMoneys;
//            //填充需要的数据
//            $orderGoods = [
//                'goods_id' => $good['goods_id'],
//                'goods_name' => $goodsList['goods_name'],
//                'image_id' => $goodsList['image_id'],
//                'deduct_stock_type' => 10,
//                'spec_type' => 10,
//                'goods_sku_id' => 0,
//                'content' => $goodsList['content'],
//                'goods_price' => $boxMach,
//                'total_num' => 1,
//                'total_price' => $boxMach,
//                'total_pay_price' => $boxMach,
//                'order_id' => $orderId['order_id'],
//                'user_id' => $userid,
//                'create_time' => time()
//            ];
//            //将数据填充到商品订单关系表中
//            $insert = Db::name('order_goods')
//                ->insert($orderGoods);
//
//            return $goodsarr;
//        } else {
//            //根据商品ID查询商品信息
//            $goodlist = Db::name('goods')
//                ->alias('g')
//                ->where('g.goods_id', $goodsid)
//                ->join('goods_sku gs', 'g.goods_id=gs.goods_id')
//                ->find();
//
//            //如果库存=0 将产品的状态改为下架
//            if (empty($goodlist['stock_num'])) {
//                /*$goodsType = Db::name('goods')
//                    ->where('goods_id', $goodsid)
//                    ->update(['status' => 20]);*/
//                //并且返回错误状态
//                return 110;
//            }
//
//            //填充需要的数据
//            $goodsarr = [
//                'total_price' => $boxMoneys,
//                'order_price' => $boxMoneys,
//                'pay_price' => $boxMoneys,
//                'pay_status' => 10,//默认为未付款
//                'order_status' => 10,//默认为进行中
//                'user_id' => $userid,
//                'store_id' => 10001,
//                'create_time' => time(),
//                'goods_id' => $goodsid,
//                'goods_sum' => $goodssum,
//                'order_no' => $orderNo,
//                'points_bonus' => $goodlist['goods_price_max'], //赠送的积分数量
//                'is_box' => $isBox,
//                'pay_type' => $payType
//            ];
//
//            OrderModel::create($goodsarr); //创建订单
//
//            //根据订单编号查询订单ID
//            $orderId = Db::name('order')
//                ->where('order_no', $orderNo)
//                ->find();
//
//            $goodsarr['order_id']   = $orderId['order_id'];
//
//            //根据商品id获取商品信息
//            $goodsList = Db::name('goods')
//                ->alias('g')
//                ->join('yoshop_goods_image gi', 'g.goods_id=gi.goods_id')
//                ->where('g.goods_id', $goodsid)
//                ->find();
//            //将数据插入到商品和订单的关系表中
//            $orderGoods = [
//                'goods_id' => $goodsid,
//                'goods_name' => $goodsList['goods_name'],
//                'image_id' => $goodsList['image_id'],
//                'deduct_stock_type' => 10,
//                'spec_type' => 10,
//                'goods_sku_id' => 0,
//                'content' => $goodsList['content'],
//                'goods_price' => $goodsList['goods_price_max'],
//                'total_num' => 1,
//                'total_price' => $goodsList['goods_price_max'],
//                'total_pay_price' => $goodsList['goods_price_max'],
//                'order_id' => $orderId['order_id'],
//                'user_id' => $userid,
//                'create_time' => time()
//            ];
//            //将数据填充到商品订单关系表中
//            $insert = Db::name('order_goods')
//                ->insert($orderGoods);
//            //针对下单减库存
//            if ($goodsList['deduct_stock_type'] == 10){
//                //创建订单后立刻库存-1
//                Db::name('goods_sku')
//                    ->where('goods_id', $goodsid)
//                    ->dec('stock_num',intval($goodssum))
//                    ->update();
//                Db::name('goods')
//                    ->where('goods_id', $goodsid)
//                    ->dec('stock_total',intval($goodssum))
//                    ->update();
//            }
//            return $goodsarr;
//        }
//    }

    /**
     * 查询剩余库存
     */
    public function goodsarr($goodsid)
    {
        $goodsnum = Db::name('goods_sku')
            ->field('stock_num')
            ->where('goods_id', $goodsid)
            ->find();
        return $goodsnum;
    }

    /**
     * 未支付，调用。订单作废，库存+1
     */
    public function noPayment($goodsid)
    {
        //库存+1
        $goodssku = Db::name('goods_sku')
            ->where('goods_id', $goodsid)
            ->inc('stock_num')
            ->update();
        return 0;
    }


    /**
     * 写入藏品表
     */
    public function addCollec($userid, $goodsid, $ordersNo)
    {
        //根据商品id查询商品名称
        $goodsname = Db::name('goods')
            ->where('goods_id', $goodsid)
            ->field('goods_name')
            ->select()->toArray();
        $config = Integrals::find();
        $time = $config['setday'] * 86400;  //转赠时间
        $collecList = [
            'goods_id' => $goodsid,
            'goods_name' => $goodsname[0]['goods_name'],
            'user_id' => $userid,
            'order_no' => $ordersNo,
            'addtime' => time(),
            'zztime' => time() + $time,
            'coll_no' => random(8)
        ];
        $addcollec = Db::name('coll')->insert($collecList);

        if ($addcollec != 1) {
            return false;
        }
        return true;

    }

    /**
     * 支付完成 修改订单编号
     * orders_no  订单编号
     */
    public function orderup($orders_no)
    {
        //调用后接口直接变为完成支付
        $ordersup = Db::name('order')
            ->where('order_no', $orders_no)
            ->update(['pay_status' => 20]);

        if ($ordersup == 1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 状态完成接口
     */
    public function orderType($orderNo)
    {
        //将订单改为已完成
        $orderType = Db::name('order')
            ->where('order_no', $orderNo)
            ->update('order_status');
        if ($orderType == 1) {
            return true;
        }
        return false;

    }


    /**
     * 支付成功之后该商品减1
     */
    public function goodsDec($goodsid)
    {
        //根据获取到的商品ID修改商品的库存
//        $goodsk=Db::name('goods')
//            ->where('goods_id',$goodsid)
//            ->dec('stock_total')
//            ->update();
        //现有库存 -1
        $newgoodskc = Db::name('goods_sku')
            ->where('goods_id', $goodsid)
            ->dec('stock_num')
            ->update();

        //查询现有库存
        $stock = Db::name('goods_sku')
            ->field('stock_num')
            ->where('goods_id', $goodsid)
            ->find();

        if ($newgoodskc == 0) {
            return false;
        }
        return $stock;
    }

    /**
     * 每次购买后调用积分接口添加积分
     */
    public function points($userid, $pointsbonus, $ordersup)
    {
        //首先判断是否支付完成
        if (!$ordersup) {
            return false;
        }
        //将价格转为整数,变成积分
        $pointsbonus = round($pointsbonus);
        //支付完成后添加积分
        $point = Db::name('user')
            ->where('user_id', $userid)
            ->inc('pointsall', $pointsbonus)
            ->inc('points', $pointsbonus)
            ->update();

        //记录积分变动情况
        $arr = [
            'user_id' => $userid,
            'value' => $pointsbonus,
            'describe' => '购买藏品',
            'create_time' => time()
        ];
        $userPoint = Db::name('user_points_log')
            ->insert($arr);


        if ($point == 0 or $userPoint) {
            return false;
        }
        return true;
    }


    /**
     * 每次购买后调用积分接口添加积分
     */
    public function pointss($userid, $pointsbonus, $ordersup)
    {
        //首先判断是否支付完成
        if (!$ordersup) {
            return false;
        }
        //将价格转为整数,变成积分
        $pointsbonus = round($pointsbonus);
        //支付完成后添加积分
        $point = Db::name('user')
            ->where('user_id', $userid)
            ->inc('pointsall', $pointsbonus)
            ->inc('points', $pointsbonus)
            ->update();

        //记录积分变动情况
        $arr = [
            'user_id' => $userid,
            'value' => $pointsbonus,
            'describe' => '购买藏品',
            'create_time' => time()
        ];
        $userPoint = Db::name('user_points_log')
            ->insert($arr);


        if ($point == 0 or $userPoint) {
            return false;
        }
        return true;
    }


    /**
     * 查询我的订单，模型层
     */
    public function myOrderFind($orderType,int $listRows = 15)
    {
        $userId = UserService::getCurrentLoginUserId();

        $status = [];

        if(!empty($orderType['order_status'])){

            switch ($orderType['order_status']){
                case 10:
                    $status['o.order_status'] = 10;
                    break;
                case 30:
                    $status['o.order_status'] = 30;
                    break;
                case 20:
                    $status['o.order_status'] = 20;
                    break;
            }
        }

        if(isset($orderType['is_delete']) && $orderType['is_delete'] > 0){

            $is_delete = 1;

        }else{

            $is_delete = 0;

        }
        $type = [];
        // 待付款显示二级市场和一级市场所有订单
        if(isset($orderType['order_status']) && ($orderType['order_status'] != 10)) {
            // 我的转卖订单列表
            if(isset($orderType['type']) && $orderType['type']) {
                if(1 == $orderType['type']) {
                    $type['type'] = 0;
                }else{
                    $type['type'] = 1;
                }
            }
        }

        // 无条件全部订单
        if(isset($orderType['order_status']) && ($orderType['order_status'] == '')) {
            $orderList = DB::name('order')
                    ->distinct(true)
                    ->alias('o')
                    ->leftJoin('yoshop_order_goods yog', 'o.order_id=yog.order_id')
                    ->leftJoin('yoshop_upload_file yuf', 'yog.image_id=yuf.file_id')
                    ->join('yoshop_coll co', 'co.order_no = o.order_no')
                    ->join('yoshop_goods_sn sn', 'sn.coll_id = co.coll_id')
                    ->leftJoin('yoshop_goods go', 'go.goods_id = o.goods_id') 
                    ->field('o.order_id,o.order_no,o.goods_id as g_id,go.xn_sale,sn.number,o.create_time,o.total_price,yog.goods_name,yuf.file_path,o.pay_status,o.is_box,o.order_status,o.pay_type,yog.goods_id,o.type,o.transaction_id,o.is_delete')
                    ->where('o.user_id', $userId)
                    ->order('o.create_time', 'desc')
                    ->paginate($listRows)->toArray();
        }else{
            $orderList = DB::name('order')
                    ->distinct(true)
                    ->alias('o')
                    ->leftJoin('yoshop_order_goods yog', 'o.order_id=yog.order_id')
                    ->leftJoin('yoshop_upload_file yuf', 'yog.image_id=yuf.file_id')
                     ->join('yoshop_coll co', 'co.order_no = o.order_no')
                    ->join('yoshop_goods_sn sn', 'sn.coll_id = co.coll_id')
                    ->leftJoin('yoshop_goods go', 'go.goods_id = o.goods_id') 
                    ->field('o.order_id,o.order_no,o.goods_id as g_id,go.xn_sale,sn.number,o.create_time,o.total_price,yog.goods_name,yuf.file_path,o.pay_status,o.is_box,o.order_status,o.pay_type,yog.goods_id,o.type,o.transaction_id,o.is_delete')
                    ->where('o.user_id', $userId)
                    ->where('o.is_delete','=',$is_delete)
                    ->where($status)
                    ->where($type)
                    ->order('o.create_time', 'desc')
                    ->paginate($listRows)->toArray();
        }

        $not_cofig = \app\store\model\Setting::getItem('box',10001)['price'];

        foreach ($orderList['data'] as $k => &$v) {

            $not_time = $v['create_time'] + 60*$not_cofig;

            $v['not_time'] = $not_time-time();

            //如果盲盒是待付款状态则不输出
            if ($v['is_box'] == 1 && $v['pay_status'] == 10 && $v['order_status'] == 10) {
                unset($orderList[$k]);
            }
            $v['goods_name'] = Goods::where('goods_id',$v['g_id'])->value('goods_name');
            $image_id = GoodsImage::with('file')->where('goods_id',$v['g_id'])->find();
            //if ($v['type'] == 1){
            //    $v['file_path'] = $image_id['file']['file_path'];
            //}else{

                $v['file_path'] = $image_id['file']['preview_url'];
            //}
        }
        return $orderList;
    }

    /**
     * 查询我的积分
     */
    public function myIntegralFind($userid)
    {
        //查询剩余积分
        $myIntegralFind = Db::name('user')
            ->field('points')
            ->where('user_id', $userid)
            ->find();
        $myIntegral = $myIntegralFind['points'];
        //查询积分变动明细
        $myIntegralArr = Db::name('user_points_log')
            ->field('value,describe,create_time')
            ->where('user_id', $userid)
            ->order('create_time', 'desc')
            ->select()
            ->toArray();


        //返回所有数据
        $integralArr = [
            'integralAll' => $myIntegral,
            'integralDetails' => $myIntegralArr,
        ];

        return $integralArr;
    }

    /**
     * 空投模型
     */
    public function airdropNew($goodsid)
    {
        //根据商品ID给所有用户添加商品，生成记录
        //查询所有用户ID
        $userList = Db::name('user')
            ->field('user_id')
            ->select()->toArray();

        $goodsnew = [];

        //将这个数组循环遍历到新增的数组中
        foreach ($userList as $key => $userarr) {
            $goodsnew[$key]['user_id'] = $userarr['user_id'];
            $goodsnew[$key]['goods_id'] = $goodsid;
            $goodsnew[$key]['addtime'] = time();
            $goodsnew[$key]['order_no'] = 0;
        }

        $addcp = DB::name('coll')->insertAll($goodsnew);

        if ($addcp == 0) {
            return null;
        }
        return $addcp;
    }


    /**
     * 查询我的推广二维码和我推广的人
     */
    public function extenImg($userid)
    {
        //我的推广二维码
        $myExtenImg = Db::name('user')
            ->where('user_id', $userid)
            ->field('extensionImg')
            ->find();

        //查询我推荐的用户
        // $myExtenMan=Db::name('user')
        // ->where('extension_id',$userid)
        // ->field('user_id,nick_name,create_time')
        // ->select()
        // ->toArray();

//        foreach($myExtenMan as $key => $mans){
//            $manArr[$key]=$mans['user_id'];
//        }

         $myExtenMan = Db::name('user')
            ->alias('u')
            ->leftJoin('upload_file uf', 'u.avatar_id = uf.file_id')
            ->where('u.extension_id', $userid)
            ->field('u.user_id,u.nick_name,u.idcar_id,u.create_time,uf.file_path as head_path')
            ->select()
            ->toArray();
            
        $arr = [
            'myExtenImg' => $myExtenImg,
            'myExtenMan' => $myExtenMan
        ];


        return $arr;
    }

    /**
     * 根据订单编号查询用户ID和商品ID
     */
    public function chaUserId($orderno)
    {
        $list = Db::name('order')
            ->where('order_no', $orderno)
            ->field('user_id,goods_id')
            ->select()->toArray();
        return $list;
    }

    /**
     * 查询盲盒价格
     */
    public function boxMoneys()
    {
        $boxmoney = Db::name('integrals')
            ->where('id', 1)
            ->field('box_money')
            ->find();
        return $boxmoney;
    }

    /**
     * 根据商品订单号查询订单价格
     **/
    public function getOrder($orderNo)
    {
        $total_price = Db::name('order')->field('total_price,pay_type')->where('order_no', $orderNo)->find();
        return $total_price;
    }

}
