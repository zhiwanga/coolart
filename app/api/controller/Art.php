<?php


namespace app\api\controller;


use app\api\service\User as UserService;
use think\App;
use think\Exception;


/**
 * 艺术家
 * Class Art
 * @package app\api\controller
 */
class Art extends Controller
{

    public $model;

    public function __construct(App $app)
    {
        $this->model = new \app\common\model\Goods();

        parent::__construct($app);
    }

    /**
     * 添加上传配送
     */
    public function add(){

        $user_id = UserService::getCurrentLoginUserId();

        $params = $this->request->param();
        $model = new \app\store\model\Goods();

        $data = [
            'spec_type'     => 10,
            'goods_name'    => $params['goods_name'],
            'imagesIds'     => $params['imagesIds'],
            'is_limit'      => $params['is_limit'],
            'is_hide'       => $params['is_hide'],
            'type'          => $params['type'],
            'goods_price'   => $params['goods_price'],
            'stock_num'     => $params['stock_num'],
            'content'       => $params['content'],
            'startTime'     => $params['startTime'],
            'author'        => $params['author'],
            'stock_total'   => $params['stock_num'],
            'store_id'      => 10001,
            'is_shop'       => 1,
            'user_id'       =>  $user_id,
            'newSkuList'    => [
                'goods_price'   => $params['goods_price'],
                'stock_num'     => $params['stock_num'],
                'goods_weight'  => 0,
                'line_price'    => 0,
            ],
            'is_enable_grade'=> 0,
            'categoryIds'    => [10001],
            'serviceIds'     => [10001,10003]
        ];

        if ($model->add($data)) {
            return $this->renderSuccess('添加成功');
        }

        return $this->renderError($model->getError() ?: '添加失败');
    }

    /**
     * 艺术家列表
     */
    public function art_list(){

        $user_list = \app\common\model\User::with(['avatar'])->where('is_art',1)
            ->field('user_id,mobile,nick_name,avatar_id')
            ->order('user_id desc')
            ->paginate();

        return $this->renderSuccess(['user_list'=>$user_list]);

    }

    /**
     * 排行榜-拉新
     */
    public function push_rank(){

        $user_rank_list = \app\common\model\User::with(['avatar'])
            ->field('user_id,mobile,avatar_id,nick_name,num')
            ->order('num desc')
            ->limit(50)
            ->select();
        foreach ($user_rank_list as $k => $v) {

            $user_rank_list[$k]['mobile'] = substr($v['mobile'], 0, 3).'********';
            $user_rank_list[$k]['nick_name'] = '';
        }
        return $this->renderSuccess(['user_rank_list'=>$user_rank_list]);
    }

    /**
     * 排行榜-拉新
     */
    public function pay_rank(){

        $user_rank_list = \app\common\model\User::with(['avatar'])
            ->field('user_id,mobile,avatar_id,nick_name,pay_money')
            ->order('pay_money desc')
            ->limit(50)
            ->select();

        return $this->renderSuccess(['order_rank_list'=>$user_rank_list]);

    }
}
