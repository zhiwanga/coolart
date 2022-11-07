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
declare (strict_types = 1);

namespace app\api\controller;

use app\api\model\Goods as GoodsModel;
use app\store\model\Setting;

/**
 * 商品控制器
 * Class Goods
 * @package app\api\controller
 */
class Goods extends Controller
{
    /**
     * 商品列表
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function list()
    {
        // 获取列表数据
        $model = new GoodsModel;
        $list = $model->getList($this->request->param());

        return $this->renderSuccess(compact('list'));
    }

    /**
     * 获取商品详情
     * @param int $goodsId
     * @return array|\think\response\Json
     * @throws \app\common\exception\BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function detail(int $goodsId)
    {
        // 商品详情
        $model = new GoodsModel;
        $goodsInfo = $model->getDetails($goodsId);
        return $this->renderSuccess(['detail' => $goodsInfo]);
    }

    /**
     * 前端商品列表接口
     * @return json|\think\response\Json
     */
    public function lista(){
        
        $gModel=new GoodsModel;

        $user_id = $this->request->param('user_id','0');

        $keyword = $this->request->param('keyword','');

        $goodsInfo=$gModel->lista(15,intval($user_id),$keyword);

        return $this->renderSuccess(['product'=>$goodsInfo,'time'=>date('Y-m-d H:i:s')]);
    }

    /**
     * 定时器任务，每分钟查询所有订单状态，将超过15分钟的未付款订单改为已取消
     */
    public function setTimePlan(){
        $gModel=new GoodsModel;
        $orderNo=$gModel->orderCancel();
        if(!$orderNo){
            return '未修改';
        }
        return '已修改';
    }

    /**
     * 空投
     */
    public function airdrop(){

        $storeModel=new GoodsModel;
        //获取邀请人数限制和空投间隔天数
        $arr=$storeModel->getkong();

        $getManCount=$arr['invita'];
        $getFatime=$arr['fatime'];
        $getnewtime=$arr['newtime'];

        //执行空投功能
        $arrList=$storeModel->getUsers($getManCount,$getFatime,$getnewtime);

        if($arrList==false){
            return '空投失败，请联系管理员！';
        }
        return '空投成功！';
    }



    /**
     * 前端商品详情接口
     * 商品ID  $goodsid
     */
    public function goodsdetails(int $goodsid){
        $gModel=new GoodsModel;
        $goodsInfo=$gModel->goodsDetails($goodsid);
        $gb_time = Setting::getItem('box',10001)['price'];
        return $this->renderSuccess(['goodsdetails'=>$goodsInfo,'gb_time'=>$gb_time,'time'=>date('Y-m-d H:i:s')]);
    }

    /**
     * 查询关于我们（前台）
     */
    public function chakeAbout(){

        $model=new GoodsModel;
        $aboutUs=$model->chackAbotUs();

        return $this->renderSuccess(['data' => $aboutUs],'查询成功！');
    }

    /**
     * 查询盲盒价格
     */
    public function chaBOxM(){
        $Checkout = new GoodsModel;
        //查询盲盒价格
        $boxMoney=$Checkout->boxMoneys();
        return $this->renderSuccess(['date'=>$boxMoney],'查询成功');
    }
    
    //测试接口
    public function tet(){
         // 密码字符集，可任意添加你需要的字符
        $chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
        
        $length=40;
        
        $str = '0x';
        for($i = 0; $i < $length; $i++)
        {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /**
     * 暂未发售
    **/
    public function notSale(){
       $Checkout = new GoodsModel;
       $saleList=$Checkout->notSaleMode();
       return $this->renderSuccess($saleList,'查询成功');
    }

    /**
     * 合成商品详情接口
     * 商品ID  $goodsid
     */
    public function debrisDetails(int $goodsId){
        $gModel = new GoodsModel;
        $goodsInfo = $gModel->debrisDetails($goodsId);
        return $this->renderSuccess(['goodsdetails'=>$goodsInfo]);
    }

    public function rareList()
    {
        $model = new GoodsModel;
        $data = $model->rate();
        return $this->renderSuccess(compact('data'));
    }

    /**
     * 合成
     * 商品ID  $goodsid
     */
    public function compound(int $goodsId){
        $gModel = new GoodsModel;
        $res = $gModel->compound($goodsId);
        if (!$res){
            return $this->renderError('合成失败');
        }
        return $this->renderSuccess('合成成功');
    }
    
    public function observationList()
    {
        $where = [
            'is_delete' => 0,
            'is_box' => 0,
        ];
        $data = Db::name('goods')
            ->alias('g')
            ->join(['yoshop_goods_image'=>'gi'],'g.goods_id=gi.goods_id')
            ->join(['yoshop_upload_file'=>'uf'],'gi.image_id=uf.file_id')
            ->field('g.goods_name, g.goods_price_min, uf.file_path')
            ->where($where)
            ->select()
            ->toArray();

            var_dump($data);
            // https://coolart.space/uploads/
    }
}
