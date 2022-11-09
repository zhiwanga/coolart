<?php
declare (strict_types = 1);

namespace app\model\market;

use think\facade\Db;
use think\Model;

/**
 * 对接第三方实时商品
 */
class RealtGoods extends Model
{
    protected $name = 'realt_goods';

    /**
     * 对接第三方信息（新加|上架|修改 商品时需检查是否上架）
     * @param [type] $goodsId
     * @param string $order_no 购买时会有订单号（新加|修改|上架，操作可不传订单号和价格）
     * @param integer $price 购买时会有价格
     */
    public static function add($goodsId, $order_no = 0, $price = 0)
    {
        $time = time();
        $info = Db::name('goods')
                    ->alias('g')
                    ->join(['yoshop_goods_image'=>'gi'],'g.goods_id=gi.goods_id')
                    ->join(['yoshop_upload_file'=>'uf'],'gi.image_id=uf.file_id')
                    ->field('g.goods_name, g.goods_price_min, g.xn_sale, g.startTime, g.status, uf.file_path')
                    ->where('g.goods_id', $goodsId)
                    ->where('g.status', 10) // 上架的商品
                    ->find();

        $res = RealtGoods::where('goods_id', $goodsId)->find();
        if($res) {
            $update = [
                'order_no'       => $order_no,              // 订单号，用于追溯商品
                'price'          => $price,                 // 购买价格
            ];
            RealtGoods::where('goods_id', $goodsId)->update($update);
        }else{
            $insert = [
                'goods_id'       => $goodsId,                   // 商品ID
                'order_no'       => $order_no,                  // 订单号，用于追溯商品
                'name'           => $info['goods_name'],        // 商品名称
                'image'          => 'https://coolart.space/uploads/'.$info['file_path'],     // 图片地址
                'price'          => $info['goods_price_min'],   // 购买价格
                'circula_number' => $info['xn_sale'],           // 市场流通数量
                'type'           => 0,                          // 交易类型1/0 (1表示可以寄售)
                'sale_time'      => $info['startTime'],         // 预售时间
                'sale_number'    => $info['xn_sale'],           // 发售数量
                'create_time'    => $time
            ];
            RealtGoods::insert($insert);
        }
    }
}
