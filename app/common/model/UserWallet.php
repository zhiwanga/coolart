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

use app\api\model\UserBank;
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
        $bank = UserBank::where('user_id', $user_id)->where('cardNo', $data['cardno'])->find();
        if($bank) {
            return false;
        }else{
            $user_idcar = UserIdcar::field('idcar_name, idcar')->where('user_id', $user_id)->find();

            // 银行卡检验
            $res = $this->bankcard234($data['cardno'], $user_idcar['idcar'], $data['mobile'], $user_idcar['idcar_name']);
            $res = json_decode($res, true);
            if($res['data']['result'] == 0) {

                UserBank::where('user_id', $user_id)->where('status', 1)->update(['status' => 0]);
                $insert = [
                    'requestNum'    => $res['data']['order_no'],
                    'idCardNo'      => $user_idcar['idcar'],
                    'cardNo'        => $data['cardno'],
                    'phone'         => $data['mobile'],
                    'payerName'     => $user_idcar['idcar_name'],
                    'status'        => 1,
                    'type'          => $res['data']['bank_info']['type'],
                    'bankName'      => $res['data']['bank_info']['bank'],
                    'logo'          => $res['data']['bank_info']['logo']
                ];
                UserBank::insert($insert);
            }else{
                return false;
            }
        }

        return true;
    }

    private function bankcard234($bankcard, $idcard, $mobile, $name)
    {
        $host = "https://slybank234.market.alicloudapi.com";
        $path = "/bankcard234/check";
        $method = "GET";
        $appcode = "2dc90be803904d8c9d6bcbc4f3481101";
        $headers = array();
        array_push($headers, "Authorization:APPCODE " . $appcode);
        $querys = "bankcard=".$bankcard."&idcard=".$idcard."&mobile=".$mobile."&name=".urlencode($name);
        $bodys = "";
        $url = $host . $path . "?" . $querys;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        if (1 == strpos("$".$host, "https://"))
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        return curl_exec($curl);
    }

    public function info()
    {
        $user_id = User::getCurrentLoginUserId();
        // $data = $this->where('user_id',$user_id)->select();
        // if (!empty($data)){
        //     foreach ($data as &$value){
        //         if ($value['status'] == 1 || $value['status'] == 2){
        //             $value['path'] = base_url() . 'uploads/' . $value['path'];
        //         }
        //     }
        // }

        // return $data;

        $res = UserBank::field('RIGHT(cardNo, 4) as cardno, bankName, type')
                        ->where('user_id', $user_id)
                        ->where('status', 1)
                        ->select()
                        ->toArray();

        return $res;
    }
}