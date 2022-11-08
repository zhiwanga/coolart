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

use app\store\model\Setting as SettingModel;
use app\common\library\sms\Driver as SmsDriver;
use think\response\Json;

/**
 * 系统设置
 * Class Setting
 * @package app\store\controller
 */
class Setting extends Controller
{
    /**
     * 获取设置项
     * @param string $key
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function detail(string $key): Json
    {
        $values = SettingModel::getItem($key);
        return $this->renderSuccess(compact('values'));
    }

    /**
     * 更新系统设置
     * @param string $key
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function update(string $key): Json
    {
        // 保存商城设置
        $model = new SettingModel;
        if ($model->edit($key, $this->postForm())) {
            return $this->renderSuccess('操作成功');
        }
        return $this->renderError($model->getError() ?: '操作失败');
    }

    /**
     * 修改关于我们
     */
    public function aboutUsValue(){
        //获取传过来的内容
        $posta=$this->postData();

        if(!isset($posta['weChe'])){
            return $this->renderError('操作失败');
        }
        if (!isset($posta['qq'])){
            return $this->renderError('操作失败');
        }
        $weChe=$posta['weChe'];
        $qq=$posta['qq'];
        $model=new SettingModel;
        $aboutUs=$model->abotUsAbs($weChe,$qq);
        return $this->renderSuccess(['type'=>$aboutUs],'修改成功');
    }

    /**
     * 查询关于我们（后台）
     */
    public function chakeAbout(){
        $model=new SettingModel;
        $aboutUs=$model->chackAbotUs();

        return $this->renderSuccess(['data' => $aboutUs],'查询成功！');
    }
}
