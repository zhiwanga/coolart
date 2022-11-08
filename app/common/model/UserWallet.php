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
declare (strict_types=1);

namespace app\common\model;

use cores\BaseModel;
use think\Model;
use app\api\service\User;

/**
 * 模型类：用户收款信息
 * Class UserWallet
 * @package app\common\model
 */
class UserWallet extends BaseModel
{
    // 定义表名
    protected $name = 'user_wallet';

    // 定义主键
    protected $pk = 'id';

    public function edit($data)
    {
        $user_id = User::getCurrentLoginUserId();
        if (!isset($data['type'])){
            return false;
        }
        $wallet = $this->where(['user_id' => $user_id,'type' => $data['type']])->find();
        if (empty($wallet)){
            if ($data['type'] == 0 || $data['type'] == 1){
                $info = [
                    'user_id' => $user_id,
                    'name' => $data['name'],
                    'mobile' => $data['mobile'],
                    'type' => $data['type'],
                    'path' => $data['path'],
                    'real_name' => $data['real_name']
                ];
            }elseif ($data['type'] == 2){
                $info = [
                    'user_id' => $user_id,
                    'name' => $data['name'],  //卡号
                    'bank' => $data['bank'], //银行
                    'branch' => $data['branch'],   //支行
                    'real_name' => $data['real_name'],  //真实姓名
                    'mobile' => $data['mobile'],  //手机号
                    'type' => $data['type']
                ];
            }
            $this->save($info);
        }else{
            $wallet->save($data);
        }

        return true;
    }

    public function info()
    {
        $user_id = User::getCurrentLoginUserId();
//        var_dump($user_id);exit;
        $data = $this->where('user_id',$user_id)->select();
        if (!empty($data)){
            foreach ($data as &$value){
                if ($value['status'] == 1 || $value['status'] == 2){
                    $value['path'] = base_url() . 'uploads/' . $value['path'];
                }
            }
        }

        return $data;
    }
}