<?php
declare (strict_types = 1);

namespace app\controller\market;

use app\controller\PhpJwt;
use app\model\market\RealtGoods;
use think\facade\Request;

class StaringChain
{
    public function __construct()
    {
        $token = Request::header('token');

        $res = PhpJwt::jwtencrypt($token);
        if(!$res['code']) {
            header("Status: 403");exit(json_encode(['code'=> 403, 'msg'=>$res['msg']]));
        }
    }

    /**
     * 显示资源列表
     * @return \think\Response
     */
    public function list()
    {
        $result = RealtGoods::field('image, name, price')->select()->toArray();
        $result = [
            'data' => $result
        ];
        return json($result);
    }

    public function data()
    {
        $result = RealtGoods::field('name, price, circula_number total')->select()->toArray();
        $result = [
            'data' => $result
        ];
        return json($result);
    }
}
