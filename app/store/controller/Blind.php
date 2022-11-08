<?php


namespace app\store\controller;


use app\common\exception\BaseException;
use app\common\library\helper;
use app\store\controller\Controller;
use app\common\model\Blind as GoodsModel;
use think\App;
use think\Exception;
use think\response\Json;

/**
 * 盲盒
 * Class Blind
 * @package app\store
 */
class Blind extends Controller
{

    protected $model;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new GoodsModel();

    }

    public function alllist() :Json
    {


    }

    public function list() :Json
    {
        $params = $this->request->param();

        $where = [];

        if(!empty($params['keyword'])){

            $where['blind_name'] = ['LIKE',$params['keyword']];

        }

        $lists = $this->model->where($where)
            ->where('is_delete',0)
            ->order('id desc')
            ->paginate(15);

        return $this->renderSuccess(compact('lists'));
    }

    public function add()
    {

        $this->model->startTrans();
        try {
            $params = $this->postForm('data');

            if(empty($params['goods_ids'])){

                return $this->renderError('请添加盲盒商品');

            }

            $params['store_id'] = 10001;

            $res= $this->model->save($params);

            $this->model->commit();
            return $this->renderSuccess('添加成功');
        }catch (Exception $e){

            $this->model->rollback();
            return $this->renderError($e->getError() ?: '添加失败');
        }
    }

    public function edit()
    {

        if($this->request->isPost()){

            $this->model->startTrans();
            try {

                $params = $this->postForm('data');

                $row = $this->model->where('id',$params['id'])->find();unset($params['id']);

                if(!is_array($params['thumb'])){

                    unset($params['thumb']);

                }

                if(empty($params['goods_ids'])){

                    return $this->renderError('请添加盲盒商品');

                }

                $res= $row->save($params);

                $this->model->commit();
                return $this->renderSuccess('编辑成功');
            }catch (Exception $e){

                $this->model->rollback();
                return $this->renderError($e->getMessage() ?: '编辑失败');
            }
        }

    }


    public function detail(int $goodsid): Json
    {
        // 获取商品详情
        $goodsInfo = $this->model->where('id',$goodsid)->find();

        //商品详情
        $goodsInfo['goods'] = \app\common\model\Goods::with(['images.file'])
            ->where('goods_id','in',$goodsInfo['goods_ids'])
            ->field('goods_id,goods_name,sales_initial,sales_actual,probability')
            ->select();

        foreach($goodsInfo['goods'] as &$g){

            $goods_images = helper::getArrayColumn( $g['images'], 'file');

            // 商品主图
            $g['goods_image'] = current($goods_images)['preview_url'];

        }unset($g);


        return $this->renderSuccess(compact('goodsInfo'));
    }

    public function del()
    {
        $goods_ids = $this->request->param('goodsid');

        if(is_array($goods_ids)){

            $goods_ids = implode(',',$goods_ids);

        }

        // 获取商品详情
        $goodsInfo = $this->model->where('id','in',$goods_ids)->find();

        if(empty($goodsInfo)){

            return $this->renderError('商品已删除');

        }

        $res = $goodsInfo->save([
            'is_delete' => 1,
        ]);

        if(!$res){

            return $this->renderError('删除失败');

        }else{

            return $this->renderSuccess('成功','成功');


        }
    }
}