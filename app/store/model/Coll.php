<?php


namespace app\store\model;


use think\Model;

class Coll extends Model
{

    protected $name = 'coll';

    public function getAddTimeAttr($value){

        return date('Y-m-d H:i:s',$value);
        
    }
}