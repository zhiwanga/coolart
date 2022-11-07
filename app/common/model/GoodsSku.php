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

use cores\BaseModel;
use app\common\library\helper;

/**
 * 商品SKU模型
 * Class GoodsSku
 * @package app\common\model
 */
class GoodsSku extends BaseModel
{
    // 定义表名
    protected $name = 'goods_sku';

    // 定义主键
    protected $pk = 'id';

    /**
     * 规格图片
     * @return \think\model\relation\HasOne
     */
    public function image()
    {
        return $this->hasOne('UploadFile', 'file_id', 'image_id');
    }

    /**
     * 获取器：规格值ID集
     * @param $value
     * @return array
     */
    public function getSpecValueIdsAttr($value)
    {
        return helper::jsonDecode($value);
    }

    /**
     * 获取器：规格属性
     * @param $value
     * @return array
     */
    public function getGoodsPropsAttr($value)
    {
        return helper::jsonDecode($value);
    }

    /**
     * 设置器：规格值ID集
     * @param $value
     * @return string
     */
    public function setSpecValueIdsAttr($value)
    {
        return helper::jsonEncode($value);
    }

    /**
     * 设置器：规格属性
     * @param $value
     * @return string
     */
    public function setGoodsPropsAttr($value)
    {
        return helper::jsonEncode($value);
    }

    /**
     * 获取sku信息详情
     * @param int $goodsId
     * @param string $goodsSkuId
     * @return array|null|static
     */
    public static function detail(int $goodsId, string $goodsSkuId)
    {
//        var_dump($goodsId);die;
        return static::get(['goods_id' => $goodsId, 'goods_sku_id' => $goodsSkuId]);
    }

}
