<?php


namespace app\store\controller;


use app\common\library\helper;
use app\store\model\UploadFile;
use think\App;
use think\Exception;
use think\facade\Db;
use app\common\model\Guess as GuessModel;
use think\response\Json;

class Guess extends Controller
{

    protected $model;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new GuessModel();

    }

    public function alllist() :Json
    {


    }

    public function list() :Json
    {
        $params = $this->request->param();

        $where = [];

        if(!empty($params['keyword'])){

            $where[] = ['title','LIKE','%'.$params['keyword'].'%'];

        }

        $lists = $this->model->where($where)
            ->where('is_delete',0)
            ->order('id desc')
            ->paginate(15);

        foreach($lists as &$value){

            $value['v1_thumb'] = UploadFile::where('file_id',$value['v1_image'])->select();

            $value['v2_thumb'] = UploadFile::where('file_id',$value['v2_image'])->select();

            $value['start'] = date('Y-m-d H:i:s',$value['starttime']);

            $value['end'] = date('Y-m-d H:i:s',$value['endtime']);
        }

        return $this->renderSuccess(compact('lists'));
    }

    public function add()
    {

        $this->model->startTrans();
        try {
            $params = $this->postForm('data');

            if(!isset($params['starttime']) || empty($params['starttime']) || !isset($params['endtime']) || empty($params['endtime'])){

                throw new Exception('请输入正确的时间');
            }

            $params['store_id'] = 10001;

            $res= $this->model->save($params);

            $this->model->commit();
            return $this->renderSuccess('添加成功');
        }catch (Exception $e){

            $this->model->rollback();
            return $this->renderError($e->getMessage() ?: '添加失败');
        }
    }

    public function edit()
    {

        if($this->request->isPost()){

            $this->model->startTrans();
            try {

                $params = $this->postForm('data');

                if(!isset($params['starttime']) || empty($params['starttime']) || !isset($params['endtime']) || empty($params['endtime'])){

                    throw new Exception('请输入正确的时间');
                }

                $row = $this->model->where('id',$params['id'])->find();

                $res= $row->save($params);

                $this->model->commit();
                return $this->renderSuccess('编辑成功');
            }catch (Exception $e){

                $this->model->rollback();
                return $this->renderError($e->getMessage() ?: '编辑失败');
            }
        }

    }


    public function detail(int $id): Json
    {
        // 获取商品详情
        $info = $this->model->where('id',$id)->find();

        $info['v1_thumb'] = UploadFile::where('file_id',$info['v1_image'])->select();

        $info['v2_thumb'] = UploadFile::where('file_id',$info['v2_image'])->select();

        $info['start'] = date('Y-m-d H:i:s',$info['starttime']);

        $info['end'] = date('Y-m-d H:i:s',$info['endtime']);

        return $this->renderSuccess(compact('info'));
    }

    public function del()
    {
        $id = $this->request->param('id');

        // 获取商品详情
        $info = $this->model->where('id','in',$id)->find();

        if(empty($info)){

            return $this->renderError('数据已删除');

        }

        $res = $info->save([
            'is_delete' => 1,
        ]);

        if(!$res){

            return $this->renderError('删除失败');

        }else{

            return $this->renderSuccess('成功','成功');


        }
    }
}
