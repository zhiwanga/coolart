<?php
// +----------------------------------------------------------------------
// | 控制台配置
// +----------------------------------------------------------------------
return [
    // 指令定义
    'commands' => [
        // 定时任务
        'timer' => \app\console\command\Timer::class,
        'app\command\Nft',
        'app\command\AddWallet',
        'app\command\GoodsAdd'
    ],
];
