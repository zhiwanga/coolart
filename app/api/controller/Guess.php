<?php


namespace app\api\controller;


use app\api\service\User as UserService;
use app\common\model\GuessLog;
use app\common\model\UploadFile;
use think\App;
use think\Exception;
use app\common\model\Guess as GuessModel;
use think\facade\Db;


class Guess extends Controller
{
    /**
     * 列表
     */
    public function lists(){

        $user = \app\api\service\User::getCurrentLoginUser();

        $lists = GuessModel::where('starttime','<=',time())
            ->where('endtime','>=',time())
            ->where('is_delete',0)
            ->order('id desc')
            ->paginate();

        foreach($lists as &$value){

            $v1_file = UploadFile::where('file_id',$value['v1_image'])->find();

            $v2_file = UploadFile::where('file_id',$value['v2_image'])->find();

            $value['v1_image'] = $v1_file['preview_url'];

            $value['v2_image'] = $v2_file['preview_url'];

            $guess_log = GuessLog::where('guess_id',$value['id'])
                ->where('user_id',$user['user_id'])
                ->find();

            if($guess_log['class_name'] == $value['v1_country']){

                $value['v1_type'] = 1;

            }else{

                $value['v1_type'] = 0;
            }

            if($guess_log['class_name'] == $value['v2_country']){

                $value['v2_type'] = 1;

            }else{

                $value['v2_type'] = 0;
            }

            $value['start'] = date('Y-m-d H:i:s',$value['starttime']);

            $value['end'] = date('Y-m-d H:i:s',$value['endtime']);
        }

        return $this->renderSuccess(['lists'=>$lists]);

    }

    /**
     * 参与竞猜
     * @param $guess_id
     * @param $country
     * @param $num
     * @return \think\response\Json
     * @throws \cores\exception\BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function set_guess($guess_id,$country,$num){

        $guess_info = GuessModel::where('id',$guess_id)
            ->where('is_delete',0)
            ->find();

        if(empty($guess_info)){

            return $this->renderError('该竞猜时间已结束');

        }

        if(!in_array($country,[$guess_info['v1_country'],$guess_info['v2_country']])){

            return $this->renderError('数据异常');

        }

        if($num <= 0){

            return $this->renderError('请输入正确是数量');

        }

        $user = \app\api\service\User::getCurrentLoginUser();

        $guess_log = GuessLog::where('user_id',$user['user_id'])
            ->where('guess_id',$guess_info['id'])
            ->find();

        if(!empty($guess_log)){

            return $this->renderError('您已经参与该竞猜');

        }

        if($user['points'] < $num){

            return $this->renderError('藏品券不足');

        }

        Db::startTrans();
        try {

            GuessLog::create([
                'guess_id'  => $guess_id,
                'user_id'   => $user['user_id'],
                'class_name'=> $country,
                'num'       => $num
            ]);

            \app\common\model\User::setIncPoints((int)$user['user_id'],-$num,"参与竞猜");

            Db::commit();
            return $this->renderSuccess('success');
        }catch (Exception $ed){

            Db::rollback();
            return $this->renderError($ed->getMessage());
        }
    }

    /**
     * 藏品券互转
     */
    public function huzhuan(){

        $user = \app\api\service\User::getCurrentLoginUser();

        $keyword = $this->request->param('keyword','');

        $get_info = \app\common\model\User::where('user_id|mobile','=',$keyword)->find();

        if($get_info['user_id'] == $user['user_id'] || empty($get_info)){

            return $this->renderError('没有找到该用户信息');

        }

        $num = $this->request->param('num',0);

        if($num <= 0){

            return $this->renderError('请输入正确的数量');

        }

        Db::startTrans();
        try {

            $res = \app\common\model\User::setIncPoints((int)$user['user_id'],-$num,"转赠给ID:".$get_info['user_id']);

            if(!$res){

                throw new Exception('藏品券不足',100);

            }

            $res2 =  \app\common\model\User::setIncPoints((int)$get_info['user_id'],$num,"ID:".$user['user_id'].'转赠');

            if(!$res2){

                throw new Exception('转赠异常',100);

            }

            Db::commit();

            return $this->renderSuccess('操作成功');
        }catch (Exception $e){

            Db::rollback();
            if($e->getCode() == 100){

                return $this->renderError($e->getMessage());

            }else{

                return $this->renderError('转赠失败了,请检查藏品券是否充足');

            }
        }
    }
}
