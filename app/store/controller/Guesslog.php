<?php


namespace app\store\controller;


use app\common\library\helper;
use app\store\model\UploadFile;
use think\App;
use think\Exception;
use think\facade\Db;
use app\common\model\GuessLog as GuessLogModel;
use think\response\Json;

class Guesslog extends Controller
{

    protected $model;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new GuessLogModel();

    }

    public function alllist() :Json
    {


    }

    public function list() :Json
    {
        $title = $this->request->param('title','');

        $keyword = $this->request->param('keyword','');

        $class_name = $this->request->param('class_name','');

        $betweenTime = $this->request->param('betweenTime','');

        $where = [];

        if(!empty($title)){

            $where[] = ['g.title','LIKE','%'.$title.'%'];

        }

        // 起止时间
        if (!empty($betweenTime)) {
            $times = between_time($betweenTime);
            $where[] = ['l.create_time', '>=', $times['start_time']];
            $where[] = ['l.create_time', '<', $times['end_time'] + 86400];
        }

        if(!empty($keyword)){

            $where[] = ['u.nick_name|u.mobile','like','%'.$keyword.'%'];

        }

        if(!empty($class_name)){

            $where[] = ['l.class_name','=',$class_name];

        }

        $lists = $this->model->alias('l')
            ->field('l.*,g.title,u.nick_name,u.mobile')
            ->where($where)
            ->leftJoin('yoshop_guess g','g.id=l.guess_id')
            ->leftJoin('yoshop_user u','u.user_id=l.user_id')
            ->order('l.id desc')
            ->paginate(15);

        return $this->renderSuccess(compact('lists'));
    }


}
