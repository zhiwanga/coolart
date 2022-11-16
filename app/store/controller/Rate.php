<?php

declare (strict_types=1);

namespace app\store\controller;

use think\facade\Db;

/**
 * 费率控制
 */
class Rate extends Controller
{
    public function create()
    {
        $input = input();

        try {
            $insert = [
                'withdrawal_rate'   => $input['withdrawal_rate'],
                'transac_rate'      => $input['transac_rate'],
                'remarks'           => $input['remarks'],
                'create_time'       => time()
            ];
            Db::name('rate_control')->insert($insert);
            return $this->renderSuccess([], 'success');
        } catch (\Throwable $th) {
            return $this->renderError($th->getMessage());
        }
    }

    public function update()
    {
        $input = input();

        try {
            $update = [
                'withdrawal_rate'   => $input['withdrawal_rate'],
                'transac_rate'      => $input['transac_rate'],
                'remarks'           => $input['remarks'],
                'update_time'       => time()
            ];
            $res = Db::name('rate_control')->where('id', $input['id'])->update($update);
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
            $res = Db::name('rate_control')->where('id', $input['id'])->delete();
            if($res) {
                return $this->renderSuccess([], 'success');
            }else{
                return $this->renderError('删除失败');
            }
        } catch (\Throwable $th) {
            return $this->renderError($th->getMessage());
        }
    }

    public function list()
    {
        $list = Db::name('rate_control')->field('id, withdrawal_rate, transac_rate, remarks, from_unixtime(create_time,"%Y-%m-%d %H:%i") create_time, from_unixtime(update_time,"%Y-%m-%d %H:%i") update_time')->select()->toArray();
        return $this->renderSuccess($list, 'success');
    }
}