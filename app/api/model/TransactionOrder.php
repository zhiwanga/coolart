<?php
declare (strict_types = 1);

namespace app\api\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class TransactionOrder extends Model
{
    protected $name = 'transaction_order';
}