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

namespace app\common\model;

use think\Paginator;
use think\model\Collection;
use think\model\relation\HasOne;
use cores\BaseModel;
use app\store\model\GoodsCategoryRel as GoodsCategoryRelModel;
use app\common\enum\goods\Status as GoodsStatusEnum;
use app\common\library\helper;

/**
 * 商品模型
 * Class Goods
 * @package app\common\model
 */
class Goods extends BaseModel
{
    // 定义表名
    protected $name = 'goods';

    // 定义主键
    protected $pk = 'goods_id';

    // 追加字段
    protected $append = ['goods_sales'];

    /**
     * 关联模型：主图视频文件
     * @return HasOne
     */
    public function video(): HasOne
    {
        return $this->hasOne('UploadFile', 'file_id', 'video_id');
    }

    /**
     * 关联模型：主图视频封面图片文件
     * @return HasOne
     */
    public function videoCover(): HasOne
    {
        return $this->hasOne('UploadFile', 'file_id', 'video_cover_id');
    }

    /**
     * 计算显示销量 (初始销量 + 实际销量)
     * @param $value
     * @param $data
     * @return mixed
     */
    public function getGoodsSalesAttr($value, $data)
    {
        return $data['sales_initial'] + $data['sales_actual'];
    }

    public function getMakeTimeAttr($value){

        if(!is_string($value) && !empty($value)){

            return date('Y-m-d H:i:s',$value);

        }else{

            return $value;

        }
    }

    public function getPublishtimeAttr($value){

        if(!is_string($value) && !empty($value)){

            return date('Y-m-d H:i:s',$value);

        }else{

            return $value;

        }
    }

    public function getEndtimeAttr($value){

        if(!is_string($value) && !empty($value)){

            return date('Y-m-d H:i:s',$value);

        }else{

            return $value;

        }
    }

    public function setFirstGoodsNumAttr($value){

        $info = [];
        if(isset($value) && !empty($value)){

            foreach($value as $ks=>$v){

                foreach($v as $k=>$i){

                    $info[$k] = $i;

                }
            }

            return serialize($info);

        }else{

            return '';
        }
    }

    public function getFirstGoodsNumAttr($value){


        if(empty($value)){

            return [];

        }else{

            return unserialize($value);

        }


    }

    public function setEndtimeAttr($value){

        if(!is_int($value) && !empty($value)){

            return strtotime($value);

        }else{

            return $value;

        }
    }
    public function setMaketimeAttr($value){

        if(!is_int($value) && !empty($value)){

            return strtotime($value);

        }else{

            return 0;

        }
    }

    public function setPublishtimeAttr($value){

        if(!is_int($value) && !empty($value)){

            return strtotime($value);

        }else{

            return 0;

        }
    }

    /**
     * 商品详情：HTML实体转换回普通字符
     * @param $value
     * @return string
     */
    public function getContentAttr($value)
    {
        return htmlspecialchars_decode($value);
    }

    /**
     * 获取器：单独设置折扣的配置
     * @param $json
     * @return mixed
     */
    public function getAloneGradeEquityAttr($json)
    {
        return helper::jsonDecode($json);
    }

    /**
     * 修改器：单独设置折扣的配置
     * @param $data
     * @return mixed
     */
    public function setAloneGradeEquityAttr($data)
    {
        return helper::jsonEncode($data);
    }

    /**
     * 关联商品规格表
     * @return \think\model\relation\HasMany
     */
    public function skuList()
    {
        return $this->hasMany('GoodsSku')->order(['id' => 'asc']);
    }

    /**
     * 关联商品规格关系表
     * @return \think\model\relation\HasMany
     */
    public function specRel()
    {
        return $this->hasMany('GoodsSpecRel');
    }

    /**
     * 关联商品图片表
     * @return \think\model\relation\HasMany
     */
    public function images()
    {
        return $this->hasMany('GoodsImage')->order(['id']);
    }

    /**
     * 关联运费模板表
     * @return \think\model\relation\BelongsTo
     */
    public function delivery()
    {
        return $this->BelongsTo('Delivery');
    }

