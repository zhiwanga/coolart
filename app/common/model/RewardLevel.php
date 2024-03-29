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
declare (strict_types = 1);

namespace app\common\model;

use app\common\model\user\Grade;
use cores\BaseModel;
use app\common\library\helper;
use app\common\enum\page\PageType as PageTypeEnum;

/**
 * 店铺页面模型
 * Class Page
 * @package app\common\model
 */
class RewardLevel extends BaseModel
{
    // 定义表名
    protected $name = 'reward_level';

    // 定义主键
    protected $pk = 'grade_id';

    /**
     * 获取器：升级条件
     * @param $json
     * @return mixed
     */
    public function getUpgradeAttr($json)
    {
        return helper::jsonDecode($json);
    }

//    /**
//     * 获取器：等级权益
//     * @param $json
//     * @return mixed
//     */
//    public function getEquityAttr($json)
//    {
//        return helper::jsonDecode($json);
//    }
//
//    /**
//     * 修改器：升级条件
//     * @param $data
//     * @return mixed
//     */
//    public function setUpgradeAttr($data)
//    {
//        // 这里需要转换下, 否则会出现浮点数精度丢失问题
//        $data['expend_money'] = (string)$data['expend_money'];
//        return helper::jsonEncode($data);
//    }

    /**
     * 修改器：等级权益
     * @param $data
     * @return mixed
     */
    public function setEquityAttr($data)
    {
        // 这里需要转换下, 否则会出现浮点数精度丢失问题
        $data['discount'] = (string)$data['discount'];
        return helper::jsonEncode($data);
    }

    /**
     * 会员等级详情
     * @param int $gradId
     * @param array $with
     * @return array|null|static
     */
    public static function detail(int $gradId, array $with = [])
    {
        return static::get($gradId, $with);
    }

    /**
     * 验证等级权重是否存在
     * @param int $weight 验证的权重
     * @param int $gradeId 自身的等级ID
     * @return bool
     */
    public static function checkExistByCategoryId(int $categoryId, int $gradeId = 0): bool
    {
        $filter = [];
        $gradeId > 0 && $filter[] = ['grade_id', '<>', (int)$gradeId];
        return !!(new static)->where('category_id', '=', (int)$categoryId)
            ->where($filter)
            ->where('is_delete', '=', 0)
            ->value('grade_id');
    }
}