<?php


namespace app\common\model;


use cores\BaseModel;

class GuessLog  extends BaseModel
{
    // 定义表名
    protected $name = 'guess_log';

    // 定义主键
    protected $pk = 'id';

    public function guess(){

        return $this->belongsTo('Guess','guess_id');

    }
}
