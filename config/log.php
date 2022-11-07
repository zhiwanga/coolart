<?php

// +----------------------------------------------------------------------
// | 日志设置
// +----------------------------------------------------------------------
return [
    // 默认日志记录通道
    'default'      => env('log.channel', 'file'),
    // 日志记录级别
    'level'        => [],
    // 日志类型记录的通道 ['error'=>'email',...]
    'type_channel' => [],
    // 关闭全局日志写入
    'close'        => false,
    // 全局日志处理 支持闭包
    'processor'    => null,

    // 日志通道列表
    'channels'     => [
        'file' => [
            // 日志记录方式
            'type'           => 'File',
            // 日志保存目录
            'path'           => '',
            // 单文件日志写入
            'single'         => false,
            // 独立日志级别
            'apart_level'    => ['begin', 'error', 'sql', 'info'],
            // 最大日志文件数量
            'max_files'      => 0,
            // 使用JSON格式记录
            'json'           => false,
            // 日志处理
            'processor'      => null,
            // 关闭通道日志写入
            'close'          => false,
            // 日志时间格式
            'time_format'    => 'Y-m-d H:i:s',
            // 日志输出格式化
            'format'         => '[%s] [%s] %s',
            // 是否实时写入
            'realtime_write' => false,
        ],
        // 其它日志通道配置
    ],
    'log' => [
        'file' => './logs/pay.log', // 请注意权限
        'level' => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
        'type' => 'single', // optional, 可选 daily， daily 时将按时间自动划分文件.
        'max_file' => 30, // optional, 当 type 为 daily 时有效，默认 30 天
    ],

];


