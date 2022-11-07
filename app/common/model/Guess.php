<?php


namespace app\common\model;


use cores\BaseModel;

class Guess  extends BaseModel
{
    // 定义表名
    protected $name = 'guess';

    // 定义主键
    protected $pk = 'id';

    public function setStarttimeAttr($value){

        return strtotime($value);

    }

    public function setEndtimeAttr($value){

        return strtotime($value);
        
    }
}
