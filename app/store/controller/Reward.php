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

use app\store\model\RewardLevel;

/**
 * 奖励等级
 * Class Reward
 * @package app\store\controller\user
 */
class Reward extends Controller
{
    /**
     * 列表记录
     * @return array
     * @throws \think\db\exception\DbException
     */
    public function list()
    {
        $model = new RewardLevel;
        $list = $model->getList($this->request->param());
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 全部记录
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function all()
    {
        $model = new RewardLevel;
        $list = $model->getAll($this->request->param());
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 添加等级
     * @return array|bool|mixed
     * @throws \Exception
     */
    public function add()
    {
        // 新增记录
        $model = new RewardLevel;
        if ($model->add($this->postForm())) {
            return $this->renderSuccess('添加成功');
        }
        return $this->renderError($model->getError() ?: '添加失败');
    }

    /**
     * 编辑会员等级
     * @param int $gradeId
     * @return array|bool|mixed
     */
    public function edit(int $gradeId)
    {
        // 会员等级详情
        $model = RewardLevel::detail($gradeId);
        // 更新记录
        if ($model->edit($this->postForm())) {
            return $this->renderSuccess('更新成功');
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }

    /**
     * 删除会员等级
     * @param int $gradeId
     * @return array
     */
    public function delete(int $gradeId)
    {
        // 会员等级详情
        $model = RewardLevel::detail($gradeId);
        if (!$model->setDelete()) {
            return $this->renderError($model->getError() ?: '删除失败');
        }
        return $this->renderSuccess('删除成功');
    }

}
