<?php 
namespace app\api\model;




class good {

    /**
     * 首页商品展示数据接口
     * @return arr
     * 
     * 商品图片
     * 商品标题
     * 商品总数量
     * 商品剩余数量
     * 上架时间
     * 价格
     */
    public function lista(){
        try {
            //查询所有上架的商品信息
            $list=Goods::where('status',10)
                ->order('create_time')
                ->select();
            $listarr =array();
            if ($list->isEmpty()) {
                return $list;
            }
            foreach ($list as $key => $goods) {
                $listarr[]=$goods[$key];
            }
            return $listarr;
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }
}
?>