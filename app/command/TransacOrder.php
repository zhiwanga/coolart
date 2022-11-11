<?php

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use app\api\model\Order as OrderModel;

class TransacOrder extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('transacorder')
            ->setDescription('检查二级市场临时订单');

    }

    protected function execute(Input $input, Output $output)
    {
        $time  = time()-300;
        $res = OrderModel::where('create_time', '<', $time)->where('order_status', 10)->where('type', 1)->update(['is_delete'=> 1, 'order_status' => 20]);
        echo '更新数据：'. $res;
    }
}