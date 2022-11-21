<?php

declare (strict_types=1);

namespace app\store\controller;

use think\facade\Db;

/**
 * 持有分类下的藏品可以0元购规则
 */
class HoldAppoint extends Controller
{
    public function create()
    {
        $input = input();
        $classify = implode(',', $input['classify']);
        try {
            $cate = Db::name('category')->field('name')->whereIn('category_id', $classify)->select()->toArray();
            $blind_name = Db::name('coll')->where('goods_id', $input['goods_id'])->value('blind_name');
            if(!$cate) {
                return $this->renderError('分类不存在');
            }
            $classify_name = implode(',', array_column($cate, 'name'));
            $insert = [
                'classify'          => $classify,
                'blind_id'          => $input['blind_id'],
                'blind_name'        => $blind_name,
                'classify_name'     => $classify_name,
                'number'            => $input['number'],
                'max_number'        => $input['max_number'],
                'remarks'           => $input['remarks'],
                'create_time'       => time()
            ];
            Db::name('hold_appoint')->insert($insert);
            return $this->renderSuccess([], 'success');
        } catch (\Throwable $th) {
            return $this->renderError($th->getMessage());
        }
    }

    public function update()
    {
        $input = input();
        $classify = implode(',', $input['classify']);
        try {
            $cate = Db::name('category')->field('name')->whereIn('category_id', $classify)->select()->toArray();
            $blind_name = Db::name('blind')->where('id', $input['blind_id'])->value('blind_name');
            if(!$cate) {
                return $this->renderError('分类不存在');
            }
            $classify_name = implode(',', array_column($cate, 'name'));
            $update = [
                'classify'          => $classify,
                'classify_name'     => $classify_name,
                'blind_id'          => $input['blind_id'],
                'blind_name'        => $blind_name,
                'number'            => $input['number'],
                'max_number'        => $input['max_number'],
                'remarks'           => $input['remarks'],
                'update_time'       => time()
            ];
            $res = Db::name('hold_appoint')->where('id', $input['id'])->update($update);
            if($res) {
                return $this->renderSuccess([], 'success');
            }else{
                return $this->renderError('更新失败');
            }
        } catch (\Throwable $th) {
            return $this->renderError($th->getMessage());
        }
    }

    public function delete()
    {
        $input = input();
        try {
            $res = Db::name('hold_appoint')->where('id', $input['id'])->delete();
            if($res) {
                return $this->renderSuccess([], 'success');
            }else{
                return $this->renderError('删除失败');
            }
        } catch (\Throwable $th) {
            return $this->renderError($th->getMessage());
        }
    }

    public function info()
    {
        $input = input();
        try {
            $info = Db::name('hold_appoint')
                        ->field('id, classify, blind_id, blind_name, max_number, number, classify_name, remarks, from_unixtime(create_time,"%Y-%m-%d %H:%i") create_time, from_unixtime(update_time,"%Y-%m-%d %H:%i") update_time')
                        ->where('id', $input['id'])
                        ->find();
            $info['classify']       = explode(',', $info['classify']);
            $info['classify_name']  = explode(',', $info['classify_name']);
            return $this->renderSuccess($info, 'success');
        } catch (\Throwable $th) {
            return $this->renderError($th->getMessage());
        }
    }

    public function list()
    {
        $list = Db::name('hold_appoint')
                    ->field('id, classify_name, blind_name, max_number, number, remarks, from_unixtime(create_time,"%Y-%m-%d %H:%i") create_time, from_unixtime(update_time,"%Y-%m-%d %H:%i") update_time')
                    ->select()
                    ->toArray();
        
        return $this->renderSuccess($list, 'success');
    }
}