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
            $goods_name = Db::name('goods')->where('goods_id', $input['goods_id'])->value('goods_name');
            $classify_name = implode(',', array_column($cate, 'name'));
            $insert = [
                'classify'          => $classify.',',       // 可以零元购这些分类下面的商品
                'goods_id'          => $input['goods_id'],  // 持有此商品可以零元购
                'goods_name'        => $goods_name,         // 商品名称显示用
                'classify_name'     => $classify_name,      // 分类名称显示用
                // 'number'            => $input['number'],    // 持有几个可以零元购一次
                // 'max_number'        => $input['max_number'],// 总共可以零元购多少次
                'remarks'           => $input['remarks'],   // 备注信息
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
            $goods_name = Db::name('goods')->where('goods_id', $input['goods_id'])->value('goods_name');

            $classify_name = implode(',', array_column($cate, 'name'));
            $update = [
                'classify'          => $classify.',',
                'classify_name'     => $classify_name,
                'goods_id'          => $input['goods_id'],
                'goods_name'        => $goods_name,
                // 'number'            => $input['number'],
                // 'max_number'        => $input['max_number'],
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
                        ->field('id, classify, goods_id, goods_name, classify_name, remarks, from_unixtime(create_time,"%Y-%m-%d %H:%i") create_time, from_unixtime(update_time,"%Y-%m-%d %H:%i") update_time')
                        ->where('id', $input['id'])
                        ->find();
            $info['classify']       = explode(',', trim($info['classify'], ','));
            $info['classify_name']  = explode(',', $info['classify_name']);
            return $this->renderSuccess($info, 'success');
        } catch (\Throwable $th) {
            return $this->renderError($th->getMessage());
        }
    }

    public function list()
    {
        $list = Db::name('hold_appoint')
                    ->field('id, classify_name, goods_name, goods_id, remarks, from_unixtime(create_time,"%Y-%m-%d %H:%i") create_time, from_unixtime(update_time,"%Y-%m-%d %H:%i") update_time')
                    ->select()
                    ->toArray();

        return $this->renderSuccess($list, 'success');
    }
}