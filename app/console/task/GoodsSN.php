<?php


namespace app\console\task;


class GoodsSN extends Task
{
    // 当前任务唯一标识
    private $taskKey = 'UserGrade';

    // 任务执行间隔时长 (单位:秒)
    protected $taskExpire = 1;

    /**
     * 任务处理
     * @param array $param
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function handle(array $param)
    {
       var_dump($param);die;

    }
}