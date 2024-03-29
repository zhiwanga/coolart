<?php
declare (strict_types = 1);

namespace app\controller\market;

use app\model\market\RealtGoods;
use app\controller\Rsa;

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
        $res = rateLess(2, 2, 200);
        var_dump($res);die;
    }
}
