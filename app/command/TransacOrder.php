<?php

namespace app\command;

use app\api\model\TransactionOrder;
use think\console\Command;
use think\console\Input;
use think\console\Output;

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
        $res = TransactionOrder::where('create_time', '<', $time)->where('status', 2)->update(['status'=> 3]);
        echo '更新数据：'. $res;
    }
}