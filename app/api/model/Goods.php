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

namespace app\api\model;

use app\api\service\Goods as GoodsService;
use app\api\service\user\Grade as UserGradeService;
use app\api\model\GoodsSpecRel as GoodsSpecRelModel;
use app\common\model\Goods as GoodsModel;
use app\common\enum\goods\Status as GoodsStatusEnum;
use app\common\model\GoodsSn;
use app\common\model\Integrals;
use app\common\model\Make;
use app\common\model\UploadFile;
use app\store\model\GoodsDebrisRel;
use cores\exception\BaseException;
use think\facade\Cache;
use think\facade\Db;
use app\api\service\User;
use think\Model;
use think\Request;

/**
 * 商品模型
 * Class Goods
 * @package app\api\model
 */
class Goods extends GoodsModel
{
    /**
     * 隐藏字段
     * @var array
     */
    public $hidden = [
        'images',
        'delivery',
        'deduct_stock_type',
        'sales_initial',
        'sales_actual',
        'sort',
        'is_delete',
        'store_id',
        'create_time',
        'update_time'
    ];

    // 是否设置会员折扣价
    private $isGoodsGradeMoney = true;

    /**
     * 商品详情：HTML实体转换回普通字符
     * @param $value
     * @return string
     */
    public function getContentAttr($value): string
    {
        return htmlspecialchars_decode((string)$value);
    }

    /**
     * 是否设置会员折扣价
     * @param bool $value
     * @return $this
     */
    public function isGoodsGradeMoney(bool $value): Goods
    {
        $this->isGoodsGradeMoney = $value;
        return $this;
    }

    /**
     * 获取商品列表
     * @param array $param 查询条件
     * @param int $listRows 分页数量
     * @return mixed|\think\model\Collection|\think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function getList(array $param = [], int $listRows = 15)
    {
        // 整理查询参数
        $params = array_merge($param, ['status' => GoodsStatusEnum::ON_SALE]);
        // 获取商品列表
        $list = parent::getList($params, $listRows);

        if ($list->isEmpty()) {
            return $list;
        }
        // 隐藏冗余的字段
        $list->hidden(array_merge($this->hidden, ['content', 'goods_images', 'images']));
        // 整理列表数据并返回
        return $this->setGoodsListDataFromApi($list);
    }

    /**
     * 获取商品详情 (详细数据用于页面展示)
     * @param int $goodsId 商品id
     * @return mixed
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getDetails(int $goodsId)
    {
        // 关联查询
        $with = ['images.file', 'skuList.image', 'video', 'videoCover'];
        // 获取商品记录
        $goodsInfo = static::detail($goodsId, $with);
        // 判断商品的状态
        if (empty($goodsInfo) || $goodsInfo['is_delete'] || $goodsInfo['status'] != GoodsStatusEnum::ON_SALE) {
            throwError('很抱歉，商品信息不存在或已下架');
        }
        // 设置商品展示的数据
        $goodsInfo = $this->setGoodsDataFromApi($goodsInfo);
        // 商品规格列表
        $goodsInfo['specList'] = GoodsSpecRelModel::getSpecList($goodsInfo['goods_id']);
        return $goodsInfo;
    }

    /**
     * 根据商品id集获取商品列表
     * @param array $goodsIds
     * @return mixed
     */
    public function getListByIdsFromApi(array $goodsIds)
    {
        // 获取商品列表
        $data = $this->getListByIds($goodsIds, GoodsStatusEnum::ON_SALE);

        // 整理列表数据并返回
        return $this->setGoodsListDataFromApi($data);
    }

    /**
     * 获取商品指定的sku信息并且设置商品的会员价
     * @param mixed $goodsInfo 商品信息
     * @param string $goodsSkuId 商品SKUID
     * @param bool $isGoodsGradeMoney 是否设置会员折扣价
     * @return \app\common\model\GoodsSku|array|null
     * @throws BaseException
     */
    public static function getSkuInfo($goodsInfo, string $goodsSkuId, bool $isGoodsGradeMoney = true)
    {
        $goodsInfo['skuInfo'] = GoodsService::getSkuInfo($goodsInfo['goods_id'], $goodsSkuId);
        $isGoodsGradeMoney && (new static)->setGoodsGradeMoney($goodsInfo);
        return $goodsInfo['skuInfo'];
    }

