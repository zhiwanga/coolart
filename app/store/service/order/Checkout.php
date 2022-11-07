<?php


namespace app\store\service\order;


use app\api\model\Coll;
use app\api\model\GoodsImage;
use app\common\library\helper;
use app\common\model\Blind;
use app\common\model\Goods as GoodsModel;
use app\common\model\GoodsSn;
use app\common\model\Integrals;
use think\facade\Db;

class Checkout
{

    //计算中奖概率
    function get_rand($proArr) {


        $result = '';
        //概率数组的总概率精度
        $proSum = array_sum($proArr);
        //概率数组循环
        foreach ($proArr as $key => $proCur) {
            $randNum = mt_rand(1, $proSum);  //返回随机整数

            if ($randNum <= $proCur) {
                $result = $key;
                break;
            } else {
                $proSum -= $proCur;
            }
        }
        unset ($proArr);
        return $result;
    }

    /**
     * 空投盲盒
     */
    public function airdropBlind($blind_id,$user_ids=''){

        //查询所有用户ID
        $userList = Db::name('user')
            ->where('user_id','in',$user_ids)
            ->field('user_id')
            ->select()->toArray();

        $goodsnew = [];
        $config = Integrals::find();
        $time = $config['setday'] * 86400;  //转赠时间
        $blind = Blind::get($blind_id);

        if(empty($blind['goods_ids'])){

            return ['code' => 1,'message'=>'盲盒里面未找到商品'];

        }
        
        //将这个数组循环遍历到新增的数组中
        foreach ($userList as $key => $userarr) {

            //商品详情
            $goods_list = \app\common\model\Goods::with(['images.file'])
                ->where('goods_id','in',$blind['goods_ids'])
                ->where('stock_total','>',0)
                ->field('goods_id,goods_name,sales_initial,sales_actual,probability,goods_price_min')
                ->select();

            $arr_list = [];

            foreach($goods_list as $k=>&$g){

                $arr[$k] = $g['probability'];

            }unset($g);

            //抽奖
            $res = $this->get_rand($arr);

            $goods_id = $goods_list[$res]['goods_id'];

            $goodsnew[$key]['user_id']     = $userarr['user_id'];
            $goodsnew[$key]['goods_id']    = $goods_id;
            $goodsnew[$key]['create_time'] = time();
            $goodsnew[$key]['store_id']    = 10001;
            $goodsnew[$key]['blind_id']    = $blind_id;
            $goodsnew[$key]['type']        = 0;
            $goodsnew[$key]['is_shop']     = 1;
        }

        $addcp = DB::name('blind_log')->insertAll($goodsnew);

        return $addcp;
    }

    /**
     * 空投模型(商品)
     */
    public function airdropNew($goodsid,$user_ids='')
    {
        //根据商品ID给所有用户添加商品，生成记录
        //查询所有用户ID
        $userList = Db::name('user')
            ->where('user_id','in',$user_ids)
            ->field('user_id')
            ->select()->toArray();


        $config = Integrals::find();
        $time = $config['setday'] * 86400;  //转赠时间
        $good = GoodsModel::get($goodsid);

        //将这个数组循环遍历到新增的数组中
        foreach ($userList as $key => $userarr) {
            $goodsnew = [];
            $goodsnew['user_id'] = $userarr['user_id'];
            $goodsnew['goods_id'] = $goodsid;
            $goodsnew['addtime'] = time();
            $goodsnew['order_no'] = 'ART'.date('YmdHis').time().$userarr['user_id'];
            $goodsnew['coll_no'] = random(8);
            $goodsnew['image_id'] =  $this->getimage($goodsid);
            $goodsnew['goods_name'] =  $good['goods_name'];
            $goodsnew['zztime']    = time() + $time;
            $goodsnew['type']    = 2;
            $goodsnew['is_sl']   = $good['is_sl'];
            $addcp = DB::name('coll')->insertGetId($goodsnew);

            GoodsSn::create([
                'goods_id'  => $goodsid,
                'number'    => 0,
                'coll_id'   => $addcp
            ]);

        }

        return true;
    }

    /**
     * 获取图片id
     * @param $goodsid
     * @return int|mixed
     */
    public function getImage($goodsid)
    {
        $ids = GoodsImage::where('goods_id',$goodsid)->column('image_id');
        $coll_ids = Coll::where('goods_id', $goodsid)->column('image_id');
        if (empty($ids)){
            return 0;
        }
        //去重
        $diff = array_diff($ids,$coll_ids);
        //图片已分配完后默认取第一张
        if (empty($diff)){
            return $ids[0];
        }
        //随机获取
        $key = array_rand($diff);
        return $diff[$key];
    }


}