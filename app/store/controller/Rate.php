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

        $insert = [
            'withdrawal_rate'   => $input['withdrawal_rate'],
            'transac_rate'      => $input['transac_rate'],
            'remarks'           => $input['remarks'],
            'create_time'       => time()
        ];
        Db::name('rate_control')->insert($insert);

        return $this->renderSuccess([], 'success');
    }
}