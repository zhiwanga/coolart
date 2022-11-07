<?php


namespace app\common\model;


use app\common\enum\order\PayType as OrderPayTypeEnum;
use app\common\enum\user\balanceLog\Scene as SceneEnum;
use app\common\library\Sd;
use app\common\model\user\BalanceLog;
use cores\BaseModel;
use think\Exception;
use think\facade\Db;

class BlindLog extends BaseModel
{

    public $name = 'blind_log';

    protected $pk = 'id';

    public function file(){

        return $this->belongsTo('app\common\model\UploadFile','thumb','file_id');
    }

    public function blind(){

        return $this->belongsTo('app\common\model\Blind','blind_id','id');
    }

}