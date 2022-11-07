<?php
// +----------------------------------------------------------------------
// | 萤火商城系统 [ 致力于通过产品和服务，帮助商家高效化开拓市场 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2021 https://www.yiovo.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed 这不是一个自由软件，不允许对程序代码以任何形式任何目的的再发行
// +----------------------------------------------------------------------
// | Author: 萤火科技 <admin@yiovo.com>
// +----------------------------------------------------------------------
declare (strict_types=1);

namespace app\common\model;

use cores\BaseModel;
use think\model\relation\HasMany;

/**
 * 文件库模型
 * Class Transaction
 * @package app\common\model
 */
class Transaction extends BaseModel
{
    // 定义表名
    protected $name = 'transaction_log';

    // 定义主键
    protected $pk = 'id';

    // 定义时间戳字段名
    protected $createTime = 'createtime';

    // 追加的字段
    protected $append = [
    ];

    public function goods()
    {
        return $this->hasOne("GoodsImage",'goods_id','goods_id');
    }

    public function goodsinfo()
    {
         return $this->hasOne("Goods",'goods_id','goods_id')->field('goods_id,get_total,sales_initial,sales_actual,xn_sale');
    }

    public function goodssn(){

        return $this->hasOne("app\common\model\GoodsSn",'coll_id','coll_id');
    }

    /**
     * 获取二级市场列表
     * @param array $param
     * @param int $listRows
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function transactionLog(array $param = [], int $listRows = 15)
    {
        // 执行查询
        $list = $this->with(['goods.file'])
            ->order('createtime desc')
            ->paginate($listRows);

        $user = new User();
        foreach ($list as &$value){
            $value['sell_username'] = $user->where('user_id',$value['user_id'])->value('nick_name');
            $value['buy_username'] = $user->where('user_id',$value['buyer_id'])->value('nick_name');
            $value['goods']['images'] = $value['goods']['file']['preview_url'];
            unset($value['goods']['file']);
        }

        return $list;
    }
}