    /**
     * 关联订单评价表
     * @return \think\model\relation\HasMany
     */
    public function commentData()
    {
        return $this->hasMany('Comment');
    }

    /**
     * 获取全部商品列表
     * @param array $param 查询条件
     * @param int $listRows 分页数量
     * @return mixed
     * @throws \think\db\exception\DbException
     */
    public function getAllList(array $param = [], int $listRows = 15)
    {
        // 筛选条件
        $query = $this->getQueryFilter($param);
        // 设置显示的销量 goods_sales
        $query->field(['(sales_initial + sales_actual) as goods_sales']);
        // 排序条件
        $sort = $this->setQuerySort($param);
        // 执行查询
        $list = $query
            ->with(['images.file'])
            ->field('goods_id,goods_name,sales_initial,sales_actual,probability')
            ->alias($this->name)
//            ->field($this->getAliasFields($this->name, ['content']))
            ->where('is_delete', '=', 0)
            ->order($sort)
            ->select();
//        return $list;
        // 整理列表数据并返回
        return $this->setGoodsListData($list);
    }

    /**
     * 获取商品列表
     * @param array $param 查询条件
     * @param int $listRows 分页数量
     * @return mixed
     * @throws \think\db\exception\DbException
     */
    public function getList(array $param = [], int $listRows = 15)
    {
        // 筛选条件
        $query = $this->getQueryFilter($param);
        // 设置显示的销量 goods_sales
        $query->field(['(sales_initial + sales_actual) as goods_sales']);
        // 排序条件
        $sort = $this->setQuerySort($param);
        // 执行查询
        $list = $query->with(['images.file'])
            ->alias($this->name)
            ->field($this->getAliasFields($this->name, ['content']))
            ->where('is_delete', '=', 0)
            ->where('is_box','=',0)
            ->order($sort)
            ->paginate($listRows);
        // 整理列表数据并返回
        return $this->setGoodsListData($list);
    }

    /**
     * 获取商品列表
     * @param array $param 查询条件
     * @param int $listRows 分页数量
     * @return mixed
     * @throws \think\db\exception\DbException
     */
    public function getBoxList(array $param = [], int $listRows = 15)
    {
        // 筛选条件
        $query = $this->getQueryFilter($param);
        // 设置显示的销量 goods_sales
        $query->field(['(sales_initial + sales_actual) as goods_sales']);
        // 排序条件
        $sort = $this->setQuerySort($param);
        // 执行查询
        $list = $query->with(['images.file'])
            ->alias($this->name)
            ->field($this->getAliasFields($this->name, ['content']))
            ->where('is_delete', '=', 0)
            ->where('is_box','=',1)
            ->order($sort)
            ->paginate($listRows);
        // 整理列表数据并返回
        return $this->setGoodsListData($list);
    }

    /**
     * 获取私有藏品列表
     * @param array $param 查询条件
     * @param int $listRows 分页数量
     * @return mixed
     * @throws \think\db\exception\DbException
     */
    public function getRareList(array $param = [], int $listRows = 15)
    {
        // 筛选条件
        $query = $this->getQueryFilter($param);
        // 设置显示的销量 goods_sales
        $query->field(['(sales_initial + sales_actual) as goods_sales']);
        // 排序条件
        $sort = $this->setQuerySort($param);
        // 执行查询
        $list = $query->with(['images.file'])
            ->alias($this->name)
            ->field($this->getAliasFields($this->name, ['content']))
            ->where('is_delete', '=', 0)
            ->where('is_box','=',2)
            ->order($sort)
            ->paginate($listRows);
        // 整理列表数据并返回
        return $this->setGoodsListData($list);
    }