    /**
     * 设置商品展示的数据 api模块
     * @param $data
     * @return mixed
     */
    private function setGoodsListDataFromApi($data)
    {
        return $this->setGoodsListData($data, function ($goods) {
            // 整理商品数据 api模块
            $this->setGoodsDataFromApi($goods);
        });
    }
    /**
     * 整理商品数据 api模块
     * @param $goodsInfo
     * @return mixed
     */
    private function setGoodsDataFromApi($goodsInfo)
    {
        return $this->setGoodsData($goodsInfo, function ($goods) {
            // 计算并设置商品会员价
            $this->isGoodsGradeMoney && $this->setGoodsGradeMoney($goods);
        });
    }
    /**
     * 设置商品的会员价
     * @param Goods $goods
     * @throws BaseException
     */
    private function setGoodsGradeMoney(self $goods)
    {
        // 设置当前商品是否使用会员等级折扣价
        $goods['is_user_grade'] = false;
        // 获取当前登录用户的会员等级信息
        $gradeInfo = UserGradeService::getCurrentGradeInfo();
        // 判断商品是否参与会员折扣
        if (empty($gradeInfo) || !$goods['is_enable_grade']) {
            return;
        }
        // 默认的折扣比例
        $discountRatio = $gradeInfo['equity']['discount'];
        // 商品单独设置了会员折扣
        if ($goods['is_alone_grade'] && isset($goods['alone_grade_equity'][$gradeInfo['grade_id']])) {
            $discountRatio = $goods['alone_grade_equity'][$gradeInfo['grade_id']];
        }
        if (empty($discountRatio)) {
            return;
        }
        // 标记参与会员折扣
        $goods['is_user_grade'] = true;
        // 会员折扣价: 商品基础价格
        $goods['goods_price_min'] = UserGradeService::getDiscountPrice($goods['goods_price_min'], $discountRatio);
        $goods['goods_price_max'] = UserGradeService::getDiscountPrice($goods['goods_price_max'], $discountRatio);
        // 会员折扣价: 商品sku列表
        if ($goods->getRelation('skuList')) {
            foreach ($goods['skuList'] as &$skuItem) {
                $skuItem['goods_price'] = UserGradeService::getDiscountPrice($skuItem['goods_price'], $discountRatio);
            }
        }
        // 会员折扣价: 已选择的商品sku（用于购物车）
        if ($goods->getAttr('skuInfo')) {
            $goods['skuInfo']['goods_price'] = UserGradeService::getDiscountPrice($goods['skuInfo']['goods_price'], $discountRatio);
        }
    }

