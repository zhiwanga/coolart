<?php


namespace app\api\controller;

use app\common\model\Make as MakeModel;
use think\App;
use think\Exception;
use ZipStream\Exception\EncodingException;

class Make extends Controller
{
    public $model;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new MakeModel();
    }

    public function setDraw(int $goodsid){

        $user_id = \app\api\service\User::getCurrentLoginUserId();

        $goods_info = \app\api\model\Goods::get($goodsid);

        if(empty($goods_info)){

            return $this->renderError('未找到商品数据');
            
        }

        $make_info = $this->model
            ->where('user_id',$user_id)
            ->where('goods_id',$goodsid)->where('type',0)->find();

        if(!empty($make_info)){

            return $this->renderError('您已参与,请等待结果');

        }

        if($goods_info['maketime'] > time()){

            return $this->renderError('未到发签时间,请稍后');

        }

        $this->model->startTrans();
        try {

            $res = $this->model->create([
                'goods_id'      => $goods_info['goods_id'],
                'user_id'       => $user_id,
                'store_id'      => 10001,
                'goods_name'    => $goods_info['goods_name'],
            ]);

            if(!$res){

                throw new Exception('参与失败');

            }

            $this->model->commit();
            return $this->renderSuccess([],'操作成功');
        }catch (Exception $e){

            $this->model->rollback();
            return $this->renderError($e->getMessage());
        }

    }
}