    /**
     * 检索排序条件
     * @param array $param
     * @return array|string[]
     */
    private function setQuerySort(array $param = [])
    {
        $params = $this->setQueryDefaultValue($param, [
            'sortType' => 'all',    // 排序类型
            'sortPrice' => false,   // 价格排序 (true高到低 false低到高)
        ]);
        // 排序规则
        $sort = [];
        if ($params['sortType'] === 'all') {
            $sort = ['sort' => 'asc'];
        } elseif ($params['sortType'] === 'sales') {
            $sort = ['goods_sales' => 'desc'];
        } elseif ($params['sortType'] === 'price') {
            $sort = $params['sortPrice'] ? ['goods_price_max' => 'desc'] : ['goods_price_min' => 'asc'];
        }
        return array_merge($sort, [$this->getPk() => 'desc']);
    }

    /**
     * 检索查询条件
     * @param array $param
     * @return \think\db\BaseQuery
     */
    private function getQueryFilter(array $param)
    {
        // 商品列表获取条件
        $params = $this->setQueryDefaultValue($param, [
            'listType' => 'all',    // 列表模式 (全部:all 出售中:on_sale 已下架:off_sale 已售罄:sold_out)
            'categoryId' => null,    // 分类ID集
            'goodsName' => null,     // 商品名称
            'goodsNo' => null,       // 商品编码
            'status' => 0,         // 商品状态(0全部 10上架 20下架)
        ]);
        // 实例化新查询对象
        $query = $this->getNewQuery();
        // 筛选条件
        $filter = [];
        // 列表模式
        if ($params['listType'] === 'on_sale') {
            $filter[] = ['status', '=', GoodsStatusEnum::ON_SALE];        // 出售中
        } elseif ($params['listType'] === 'off_sale') {
            $filter[] = ['status', '=', GoodsStatusEnum::OFF_SALE];        // 已下架
        } elseif ($params['listType'] === 'sold_out') {
            $filter[] = ['stock_total', '=', 0];    // 已售罄
        }
        // 商品状态
        $params['status'] > 0 && $filter[] = ['status', '=', (int)$params['status']];
        // 商品分类
        if ($params['categoryId'] > 0) {
            // 关联商品与分类关系记录表
            $GoodsCategoryRelName = (new GoodsCategoryRelModel)->getName();
            $query->join($GoodsCategoryRelName, "{$GoodsCategoryRelName}.goods_id = {$this->name}.goods_id");
            // 设置分类ID条件
            $query->where('goods_category_rel.category_id', '=', (int)$params['categoryId']);
        }
        // 商品名称
        !empty($params['goodsName']) && $filter[] = ['goods_name', 'like', "%{$params['goodsName']}%"];
        // 商品编码
        !empty($params['goodsNo']) && $filter[] = ['goods_no', 'like', "%{$params['goodsNo']}%"];

        !empty($params['is_box']) && $filter[] = ['is_box','=',$params['is_box']];

        !empty($params['type']) && $filter[] = ['type','=',$params['type']];
        // 实例化新查询对象
        return $query->where($filter);
    }

    /**
     * 设置商品展示的数据
     * @param Collection|Paginator $list 商品列表
     * @param callable|null $callback 回调函数
     * @return mixed
     */
    protected function setGoodsListData($list, callable $callback = null)
    {
        if ($list->isEmpty()) return $list;
        // 遍历商品列表整理数据
        foreach ($list as &$goods) {
            $goods = $this->setGoodsData($goods, $callback);
        }
        return $list;
    }

    /**
     * 首页商品展示的数据
     * @param Collection|Paginator $list 商品列表
     * @param callable|null $callback 回调函数
     * @return mixed
     */
    protected function setGoodsListDataIndex($list, callable $callback = null)
    {
        if ($list->isEmpty()) return $list;
        // 遍历商品列表整理数据
        foreach ($list as &$goods) {
            $goods = $this->setGoodsDataIndex($goods, $callback);
        }
        return $list;
    }

