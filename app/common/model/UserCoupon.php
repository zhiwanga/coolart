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

namespace app\common\model;

use cores\BaseModel;

use app\common\library\helper;
use think\model\relation\BelongsTo;

/**
 * 用户优惠券模型
 * Class UserCoupon
 * @package app\common\model
 */
class UserCoupon extends BaseModel
{
    // 定义表名
    protected $name = 'user_coupon';

    // 定义主键
    protected $pk = 'user_coupon_id';

    /**
     * 追加字段
     * @var array
     */
    protected $append = ['state'];

    /**
     * 关联用户表
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo('User');
    }

    /**
     * 优惠券状态
     * @param $value
     * @param $data
     * @return array
     */
    public function getStateAttr($value, $data): array
    {
        if ($data['is_use']) {
            return ['text' => '已使用', 'value' => 0];
        }
        if ($data['is_expire']) {
            return ['text' => '已过期', 'value' => 0];
        }
        return ['text' => '正常', 'value' => 1];
    }

    /**
     * 获取器：格式化折扣率
     * @param $value
     * @return float|int
     */
    public function getDiscountAttr($value)
    {
        return $value / 10;
    }

    /**
     * 有效期-开始时间
     * @param $value
     * @return string
     */
    public function getStartTimeAttr($value): string
    {
        return date('Y/m/d', $value);
    }

    /**
     * 有效期-结束时间
     * @param $value
     * @return string
     */
    public function getEndTimeAttr($value): string
    {
        return date('Y/m/d', $value);
    }

    /**
     * 获取器：适用范围配置
     * @param $value
     * @return mixed
     */
    public function getApplyRangeConfigAttr($value)
    {
        return $value ? helper::jsonDecode($value) : [];
    }

    /**
     * 修改器：格式化折扣率
     * @param $value
     * @return float|int
     */
    public function setDiscountAttr($value)
    {
        return $value * 10;
    }

    /**
     * 修改器：适用范围配置
     * @param $array
     * @return mixed
     */
    public function setApplyRangeConfigAttr($array)
    {
        return helper::jsonEncode($array);
    }

    /**
     * 优惠券详情
     * @param $couponId
     * @return null|static
     */
    public static function detail($couponId)
    {
        return static::get($couponId);
    }

    /**
     * 设置优惠券使用状态
     * @param int $couponId 用户的优惠券id
     * @param bool $isUse 是否已使用
     * @return bool|false
     */
    public static function setIsUse(int $couponId, bool $isUse = true): bool
    {
        return static::updateBase(['is_use' => (int)$isUse], ['coupon_id' => $couponId]);
    }
}
