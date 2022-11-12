<?php
declare (strict_types = 1);

namespace app\controller\market;

use app\model\market\RealtGoods;
use app\api\service\User as UserService;
use app\controller\Rsa;
use think\facade\Db;

class Observation
{
    /**
     * 显示资源列表
     * @return \think\Response
     */
    public function list()
    {
        $result = RealtGoods::field('image, name, price')->select()->toArray();

        return json($result);
    }

    public function data()
    {
        $result = RealtGoods::field('image, name, price, circula_number number')->select()->toArray();

        return json($result);
    }
    public function test()
    {
        // $str = 'Nhx+kARSc9mkEjz6I2FIp52N4kJU3aD2yYTNLPbHMKZjqvMn/BhFG0prNPVYqdFS9mSef+e79pwHfB4fymPezpjdZMASl+h3+d0ksApnRiHMtKtLeV+7sz2r9pqK7SCnH/5Z3YZdqhJEDvdr6SQilv8pLMZ5a9TrTMeLPfySi/U=';
        // echo Rsa::privDecrypt($str);

        $list = Db::name('goods_sn')
                    ->alias('a')
                    ->leftJoin('transaction_log b', 'a.coll_id = b.coll_id')
                    ->leftJoin('goods c', 'a.goods_id = c.goods_id')
                    ->field('a.number, c.xn_sale, b.price, a.status')
                    ->where('a.goods_id', 101)
                    ->where('a.number', '<>', 11)
                    ->select()
                    ->toArray();
                    var_dump($list);die;
    }
}
