<?php


namespace app\common\model;


use cores\BaseModel;

class Blind extends BaseModel
{

    protected $name = 'blind';

    protected $pk = 'id';

    public $append =[
        'goods_images',
    ];

    public function getContentAttr($value){

        return htmlspecialchars_decode($value);

    }

    public function setThumbAttr($value){

        if(is_array($value)){

            return implode(',',$value);

        }
    }

    public function getThumbAttr($value){

        if(is_array($value)){

            $value = implode(',',$value);
            
        }

        $file = UploadFile::where('file_id','in',$value)->find();

        return $file['preview_url'];
    }

    public function getGoodsImagesAttr($value,$data){

        if(!empty($data['thumb'])){

            return [
                UploadFile::where('file_id',$data['thumb'])->find()->toArray()
            ];

        }else{

            return [];
        }
    }

    /**
     * 随机获取商品id
     */
    public static function rand_goods($goods_ids){

        //商品详情
        $goods = Goods::with(['images.file'])
            ->where('goods_id','in',$goods_ids)
            ->where('stock_total','>',0)
            ->where('is_box',1)
            ->field('goods_id,goods_name,sales_initial,sales_actual,probability,goods_price_min,starttime')
            ->select();

        $arr = [];
        foreach($goods as $k=>&$g){
            $arr[$k] = $g['probability'];
        }
        unset($g);

        //抽奖
        $res = get_rand($arr);

        if(isset($goods[$res]['goods_id'])){
            return $goods[$res]['goods_id'];
        }else{
            return 0;
        }
    }
}