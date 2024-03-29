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

namespace app\common\enum;

/**
 * 商城设置枚举类
 * Class Setting
 * @package app\common\enum
 */
class Setting extends EnumBasics
{
    // 配送设置
    const DELIVERY = 'delivery';

    // 交易设置
    const TRADE = 'trade';

    // 短信通知
    const SMS = 'sms';

    // 上传设置
    const STORAGE = 'storage';

    // 满额包邮设置
    const FULL_FREE = 'full_free';

    // 账户注册设置
    const REGISTER = 'register';

    // 充值设置
    const RECHARGE = 'recharge';

    // 积分设置
    const POINTS = 'points';

    // 海报上传
    const POSTER = 'poster';

    // 盲盒设置
    const BOX = 'box';

    // 盲盒背景设置
    const BOX_BG = 'box_bg';

    // 系统设置
    const SETTING = 'setting';


    // 分类页模板
    const PAGE_CATEGORY_TEMPLATE = 'page_category_template';

    /**
     * 获取订单类型值
     * @return array
     */
    public static function data(): array
    {
        return [
            self::DELIVERY => [
                'value' => self::DELIVERY,
                'describe' => '配送设置',
            ],
            self::TRADE => [
                'value' => self::TRADE,
                'describe' => '交易设置',
            ],
            self::SMS => [
                'value' => self::SMS,
                'describe' => '短信通知',
            ],
            self::STORAGE => [
                'value' => self::STORAGE,
                'describe' => '上传设置',
            ],
            self::FULL_FREE => [
                'value' => self::FULL_FREE,
                'describe' => '满额包邮设置',
            ],
            self::REGISTER => [
                'value' => self::REGISTER,
                'describe' => '账户注册设置',
            ],
            self::RECHARGE => [
                'value' => self::RECHARGE,
                'describe' => '充值设置',
            ],
            self::POINTS => [
                'value' => self::POINTS,
                'describe' => '积分设置',
            ],
            self::POSTER => [
                'value' => self::POSTER,
                'describe' => '海报上传',
            ],
            self::BOX => [
                'value' => self::BOX,
                'describe' => '盲盒设置',
            ],
            self::PAGE_CATEGORY_TEMPLATE => [
                'value' => self::PAGE_CATEGORY_TEMPLATE,
                'describe' => '分类页模板',
            ],
            self::BOX_BG => [
                'value' => self::BOX_BG,
                'describe' => '盲盒背景图',
            ],
            self::SETTING => [
                'value' => self::SETTING,
                'describe' => '系统设置',
            ],
        ];
    }
}
