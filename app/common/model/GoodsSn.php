<?php


namespace app\common\model;


use cores\BaseModel;
use think\facade\Cache;

class GoodsSn extends BaseModel
{
    // 定义表名
    protected $name = 'goods_sn';

    // 定义主键
    protected $pk = 'id';

    public function setNumberAttr($value,$data){

        $get_number = Cache::get('g_num'.$data['goods_id']);

        if(empty($get_number)){

            //获取该商品的编号累加
            $get_number = self::where('goods_id',$data['goods_id'])->order('number desc')->value('number');

            $get_number = $get_number > 0 ? $get_number:1;

            Cache::set('g_num'.$data['goods_id'],$get_number);

        }else{

            $get_number = Cache::inc('g_num'.$data['goods_id']);

        }

        return $get_number;
    }

    public function goods(){

        return $this->hasOne('Goods','goods_id','goods_id');

    }
}
