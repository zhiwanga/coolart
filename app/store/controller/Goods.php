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

namespace app\store\controller;

use think\response\Json;
use app\store\model\Goods as GoodsModel;
use app\common\exception\BaseException;
use app\model\market\RealtGoods;

/**
 * 商品管理控制器
 * Class Goods
 * @package app\store\controller
 */
class Goods extends Controller
{
    /**
     * 商品列表
     * @return Json
     * @throws \think\db\exception\DbException
     */
    public function allList(): Json
    {
        // 获取列表记录
        $model = new GoodsModel;
        $list = $model->getAllList($this->request->param());
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 商品列表
     * @return Json
     * @throws \think\db\exception\DbException
     */
    public function list(): Json
    {
        // 获取列表记录
        $model = new GoodsModel;
        $list = $model->getList($this->request->param());
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 盲盒列表
     */
    public function boxList(){
        // 获取列表记录
        $model = new GoodsModel;
        $list = $model->getBoxList($this->request->param());
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 稀有藏品列表
     */
    public function rareList(){
        // 获取列表记录
        $model = new GoodsModel;
        $list = $model->getRareList($this->request->param());
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 碎片列表
     */
    public function debrisList(){
        // 获取列表记录
        $model = new GoodsModel;
        $list = $model->getDebrisList($this->request->param());
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 空投藏品列表
     */
    public function airdropList(){
        // 获取列表记录
        $model = new GoodsModel;
        $list = $model->getAirdropList($this->request->param());
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 根据商品ID集获取列表记录
     * @param array $goodsIds
     * @return Json
     */
    public function listByIds(array $goodsIds): Json
    {
        // 获取列表记录
        $model = new GoodsModel;
        $list = $model->getListByIds($goodsIds);
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 商品详情
     * @param int $goodsId
     * @return Json
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \cores\exception\BaseException
     */
    public function detail(int $goodsId): Json
    {
        // 获取商品详情
        $model = new GoodsModel;
        $goodsInfo = $model->getDetail($goodsId);

        return $this->renderSuccess(compact('goodsInfo'));
    }

    /**
     * 添加商品
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \cores\exception\BaseException
     */
    public function add(): Json
    {
        $model = new GoodsModel;
        if ($model->add($this->postForm())) {
            return $this->renderSuccess('添加成功');
        }
        return $this->renderError($model->getError() ?: '添加失败');
    }

    /**
     * 添加盲盒
     */
    public function addBox(){
        $model = new GoodsModel;
        if ($model->addBox($this->postForm())) {
            return $this->renderSuccess('添加成功');
        }
        return $this->renderError($model->getError() ?: '添加失败');
    }


    /**
     * 编辑商品
     * @param int $goodsId
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \cores\exception\BaseException
     */
    public function edit(int $goodsId): Json
    {
        // 商品详情
        $model = GoodsModel::detail($goodsId);
        // 更新记录
        if ($model->edit($this->postForm())) {
            return $this->renderSuccess('更新成功');
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }

    /**
     * 修改商品状态(上下架)
     * @param array $goodsIds 商品id集
     * @param bool $state 为true表示上架
     * @return Json
     */
    public function state(array $goodsIds, bool $state): Json
    {
        $model = new GoodsModel;
        if (!$model->setStatus($goodsIds, $state)) {
            return $this->renderError($model->getError() ?: '操作失败');
        }
        // 上架把商品加到第三方实时商品表
        if($state) {
            foreach ($goodsIds as $value) {
                RealtGoods::add($value);
            }
        }
        return $this->renderSuccess('操作成功');
    }

    /**
     * 删除商品
     * @param array $goodsIds
     * @return Json
     */
    public function delete(array $goodsIds): Json
    {
        $model = new GoodsModel;
        if (!$model->setDelete($goodsIds)) {
            return $this->renderError($model->getError() ?: '删除失败');
        }
        return $this->renderSuccess('删除成功');
    }
    
    
    /**
     * 修改盲盒价格
     */
    public function setBoxMoney(){
        $model = new GoodsModel;
        //获取传过来的参数
        $posta=$this->postData();
        $boxMoney=$posta['boxMoney'];
        
        $setBox=$model->setBoxMoneyModel($boxMoney);

        if($setBox==0){
            return $this->renderError( '修改失败');
        }
        return $this->renderSuccess(['data'=>""],'修改成功');
    }
    
    /**
     * 
     **/
     public function getBoxMoney(){
        $model = new GoodsModel;
        $getBoxMoney=$model->getBoxMoneyModel();
        return $this->renderSuccess(['data'=>$getBoxMoney],'查询成功'); 
     }
     
}