    /**
     * 整理商品数据
     * @param Collection|static $goodsInfo
     * @param callable|null $callback
     * @return mixed
     */
    protected function setGoodsData($goodsInfo, callable $callback = null)
    {
        // 商品图片列表
        $goodsInfo['goods_images'] = helper::getArrayColumn($goodsInfo['images'], 'file');
        // 商品主图
        $goodsInfo['goods_image'] = current($goodsInfo['goods_images'])['preview_url'];
        // 商品销量(实际显示=初始虚拟销量+实际销量)
        $goodsInfo['goods_sales'] = $goodsInfo['sales_initial'] + $goodsInfo['sales_actual'];
        // 回调函数
        is_callable($callback) && call_user_func($callback, $goodsInfo);
        return $goodsInfo->hidden(array_merge($this->hidden, ['specRel', 'images']));
    }

    /**
     * 整理商品数据
     * @param Collection|static $goodsInfo
     * @param callable|null $callback
     * @return mixed
     */
    protected function setGoodsDataIndex($goodsInfo, callable $callback = null)
    {
        // 商品图片列表
        $goods_images = helper::getArrayColumn($goodsInfo['images'], 'file');
        // 商品主图
        $goodsInfo['goods_image'] = current($goods_images)['preview_url'];
        // 商品销量(实际显示=初始虚拟销量+实际销量)
        $goodsInfo['goods_sales'] = $goodsInfo['sales_initial'] + $goodsInfo['sales_actual'];
        // 回调函数
        is_callable($callback) && call_user_func($callback, $goodsInfo);
        return $goodsInfo->hidden(array_merge($this->hidden, ['specRel', 'images']));
    }

    /**
     * 根据商品id集获取商品列表
     * @param array $goodsIds
     * @param null $status
     * @return array|mixed
     */
    public function getListByIds(array $goodsIds, $status = null)
    {
        // 筛选条件
        $filter = [['goods_id', 'in', $goodsIds]];
        // 商品状态
        $status > 0 && $filter[] = ['status', '=', $status];
        // 获取商品列表数据
        $data = $this->withoutField(['content'])
            ->with(['images.file'])
            ->where($filter)
            ->where('is_delete', '=', 0)
            ->orderRaw('field(goods_id, ' . implode(',', $goodsIds) . ')')
            ->select();
        // 整理列表数据并返回
        return $this->setGoodsListData($data);
    }

    /**
     * 商品多规格信息
     * @param \think\Collection $specRel
     * @param \think\Collection $skuData
     * @return array
     */
    public function getManySpecData($specRel, $skuData)
    {
        // spec_attr
        $specAttrData = [];
        foreach ($specRel as $item) {
            if (!isset($specAttrData[$item['spec_id']])) {
                $specAttrData[$item['spec_id']] = [
                    'group_id' => $item['spec']['spec_id'],
                    'name' => $item['spec']['spec_name'],
                    'spec_items' => [],
                ];
            }
            $specAttrData[$item['spec_id']]['spec_items'][] = [
                'item_id' => $item['spec_value_id'],
                'spec_value' => $item['spec_value'],
            ];
        }
        // spec_list
        $specListData = [];
        foreach ($skuData as $item) {
            $image = (isset($item['images']) && !empty($item['images'])) ? $item['images'] : ['file_id' => 0, 'external_url' => ''];
            $specListData[] = [
                'id' => $item['id'],
                'goods_sku_id' => $item['goods_sku_id'],
                'rows' => [],
                'form' => [
                    'image_id' => $image['file_id'],
                    'image_path' => $image['external_url'],
                    'goods_sku_no' => $item['goods_sku_no'],
                    'goods_price' => $item['goods_price'],
                    'goods_weight' => $item['goods_weight'],
                    'line_price' => $item['line_price'],
                    'stock_num' => $item['stock_num'],
                ],
            ];
        }
        return ['spec_attr' => array_values($specAttrData), 'spec_list' => $specListData];
    }

    /**
     * 获取商品记录
     * @param int $goodsId
     * @param array $with
     * @return static
     */
    public static function detail(int $goodsId, array $with = [])
    {
        return static::get($goodsId, $with);
    }