    /**
     *
     * 首页商品展示数据接口
     * 商品ID goods_id
     * 商品图片
     * 商品标题 goods_name
     * 商品剩余数量 stock_total
     * 更新时间 update_time
     * 价格 goods_price_min
     * 商品状态 status 10上架20下架->hidden (['goods_id', 'goods_name', 'stock_total','update_time','goods_price_min','status'])
     */
    public function lista(int $listRows = 2,int $user_id,string $keyword){
        try {

            $where = [];

            if(!empty($user_id)){

                $where[] = ['user_id','=',$user_id];

            }

            if(!empty($keyword)){

                $where[] = ['goods_name','like','%'.$keyword.'%'];
            }


            // 执行查询
            $goods = new GoodsModel();
            $list = $goods->with(['images.file'])
                ->alias($this->name)
                ->field($this->getAliasFields($this->name, ['content']))
                ->where('is_delete', '=', 0)
                ->where('is_box','=',0)
                ->where('type','<>',2)
                ->where('status','=',10)
                ->where($where)
                ->order('goods_id desc')
                ->paginate($listRows);
            return $this->setGoodsListDataIndex($list);
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }

    /**
     * 前端商品详情接口
     * 商品ID goods_id
     * 商品图片 file_path
     * 商品标题 goods_name
     * 商品剩余数量 stock_total
     * 更新时间 update_time
     * 价格 goods_price_min
     * 商品详情 content
     */
    public function goodsDetails(int $goodsid){
        $user = User::getCurrentLoginUser();
        $user_id = $user['user_id'];
        $data = Db::name('goods')
            ->alias('g')
            ->where('g.goods_id',$goodsid)
            ->join(['yoshop_goods_image'=>'gi'],'g.goods_id=gi.goods_id')
            ->join(['yoshop_upload_file'=>'uf'],'gi.image_id=uf.file_id')
            ->join(['yoshop_goods_sku'=>'gs'],'g.goods_id=gs.goods_id')
            ->field('g.get_total,g.get_limit,g.sales_actual,g.xn_sale')
            ->field('g.type,g.rate,g.xn_sale,g.endtime,g.maketime,g.publishtime,g.hot_starttime,g.hot_endtime,g.hot_ids,hot_type')
            ->field('g.goods_id, g.isresale, g.isgive, g.c_name,g.goods_name,g.stock_total,g.update_time,g.goods_price_min,g.status,g.file_path as images,uf.file_path')
            ->field('g.content,gs.stock_num,g.startTime,g.author,g.goods_address,g.logo,g.first_goods_id,first_goods_num,g.first,g.first_time,uf.file_id')
            ->find();

        $first_num = 0;

        if ($data['first'] == 1 ){
           $coll_ids = json_decode($data['first_goods_id'],true);
           $count = Coll::where('goods_id','in',$coll_ids)->where('user_id',$user_id)->where('status',0)->count();
           //拥有藏品时计算优先购时间
           if ($count > 0){
                $start = strtotime($data['startTime']);
                /*if($start > time()){

                    $first_goods_num = unserialize($data['first_goods_num']);

                    foreach($first_goods_num as $f=>$n){

                        //获取指定藏品总数
                        $coll_num = Coll::where('user_id',$user_id)
                                ->where('goods_id',$f)
                                ->where('is_give',0)
                                ->count()*$n;

                        $first_num += $coll_num;

                    }
                }*/
                $first_time = $data['first_time'] * 3600;
                $time = date('Y-m-d H:i:s',(int)($start - $first_time));
                $data['startTime'] = $time;
                $data['first_types'] = 1;

           }

        }else{

            $data['first_types'] = 0;
        }

        $data['first_num'] = $first_num;

        if($data['type'] == 3){

            $make_info = Make::where('goods_id',$goodsid)
                ->where('user_id',$user_id)
                ->find();

            if($data['publishtime'] <= time()){

                if(empty($make_info)){

                    $data['is_make'] = 3;//未参与

                }

                if($make_info['status'] == 1){

                    $data['is_make'] = 4;//已中奖

                }

                if($make_info['status'] == 2){

                    $data['is_make'] = 5;//未中奖

                }
            }else if($data['maketime'] <= time()){

                if(empty($make_info)){

                    $data['is_make'] = 1;//开始发签

                }else{

                    $data['is_make'] = 2;//等待发签
                }

            }else{

                if($data['maketime'] > time()){

                    $data['is_make'] = 0;//待发签

                }

            }



        }
        $data['endtime'] = !empty($data['endtime'])?date('Y-m-d H:i:s',$data['endtime']):0;
        $data['maketime'] = !empty($data['maketime'])?date('Y-m-d H:i:s',$data['maketime']):0;
        $data['publishtime'] = !empty($data['publishtime'])?date('Y-m-d H:i:s',$data['publishtime']):0;
        $data['content']=htmlspecialchars_decode($data['content']);
        $data['images'] = base_url() . 'uploads/' . $data['images'];
        $file = UploadFile::where('file_id',$data['file_id'])->find();
        if (empty($file['domain'])){
            $data['file_path'] = base_url() . 'uploads/' . $file['file_path'];
        }else{
            $data['file_path'] = $file['domain'] . '/' . $file['file_path'];
        }
        //$data['goods_address'] = '0xe01b312FD78C98451ccb2E9E38B7f561f7B95952';
        return $data;
    }

    /**
     * 合成商品详情
     * @param int $goodsId
     * @return array|Db|\think\Model|null
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function debrisDetails(int $goodsId){
        $user_id = User::getCurrentLoginUserId();
        $data = Db::name('goods')
            ->alias('g')
            ->where('g.goods_id',$goodsId)
            ->join(['yoshop_goods_image'=>'gi'],'g.goods_id=gi.goods_id')
            ->join(['yoshop_upload_file'=>'uf'],'gi.image_id=uf.file_id')
            ->join(['yoshop_goods_sku'=>'gs'],'g.goods_id=gs.goods_id')
            ->field('g.goods_id,g.goods_name,g.stock_total,g.update_time,g.goods_price_min,g.status,uf.file_path,g.content,gs.stock_num,g.startTime,g.author,g.goods_address,g.logo,g.is_box')
            ->find();
        $data['content'] = htmlspecialchars_decode($data['content']);
        $data['file_path'] = base_url() . 'uploads/' . $data['file_path'];
        $num = 0;
        if ($data && $data['is_box'] == 2){
            $debris = GoodsDebrisRel::with('goods.images.file')->where('goods_id',$goodsId)->select()->toArray();
            foreach ($debris as &$value){
                $count = Coll::where(['user_id' => $user_id,'goods_id' => $value['debris_id'],'is_give'=>0])->where('status',0)->count();
                $value['count'] = $count;
                $value['goods']['images'] = $value['goods']['images'][0]['file']['preview_url'];
                if ($count != 0){
                    $num += 1;
                }
            }
            $data['debris'] = $debris;
        }
        $data['num'] = $num;
        return $data;
    }

    /**
     * 合成
     * @param $goodsId
     * @return bool
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function compound($goodsId)
    {
        $user_id = User::getCurrentLoginUserId();
        $good = GoodsModel::get($goodsId);
        if (empty($good) || $good['is_box'] != 2){
            return false;
        }
        $debris = GoodsDebrisRel::where('goods_id',$goodsId)->column('debris_id');
        foreach ($debris as $id){
            $coll = Coll::where('goods_id',$id)->where('user_id',$user_id)->where('status',0)->find();
            if (empty($coll)){
                return false;
            }
        }
        $config = Integrals::find();
        $time = $config['setday'] * 86400;  //转赠时间
        Db::startTrans();
        try {
            $data = [
                'user_id' => $user_id,
                'goods_id' => $goodsId,
                'addtime' => time(),
                'order_no' => 'ART'.date('YmdHis').time(),
                'zztime' => time() + $time,
                'goods_name' => $good['goods_name'],
                'image_id'   => $this->getimage($goodsId),
                'coll_no'    => random(8)
            ];
            $res = Coll::create($data);
            GoodsSn::create([
                'goods_id'  => $goodsId,
                'number'    => 0,
                'coll_id'   => $res['coll_id'],
            ]);
            foreach ($debris as $id){
                $coll = Coll::where('goods_id',$id)->where('user_id',$user_id)->where('status',0)->find();
                $coll->save(['status' => 3]);
            }
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return false;
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

    /**
     * 订单取消方法
     */
    public function orderCancel(){
        //获取所有未支付的订单
        $orderList=Db::name('order')
            ->where('pay_status',10)
            ->where('order_status',10)
            ->select()->toArray();



        //不为空时返回FALSE
        if(empty($orderList)){
            return false;
        }

        //获取当前时间戳
        $times=time();
        //获取15分钟限制
        $fifteen=900;
        $arr=array();
        $sukArr=array();
        foreach ($orderList as $key=>$value){
            //对查询出来的时间戳进行计算，超过15分钟的记录下来
            $sum=$orderList[$key]['create_time']+$fifteen;

            if($times>=$sum){//超过15分钟

                $arr[]=$orderList[$key]['order_no'];
                //到期后将该商品的库存加1
                $incs=Db::name('goods_sku')->where('goods_id',$orderList[$key]['goods_id'])->inc('stock_num')->update();
            }
        }

        //将拿出来的订单状态改为已取消
        $orderTypeUp=Db::name('order')
            ->where('order_no','in',$arr)
            ->update(['order_status'=>20]);

        if($orderTypeUp>0){
            return true;
        }
        return false;
    }

    /**
     * 查询关于我们
     */
    public function chackAbotUs(){
        $arrList=Db::name('integrals')
            ->where('id',1)
            ->field('id,wecha,qq')
            ->select()->toArray();

        return $arrList;
    }

    /**
     * 查询空投设置
     */
    public function getkong(){
        $getarr=Db::name('aboutus')
            ->where('id',1)
            ->find();
        return $getarr;
    }

    /**
     * 空投
     * $getnewtime 上一次空投投放时间
     * $getFatime 空投投放间隔
     */
    public function getUsers($getManCount,$getFatime,$getnewtime){
        //查询所有用户的邀请人数

        //查询所有邀请过人的用户ID
        $getUsers=Db::name('user')
            ->distinct(true)
            ->where('extension_id','>',0)
            ->field('extension_id')
            ->select()->toArray();
        $arr=array();
        //然后把数据放入到数组中
        foreach($getUsers as $key => $value){
            $arr[]=$value['extension_id'];
        }

        $arrList=array();
        //根据ID循环查找
        foreach ($arr as $k =>$v){
            $arrList[$v]=Db::name('user')
                ->field('extension_id')
                ->where('extension_id',$v)
                ->count('extension_id');
        }

        $newArrList=array();
        //再次遍历 把所有超过一定次数的ID筛选出来
        foreach ($arrList as $ke => $va){
            if($va>=$getManCount){
                $newArrList[]=$ke;
            }
        }

        //$getnewtime上一次空投时间
        //计算应该投放空投的时间
        $cheng=$getnewtime+$getFatime*86400;

        //判断当前是否已经到了发送空投的时间
        if($cheng<time()){
            //符合条件，发放空投
            foreach ($newArrList as $kk => $vv){
                //查询所有商品
                $goodsAll=Db::name('goods')
                    ->alias('g')
                    ->join('yoshop_goods_sku gs','g.goods_id=gs.goods_id')
                    ->where('gs.stock_num','>',1)
                    ->where('g.is_box',0)
                    ->where('g.is_delete',0)
                    ->where('g.status',10)
                    ->field('g.goods_id')
                    ->select()->toArray();

                //将商品放到数组中
                foreach ($goodsAll as $keys => $val){
                    $goodsArr[]=$goodsAll[$keys]['goods_id'];
                }
                //生成随机数字
                $rands=rand(0,count($goodsArr)-1);
                //获取到随机下标的商品ID
                $goodsId=$goodsArr[$rands];

                //将该商品发送给符合条件的用户

                //根据商品ID查询商品名称
                $goodslist=Db::name('goods')
                    ->where('goods_id',$goodsId)
                    ->field('goods_name')
                    ->find();
                //新增藏品库
                $newArr=[
                    'user_id'   =>  $newArrList[$kk],
                    'goods_id'  =>  $goodsId,
                    'addtime'   =>  time(),
                    'zztime'    =>  time(),
                    'order_no'  =>  '',
                    'goods_name'=>  $goodslist['goods_name'],
                    'coll_no' => random(8)
                ];

                //新增
                $setst=Db::name('coll')
                    ->insert($newArr);


                //新增成功后库存-1
                if($setst==1){
                    $kc=Db::name('goods')
                        ->alias('g')
                        ->join('yoshop_goods_sku gs','g.goods_id=gs.goods_id')
                        ->where('gs.goods_id',$goodsId)
                        ->dec('gs.stock_num')
                        ->update();
                }
            }

            //将当前发放时间记录到数据库，以便下次使用
            $a=Db::name('aboutus')
                ->where('id',1)
                ->update(['newtime'=>time()]);
            //返回所有发送了空投的用户ID
            return $newArrList;
        }
        return false;
    }
    /**
     * 查询盲盒价格
     */
    public function boxMoneys(){
        $boxmoney=Db::name('integrals')
            ->where('id',1)
            ->field('box_money')
            ->find();
        return $boxmoney;
    }



    /**
     * 查询未发售
     * 发售日期
        发售时间
        藏品名字
        藏品总数量
        藏品价格
     */
     public function notSaleMode(){
         //查询数据
//         $saleList=Db::name('goods')
//            ->alias('g')
//            ->leftJoin('yoshop_goods_sku gs','g.goods_id=gs.goods_id')
//            ->leftJoin('yoshop_goods_image gi','g.goods_id=gi.goods_id')
//            ->leftJoin('yoshop_upload_file uf','gi.image_id=uf.file_id')
//            ->where('g.is_box',0)
//            ->where('g.is_delete',0)
//            ->where('g.status',10)
//            ->field('g.goods_id,g.goods_name,uf.file_path,gs.goods_price,g.stock_total,g.startTime')
//            ->order('g.startTime')
//            ->select()->toArray();
         $goods = new GoodsModel();
         $saleList = $goods->with(['images.file'])
             ->alias($this->name)
             ->field($this->getAliasFields($this->name, ['content']))
             ->where('is_delete', '=', 0)
             ->where('is_box','=',0)
             ->order('startTime')
             ->select();
         $saleList =  $this->setGoodsListData($saleList);
            //获取当前的时间转换成时间戳
            // $arr=array();
            foreach ($saleList as $k=>$v){
                //获取当前时间戳
                $saleList[$k]['setTime'] = strtotime($saleList[$k]['startTime']);
                //获取日期
                $saleList[$k]['day'] = date('m月d日',$saleList[$k]['setTime']);
                //获取时间
                $saleList[$k]['his'] = date('h:i',$saleList[$k]['setTime']);
            }

            $arrs=array();
            $arrlist=array();
            $i=0;
            foreach ($saleList as $key=>$value){
                //剔除超过当前时间的数据
                if($saleList[$key]['setTime']>time()){
                    if($saleList[$key]['day']==$value['day']){
                        $arrlist[$value['day']][]=$value;
                    }
                }
            }
            foreach ($arrlist as $ke=>$va){
                $arrs[]=$va;
            }
            return $arrs;
     }

    /**
     * 查询盲盒价格和盲盒个数
     */
    public function rareList(){
        $data = GoodsModel::rate();
        return $data;
    }
}
