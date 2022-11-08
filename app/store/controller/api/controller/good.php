<?php 
namespace app\api\controller;

use think\DB;
use app\api\model\good as goodModel;

class good extends Controller
{
    /**
     * 商品列表
     * @return json
     */
    public function lista(){
        $gModel=new goodModel;
        $list=$gModel->lista();
        return $list;
    }
}
?>