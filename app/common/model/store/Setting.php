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

namespace app\common\model\store;

use app\common\model\Goods;
use app\common\model\UploadFile;
use think\facade\Cache;
use app\common\library\helper;
use cores\BaseModel;
use app\common\enum\Setting as SettingEnum;
use app\common\enum\file\Storage as StorageEnum;
use app\common\enum\order\DeliveryType as DeliveryTypeEnum;
use app\common\enum\setting\sms\Scene as SettingSmsSceneEnum;
use app\common\enum\store\page\category\Style as PageCategoryStyleEnum;

/**
 * 系统设置模型
 * Class Setting
 * @package app\common\model
 */
class Setting extends BaseModel
{
    // 定义表名
    protected $name = 'store_setting';

    /**
     * 获取器: 转义数组格式
     * @param $value
     * @return mixed
     */
    public function getValuesAttr($value)
    {
        return helper::jsonDecode($value);
    }

    /**
     * 修改器: 转义成json格式
     * @param $value
     * @return string
     */
    public function setValuesAttr($value): string
    {
        return helper::jsonEncode($value);
    }

    /**
     * 获取指定项设置
     * @param string $key
     * @param int|null $storeId
     * @return array|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getItem(string $key, int $storeId = null)
    {
        $data = self::getAll($storeId);
        if ($key == 'poster' || $key == 'box_bg'){
            $file = UploadFile::where('file_id',$data[$key]['values']['file_id'])->find();
            if (empty($file['domain'])){
                $data[$key]['values']['file_path'] = base_url() . 'uploads/' . $file['file_path'];
            }else{
                $data[$key]['values']['file_path'] = $file['domain'] . '/' . $file['file_path'];
            }
        }
//        if ($key == 'setting'){
//            $goodsId = $data[$key]['values']['goodsId'];
//            $goods = Goods::with(['images.file'])->where('goods_id',$goodsId)->field('goods_id,goods_name,sales_initial,sales_actual')->find()->toArray();
//            $data[$key]['values']['goods_name'] = $goods['goods_name'];
//            $data[$key]['values']['goods_images'] = $goods['images'][0]['file']['preview_url'];
//        }
        return isset($data[$key]) ? $data[$key]['values'] : [];
    }

    /**
     * 获取设置项信息
     * @param string $key
     * @param int|null $storeId
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function detail(string $key, int $storeId = null)
    {
        $query = (new static)->getNewQuery();
        $storeId > 0 && $query->where('store_id', '=', $storeId);
        return $query->where('key', '=', $key)->find();
    }

    /**
     * 全局缓存: 系统设置
     * @param int|null $storeId
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getAll(int $storeId = null): array
    {
        $model = new static;
        is_null($storeId) && $storeId = static::$storeId;
        if (!$data = Cache::get("setting_{$storeId}")) {
            // 获取商城设置列表
            $setting = $model->getList($storeId);
            $data = $setting->isEmpty() ? [] : helper::arrayColumn2Key($setting->toArray(), 'key');
            // 写入缓存中
            Cache::tag('cache')->set("setting_{$storeId}", $data);
        }
        // 合并默认设置
        return array_merge_multiple($model->defaultData(), $data);
    }

    /**
     * 获取商城设置列表
     * @param $storeId
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function getList(int $storeId=10001)
    {
        return $this->where('store_id', '=', $storeId)->select();
    }

    /**
     * 默认配置
     * @return array
     */
    public function defaultData(): array
    {
        return [
            // 配送设置
            SettingEnum::DELIVERY => [
                'key' => SettingEnum::DELIVERY,
                'describe' => '配送设置',
                'values' => [
                    // 配送方式
                    'delivery_type' => array_keys(DeliveryTypeEnum::data()),
                    // 快递100
                    'kuaidi100' => [
                        'customer' => '',
                        'key' => '',
                    ]
                ],
            ],
            // 交易设置
            SettingEnum::TRADE => [
                'key' => SettingEnum::TRADE,
                'describe' => '交易设置',
                'values' => [
                    // 订单流程设置
                    'order' => [
                        // 'close_days' => '3',    // 已废弃
                        'closeHours' => 3 * 24,    // 未支付订单自动关闭期限(单位:小时)
                        'receive_days' => '10',
                        'refund_days' => '7'
                    ],
                    // 运费组合策略
                    'freight_rule' => '10',
                ]
            ],
            // 上传设置
            SettingEnum::STORAGE => [
                'key' => SettingEnum::STORAGE,
                'describe' => '上传设置',
                'values' => [
                    'default' => StorageEnum::LOCAL,
                    'engine' => [
                        StorageEnum::LOCAL => null,
                        StorageEnum::QINIU => [
                            'bucket' => '',
                            'access_key' => '',
                            'secret_key' => '',
                            'domain' => 'http://'
                        ],
                        StorageEnum::ALIYUN => [
                            'bucket' => '',
                            'access_key_id' => '',
                            'access_key_secret' => '',
                            'domain' => 'http://'
                        ],
                        StorageEnum::QCLOUD => [
                            'bucket' => '',
                            'region' => '',
                            'secret_id' => '',
                            'secret_key' => '',
                            'domain' => 'http://'
                        ],
                    ]
                ],
            ],
            // 短信通知
            SettingEnum::SMS => [
                'key' => SettingEnum::SMS,
                'describe' => '短信通知',
                'values' => [
                    'default' => 'aliyun',
                    // 短信服务渠道
                    'engine' => [
                        // 阿里云
                        'aliyun' => [
                            'name' => '阿里云短信',
                            'website' => 'https://dysms.console.aliyun.com/dysms.htm',
                            'AccessKeyId' => '',
                            'AccessKeySecret' => '',
                            'sign' => '萤火商城'   // 短信签名
                        ],
                        // 腾讯云
                        'qcloud' => [
                            'name' => '腾讯云短信',
                            'website' => 'https://console.cloud.tencent.com/smsv2',
                            'SdkAppID' => '',
                            'AccessKeyId' => '',
                            'AccessKeySecret' => '',
                            'sign' => '萤火商城'   // 短信签名
                        ],
                        // 七牛云
                        'qiniu' => [
                            'name' => '七牛云短信',
                            'website' => 'https://portal.qiniu.com/sms/dashboard',
                            'AccessKey' => '',
                            'SecretKey' => '',
                            'sign' => '萤火商城'   // 短信签名
                        ],
                    ],
                    // 短信通知场景
                    'scene' => [
                        // 短信验证码
                        SettingSmsSceneEnum::CAPTCHA => [
                            'name' => '短信验证码 (通知用户)',    // 场景名称
                            'isEnable' => false,     // 是否开启
                            'templateCode' => '',    // 模板ID
                            'content' => '验证码%s，您正在进行身份验证，打死不要告诉别人哦！',
                            'variables' => [
                                'aliyun' => ['${code}'],
                                'qiniu' => ['${code}'],
                                'qcloud' => ['{1}'],
                            ]
                        ],
                        // 新付款订单
                        SettingSmsSceneEnum::ORDER_PAY => [
                            'name' => '新付款订单 (通知商家)',   // 场景名称
                            'isEnable' => false,    // 是否开启
                            'templateCode' => '',   // 模板ID
                            'acceptPhone' => '',    // 接收手机号
                            'content' => '您有一条新订单，订单号为：%s，请注意查看',
                            'variables' => [
                                'aliyun' => ['${order_no}'],
                                'qiniu' => ['${order_no}'],
                                'qcloud' => ['{1}'],
                            ]
                        ],
                    ]
                ],
            ],
            // 满额包邮设置
            SettingEnum::FULL_FREE => [
                'key' => SettingEnum::FULL_FREE,
                'describe' => '满额包邮设置',
                'values' => [
                    'is_open' => 0,     // 是否开启满额包邮
                    'money' => '',      // 单笔订单额度
                    'excludedRegions' => [ // 不参与包邮的地区
                        'cityIds' => [],        // 城市ID集
                        'selectedText' => ''    // 选择的地区(文字)
                    ],
                    'excludedGoodsIds' => [],   // 不参与包邮的商品 (ID集)
                    'describe' => ''            // 满额包邮说明
                ],
            ],
            // 账户注册设置
            SettingEnum::REGISTER => [
                'key' => SettingEnum::REGISTER,
                'describe' => '账户注册设置',
                'values' => [
                    'registerMethod' => 10,   // 默认注册方式: 10=>手机号+短信验证码
                    'isOauthMpweixin' => 1,   // 是否开启微信小程序一键授权登录
                    'isForceBindMpweixin' => 1,   // 客户端是否强制绑定手机号(微信小程序)
                    'isManualBind' => 1,   // 个人中心页显示手动绑定手机号
                ],
            ],
            // 用户充值设置
            SettingEnum::RECHARGE => [
                'key' => SettingEnum::RECHARGE,
                'describe' => '用户充值设置',
                'values' => [
                    'is_entrance' => 1,   // 是否允许用户充值
                    'is_custom' => 1,     // 是否允许自定义金额
                    'lowest_money' => 10,   // 最低充值金额
                    'is_match_plan' => 1,   // 自定义金额是否自动匹配合适的套餐
                    'describe' => "1. 账户充值仅限微信在线方式支付，充值金额实时到账；\n" .
                        "2. 账户充值套餐赠送的金额即时到账；\n" .
                        "3. 账户余额有效期：自充值日起至用完即止；\n" .
                        "4. 若有其它疑问，可拨打客服电话400-000-1234",     // 充值说明
                ],
            ],
            // 积分设置
            SettingEnum::POINTS => [
                'key' => SettingEnum::POINTS,
                'describe' => SettingEnum::data()[SettingEnum::POINTS]['describe'],
                'values' => [
                    'points_name' => '积分',         // 积分名称自定义
                    'is_shopping_gift' => 0,      // 是否开启购物送积分
                    'gift_ratio' => '100',          // 积分赠送比例
                    'is_shopping_discount' => 0,  // 是否允许下单使用积分抵扣
                    'discount' => [     // 积分抵扣
                        'discount_ratio' => '0.01',           // 积分抵扣比例
                        'full_order_price' => '100.00',       // 订单满[?]元
                        'max_money_ratio' => '10',            // 最高可抵扣订单额百分比
                    ],
                    // 积分说明
                    'describe' => "a) 积分不可兑现、不可转让,仅可在本平台使用;\n" .
                        "b) 您在本平台参加特定活动也可使用积分,详细使用规则以具体活动时的规则为准;\n" .
                        "c) 积分的数值精确到个位(小数点后全部舍弃,不进行四舍五入)\n" .
                        "d) 买家在完成该笔交易(订单状态为“已签收”)后才能得到此笔交易的相应积分,如购买商品参加店铺其他优惠,则优惠的金额部分不享受积分获取;",
                ],
            ],
            // 分类页模板
            SettingEnum::PAGE_CATEGORY_TEMPLATE => [
                'key' => SettingEnum::PAGE_CATEGORY_TEMPLATE,
                'describe' => '分类页模板设置',
                'values' => [
                    'style' => PageCategoryStyleEnum::TWO_LEVEL,    // 分类页样式
                    'shareTitle' => ''                              // 分享标题
                ]
            ]
        ];
    }
}
