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
use cores\exception\BaseException;
use think\facade\Db;

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
    public function lista(){
        
        try {
            
            //查询所有上架的商品信息  code=1
            $list=Db::name('goods')
//                ->where('g.status',10)
                ->alias('g')
                ->join(['yoshop_goods_image'=>'gi'],'g.goods_id=gi.goods_id')
                ->join(['yoshop_upload_file'=>'uf'],'gi.image_id=uf.file_id')
                ->join(['yoshop_goods_sku'=>'gs'],'g.goods_id=gs.goods_id')
                ->where('g.is_delete',0)//不查询软删除
                ->where('g.is_box',0)//不查询盲盒
                ->where('status',10)//不查询下架产品
                ->field('g.goods_id,g.goods_name,g.stock_total,g.create_time,g.goods_price_min,g.status,uf.file_path,gs.stock_num,g.startTime,g.author,g.goods_address,g.logo')
                ->order('g.create_time','desc')
                ->select();

            //查询商品的时候统计所有库存为0的商品,并将状态改为下架
            $numarr=[];
            foreach ($list as $key=>$lista) {
                //剩余库存为0时填充入数据库
                if(empty($lista['stock_num'])){
                    $numarr[]=$lista['goods_id'];
                }
            }
            //修改商品状态，将库存为0的商品进行下架
            $goodsdow=Db::name('goods')
                ->whereIn('goods_id',$numarr)
                ->update(['status'=>20]);
            return $list;
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
        try {
            $lista=Db::name('goods')
                ->alias('g')
                ->where('g.goods_id',$goodsid)
                ->join(['yoshop_goods_image'=>'gi'],'g.goods_id=gi.goods_id')
                ->join(['yoshop_upload_file'=>'uf'],'gi.image_id=uf.file_id')
                ->join(['yoshop_goods_sku'=>'gs'],'g.goods_id=gs.goods_id')
                ->field('g.goods_id,g.goods_name,g.stock_total,g.update_time,g.goods_price_min,g.status,uf.file_path,g.content,gs.stock_num,g.startTime,g.author,g.goods_address,g.logo')
                ->find();
                $lista['content']=htmlspecialchars_decode($lista['content']);
            return $lista;
        }catch (\Throwable $th){
            echo $th->getMessage();
        }
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
     * 查询盲盒价格和盲盒个数
     */
    public function boxMoneys(){
        $boxmoney=Db::name('integrals')
            ->where('id',1)
            ->field('box_money,box_sum')
            ->find();
        return $boxmoney;
    }
}
