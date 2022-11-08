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

namespace app\store\model;

use app\common\model\GoodsDebrisRel as GoodsDebrisRelModel;

/**
 * 商品与碎片关系模型
 * Class GoodsDebrisRel
 * @package app\store\model
 */
class GoodsDebrisRel extends GoodsDebrisRelModel
{
    public function goods()
    {
        return $this->hasOne('goods','goods_id','debris_id')->field('goods_id,goods_name,is_box,sales_initial,sales_actual');
    }
}