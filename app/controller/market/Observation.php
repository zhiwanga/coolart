<?php
declare (strict_types = 1);

namespace app\controller\market;

use app\model\market\RealtGoods;
use app\api\service\User as UserService;
use app\common\service\Transaction;
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
        $arr = [
            0 => ['coll_id' => 1, 'aa' => 1, 'bb' => 1],
            1 => ['coll_id' => 2, 'aa' => 2, 'bb' => 2],
            2 => ['coll_id' => 3, 'aa' => 3, 'bb' => 3],
            3 => ['coll_id' => 1, 'aa' => 4, 'bb' => 4],
        ];
        $r= $this->array_unique_fb($arr);
        var_dump($r);die;
    }
    public function array_unique_fb($array2D=[]){
        $temp = [];
        foreach ($array2D as $k=>$v){
            $temp[$v['coll_id']] = $v;
        }
        return array_values($temp);
    }
}
