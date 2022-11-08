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

namespace app\api\controller;

use app\api\service\passport\Login as LoginService;
use think\facade\Cache;
use yiovo\captcha\facade\CaptchaApi;

/**
 * 用户认证模块
 * Class Passport
 * @package app\api\controller
 */
class Passport extends Controller
{
    /**
     * 登录接口 (需提交手机号、短信验证码、第三方用户信息)
     * @return array|\think\response\Json
     * @throws \app\common\exception\BaseException
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function login()
    {
        // 执行登录
        $LoginService = new LoginService;

        if (!$LoginService->login($this->postForm())) {
            return $this->renderError($LoginService->getError());
        }
        // 用户信息
        $userInfo = $LoginService->getUserInfo();
        return $this->renderSuccess([
            'userId' => (int)$userInfo['user_id'],
            'token' => $LoginService->getToken((int)$userInfo['user_id'])
        ], '登录成功');
    }

    /**
     * 微信小程序快捷登录 (需提交wx.login接口返回的code、微信用户公开信息)
     * 业务流程：判断openid是否存在 -> 存在:  更新用户登录信息 -> 返回userId和token
     *                          -> 不存在: 返回false, 跳转到注册页面
     * @return array|\think\response\Json
     * @throws \app\common\exception\BaseException
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function loginMpWx()
    {
        // 微信小程序一键登录
        $LoginService = new LoginService;
        if (!$LoginService->loginMpWx($this->postForm())) {
            return $this->renderError($LoginService->getError());
        }
        // 获取登录成功后的用户信息
        $userInfo = $LoginService->getUserInfo();
        return $this->renderSuccess([
            'userId' => (int)$userInfo['user_id'],
            'token' => $LoginService->getToken((int)$userInfo['user_id'])
        ], '登录成功');
    }

    /**
     * 快捷登录: 微信小程序授权手机号登录
     * @return array|\think\response\Json
     * @throws \app\common\exception\BaseException
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function loginMpWxMobile()
    {
        // 微信小程序一键登录
        $LoginService = new LoginService;
        if (!$LoginService->loginMpWxMobile($this->postForm())) {
            return $this->renderError($LoginService->getError());
        }
        // 获取登录成功后的用户信息
        $userInfo = $LoginService->getUserInfo();
        return $this->renderSuccess([
            'userId' => (int)$userInfo['user_id'],
            'token' => $LoginService->getToken((int)$userInfo['user_id'])
        ], '登录成功');
    }


    /**
     * 手机号码登录
     */
    public function loginAll(){

        $LoginService=new LoginService;

        $posta=$this->postData();
        //获取手机号码
        $phone=$posta['phone'];

        //获取推荐者的用户ID
        if (isset($posta['code'])){
            $codes=$posta['code'];
        }else{
            $codes=0;
        }

        //传值进行验证
        $userInfo = $LoginService->logina($phone,$codes);



        // 验证短信验证码是否匹配
        if (!CaptchaApi::checkSms($posta['smsCode'],$phone)) {
            throwError('短信验证码不正确');
        }


        if($userInfo['status']==201){
            return $this->renderError('201',$userInfo);
        }else{
            $token = $LoginService->getToken((int)$userInfo['user_id']);
            $store_id=10001;
            Cache::set($token, [
                'user' => [
                    'user_id'=>$userInfo['user_id'],
                ],
                'store_id' => $store_id,
                'is_login' => true,
            ], 86400 * 30);

            return $this->renderSuccess([
                'userId' => (int)$userInfo['user_id'],
                'phone' => $userInfo['mobile'],
                'nick_name' => $userInfo['nick_name'],
                'file_path' =>$userInfo['file_path'],
                'idname'=>$userInfo['idname'],
                'idcar'=>$userInfo['idcar'],
                'token' => $token,
                'address'=>$userInfo['address']

            ], '登录成功');
        }
    }



}