    /**
     * 获取碎片记录
     * @param array $param
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getDebrisList(array $param = [])
    {
        $list = $this->getAll($param);
        return $list->toArray();
    }

    /**
     * 获取空投列表
     * @param array $param
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getAirdropList(array $param = [])
    {
        $list = $this->getAirdrop($param);
        return $list->toArray();
    }

    /**
     * 获取碎片藏品列表
     * @param array $param
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    protected function getAll(array $param = [])
    {
//        // 默认查询参数
//        $params = $this->setQueryDefaultValue($param, [
//            'status' => 10,  // 状态(10上架 20下架)
//        ]);
//        // 设置检索条件
//        $filter = [];
//        $params['status'] > -1 && $filter[] = ['status', '=', $params['status']];
        // 查询列表数据
        return $this->where('type',1)
            ->where('is_delete',0)
            ->order(['sort', 'create_time'])
            ->select();
    }

    /**
     * 获取空投藏品列表
     * @param array $param
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    protected function getAirdrop(array $param = [])
    {
        // 默认查询参数
        $params = $this->setQueryDefaultValue($param, [
            'status' => 10,  // 状态(10上架 20下架)
        ]);
        // 设置检索条件
        $filter = [];
       // $params['status'] > -1 && $filter[] = ['status', '=', $params['status']];

        // 查询列表数据
        $goods = $this->with(['images.file'])->field('goods_id,goods_name,sales_initial,sales_actual')
            ->where($filter)
            ->where('is_box','<>',2)
            ->where('is_delete',0)
            ->order(['sort', 'create_time'])
            ->select();
        return $this->setGoodsListData($goods);
    }

    /**
     * 获取树状列表
     * @param $list
     * @param int $parentId
     * @return array
     */
    private function getTreeData($list, int $parentId = 0)
    {
        $data = [];
        foreach ($list as $key => $item) {
            if ($item['parent_id'] == $parentId) {
                $children = $this->getTreeData($list, $item['category_id']);
                !empty($children) && $item['children'] = $children;
                $data[] = $item;
                unset($list[$key]);
            }
        }
        return $data;
    }

    /**
     * 更新藏品碎片关系记录
     * @param $goodsId
     * @param array $categoryIds 新的分类集
     * @return array|false
     * @throws \Exception
     */
    public static function updates(int $goodsId, $debrisIds)
    {
        $debris = new GoodsDebrisRel();
        // 已分配的分类集
        $assignDebrisIds = self::getDebrisIdsByGoodsId($goodsId);
        // 找出删除的分类
        $deleteDebrisIds = array_diff($assignDebrisIds, $debrisIds);
        if (!empty($deleteDebrisIds)) {
            $debris->deleteAll([
                ['goods_id', '=', $goodsId],
                ['debris_id', 'in', $deleteDebrisIds]
            ]);
        }
        // 找出添加的分类
        $newDebrisIds = array_diff($debrisIds, $assignDebrisIds);
        $dataset = [];
        foreach ($newDebrisIds as $debrisId) {
            $dataset[] = [
                'goods_id' => $goodsId,
                'debris_id' => $debrisId,
                'store_id' => self::$storeId,
            ];
        }
        return $debris->addAll($dataset);
    }

    /**
     * 获取指定商品的所有分类id
     * @param int $goodsId
     * @return array
     */
    public static function getDebrisIdsByGoodsId(int $goodsId)
    {
        $debris = new GoodsDebrisRel();
        return $debris->where('goods_id', '=', $goodsId)->column('debris_id');
    }

    public function rate()
    {
        // 筛选条件
        $query = $this->getQueryFilter([]);
        // 设置显示的销量 goods_sales
        $query->field(['(sales_initial + sales_actual) as goods_sales']);
        // 排序条件
        // 执行查询
        $list = $query->with(['images.file'])
            ->alias($this->name)
            ->field($this->getAliasFields($this->name, ['content']))
            ->where('is_delete', '=', 0)
            ->where('is_box','=',2)
            ->select();
        // 整理列表数据并返回
        return $this->setGoodsListData($list);
    }

    /**
     * 自增商品销量
     * @param int $goodsId
     * @return mixed
     */
    public static function setIncSales(int $goodsId ,int $goods_num = 1)
    {
        return (new static)->setInc($goodsId, 'sales_actual',$goods_num);
    }
}
