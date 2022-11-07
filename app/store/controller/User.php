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

use app\store\model\User as UserModel;

/**
 * 用户管理
 * Class User
 * @package app\store\controller
 */
class User extends Controller
{

    /**
     * 商品列表
     * @return Json
     * @throws \think\db\exception\DbException
     */
    public function allList()
    {
        // 获取列表记录
        $model = new UserModel();
        $list = $model->getAllList($this->request->param());

        return $this->renderSuccess(compact('list'));
    }
    /**
     * 用户列表
     * @return array
     * @throws \think\db\exception\DbException
     */
    public function list()
    {
        // 用户列表
        $model = new UserModel;
        $list = $model->getList($this->request->param());

        return $this->renderSuccess(compact('list'));
    }

    /**
     * 删除用户
     * @param int $userId
     * @return array
     */
    public function delete(int $userId)
    {
        // 用户详情
        $model = UserModel::detail($userId);
        if ($model->setDelete()) {
            return $this->renderSuccess('删除成功');
        }
        return $this->renderError($model->getError() ?: '删除失败');
    }

    /**
     * 添加会员
     */
    public function add(){

        $model = new UserModel();
        if ($model->add($this->postForm())) {
            return $this->renderSuccess('添加成功');
        }
        return $this->renderError($model->getError() ?: '添加失败');
    }

    /**
     * 用户充值
     * @param int $userId
     * @param string $target
     * @return array
     */
    public function recharge(int $userId, string $target)
    {
        // 用户详情
        $model = UserModel::detail($userId);
        if ($model->recharge($target, $this->postForm())) {
            return $this->renderSuccess('操作成功');
        }
        return $this->renderError($model->getError() ?: '操作失败');
    }

    /**
     * 修改会员等级
     * @param int $userId
     * @return array
     */
    public function grade(int $userId)
    {
        // 用户详情
        $model = UserModel::detail($userId);
        if ($model->updateGrade($this->postForm())) {
            return $this->renderSuccess('操作成功');
        }
        return $this->renderError($model->getError() ?: '操作失败');
    }

    /**
     * 批量空投
     * @param $userIds
     * @param $goods_id
     * @return string
     */
    public function batchAirdrop($userIds,$goodsId,$type=0)
    {
        $model = new UserModel();
        $res = $model->batchAirdrop($userIds,$goodsId,$type);
        if ($res) {
            return $this->renderSuccess('操作成功');
        }
        return $this->renderError('操作失败');
    }

}
