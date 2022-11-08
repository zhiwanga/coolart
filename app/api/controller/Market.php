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

namespace app\api\controller;
use think\facade\Db;

/**
 * 第三方
 * Class Goods
 * @package app\api\controller
 */
class market
{
    public function observationList()
    {
        $where = [
            'g.is_delete' => 0,
            'g.is_box' => 0,
        ];
        $data = Db::name('goods')
            ->alias('g')
            ->join(['yoshop_goods_image'=>'gi'],'g.goods_id=gi.goods_id')
            ->join(['yoshop_upload_file'=>'uf'],'gi.image_id=uf.file_id')
            ->field('g.goods_name, g.goods_price_min, uf.file_path')
            ->where($where)
            ->select()
            ->toArray();
        $result = [];
        foreach ($data as $k => $v) {
            $result[$k]['image'] = 'https://coolart.space/uploads/'.$v['file_path'];
            $result[$k]['name'] = $v['goods_name'];
            $result[$k]['price'] = $v['goods_price_min'];
        }

        return json($result);
    }
}
