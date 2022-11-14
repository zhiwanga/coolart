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

use app\api\model\User as UserModel;
use app\common\model\User as UserModels;
use app\common\exception\BaseException;
use app\api\model\UserCoupon as UserCouponModel;
use app\api\service\User as UserService;
use app\common\model\UserIdcar;
use app\common\model\UserWallet;
use app\controller\Rsa;
use app\store\model\Setting as SettingModel;
use GuzzleHttp\Client;
use think\response\Json;
use think\facade\Db;
use yiovo\captcha\facade\CaptchaApi;
/**
 * 用户管理
 * Class User
 * @package app\api
 */
class User extends Controller
{
    /**
     * 当前用户详情
     * @return Json
     * @throws BaseException
     */
    public function info(): Json
    {
        // 当前用户信息
        $userInfo = UserService::getCurrentLoginUser(true);
        // 获取用户头像
        $userInfo['avatar'];
        // 获取会员等级
        $userInfo['grade'];
        return $this->renderSuccess(compact('userInfo'));
    }

    /**
     * 设置支付密码
     * @return Json
     */
    public function setTradePass()
    {
        //获取参数信息
        $posta=$this->postData();
        $pass = (string)$posta['password'];
        $smsCode = (string)$posta['smsCode'];
        $repeatPass = (string)$posta['repeat_password'];

        //验证
        if(empty($smsCode)){
            return $this->renderError('验证码不能位空');
        }
        if(empty($pass)){
            return $this->renderError('交易密码不能位空');
        }
        if(strlen($pass) != 6 && !is_numeric($pass)){
            return $this->renderError('交易密码必须是6位数字');
        }
        if($pass != $repeatPass){
            return $this->renderError('确认交易密码与交易密码不一致');
        }

        $model = new UserModel;
        if(!$model->setTradePass($pass, $smsCode)){
            return $this->renderError($model->getError() ?: '设置失败');
        }
        return $this->renderSuccess('设置成功');
    }
    /**
     * 账户资产
     * @return Json
     * @throws BaseException
     */
    public function assets(): Json
    {
        // 当前用户信息
        $userInfo = UserService::getCurrentLoginUser(true);
        // 用户优惠券模型
        $model = new UserCouponModel;
        // 返回数据
        return $this->renderSuccess([
            'assets' => [
                'balance' => $userInfo['balance'],  // 账户余额
                'points' => $userInfo['points'],    // 会员积分
                'coupon' => $model->getCount($userInfo['user_id']),    // 优惠券数量(可用)
            ]
        ]);
    }

    /**
     * 手机号绑定
     * @return Json
     * @throws \cores\exception\BaseException
     */
    public function bindMobile(): Json
    {
        $model = new UserModel;
        if (!$model->bindMobile($this->postForm())) {
            return $this->renderSuccess($model->getError() ?: '操作失败');
        }
        return $this->renderSuccess('恭喜您，手机号绑定成功');
    }

    /**
     * 实名认证控制器
     */
    public function idcar(){
        $idcarComm = new UserModel;
        //获取post的内容
        $postlist = $this->postData();
        $userInfo = $idcarComm->idcarModel($postlist);
        if ($userInfo['code'] == 500){
            return $this->renderError($userInfo['msg']);
        }
        return $this->renderSuccess($userInfo['msg']);
    }

    /**
     * 活体认证
     */
    public function ht_car(){

        $idcarComm = new UserModel;
        //获取post的内容
        $postlist = $this->postData();
        $userInfo = $idcarComm->ht_model();

        if ($userInfo['code'] == 500){
            return $this->renderError($userInfo['msg']);
        }
        return $this->renderSuccess($userInfo['msg']);

    }
    public function userinfo(){

        $user_id = UserService::getCurrentLoginUserId();

        $userInfo = \app\common\model\User::with('avatar')->where(['user_id' =>$user_id])->find();

        if($userInfo['idcar_id'] != 0){ //已实名
            //查询实名信息
            $idcar = UserIdcar::where('idcar_id',$userInfo['idcar_id'])->field('idcar_name,idcar')->find();
            $xing ='****************';
            $userInfo['idname'] = $idcar['idcar_name'];
            $userInfo['idcar'] = substr_replace($idcar['idcar'],$xing,2,16);
        }

        return $this->renderSuccess([
            'userId' => (int)$userInfo['user_id'],
            'phone' => $userInfo['mobile'],
            'nick_name' => $userInfo['nick_name'],
            'file_path' =>$userInfo['avatar_url'],
            'idname'=>$userInfo['idname'],
            'idcar'=>$userInfo['idcar'],
            'address'=>$userInfo['address'],
            'trade_pass' => $userInfo['trade_pass'] ? false : 1
        ], 'ok');
    }

    /**
     * 修改用户信息
     */
    public function userUp(){
        $idcarComm = new UserModel;
        //获取表单传过来的数据
        $posts=$this->postData();
        $userid=$posts['userid'];
        $nickname=$posts['nickname'];
        //获取上传的图片
        $file=$this->request->file('file');
        //获取文件大小
        $fileSize=$file->getSize();
        //获取文件后缀
        $fileHz=$file->extension();
        $userUpNew=$idcarComm->userUpmodel($userid,$nickname,$file,$fileSize,$fileHz);
        if(!$userUpNew){
            return $this->renderSuccess('修改失败！');
        }
        return $this->renderError('实名认证失败！');
    }



    /**
     * 修改头像
     */
    public function ImgUp(){

        $post=$this->postData();
        $userid=$post['userid'];

        $idcarComm = new UserModel;
        //获取图片路径
        $file=$this->request->file('file');
        //获取文件大小
        $fileSize=$file->getSize();
        //获取文件后缀
        $fileHz=$file->extension();
        $ImgUpNew=$idcarComm->ImgUpNew($userid,$file,$fileSize,$fileHz);

        if($ImgUpNew==false){
            return $this->renderError('修改失败！');
        }
        return $this->renderSuccess($ImgUpNew,'修改成功');
    }
    /**
     * 修改昵称
     */
     public function nickNameUp(){
         //获取Head里的用户ID
         $idcarComm = new UserModel;
         //获取传过来的新昵称
         $posts=$this->postData();
         $userid=$posts['userid'];
         $newnickname=$posts['nickname'];
         $nickName=$idcarComm->upNickName($userid,$newnickname);
         if($nickName==103){
             return $this->renderError('修改失败！');
         }else if($nickName==104){
             return $this->renderError('新昵称和原昵称相同，修改失败！');
         }
         return $this->renderSuccess($nickName,'修改成功');
     }

     /**
      * 新增反馈
      * userid 用户ID
      * textend 反馈内容
      */
     public function feedback(){
         $feedbacks = new UserModel;
         $posts=$this->postData();

         //获取传过来的用户id
         $userid=$posts['userid'];
         //获取传过来的反馈内容
         $textend=$posts['textend'];

         $feedback=$feedbacks->feedback($userid,$textend);
         if($feedback){
             return $this->renderSuccess('反馈提交成功！');
         }
         return $this->renderError('反馈提交失败，请稍后再试！');
     }

     /**
      * 查找反馈
      */
     public function feedbackColl(){
         $feedbacks = new UserModel;

         $feedbaMod=$feedbacks->feedbaMod();

         if ($feedbaMod==0){
             return $this->renderError('查询失败，请稍后再试！');
         }
         return $this->renderSuccess($feedbaMod,'查询成功！');
     }

     /**
      * 查询关于我们
      */
     public function aboutUsColl(){
         $abotUs = new UserModel;

         $abotUsMod=$abotUs->aboutUsMod();

         if($abotUsMod==0){
             return $this->renderError('查询失败，请稍后再试！');
         }
         return $this->renderSuccess($abotUsMod,'查询成功！');
     }


     /**
      *修改关于我们
      */
     public function aboutUpColl(){
         $abotUs = new UserModel;

         $posts=$this->postData();

         //获取传过来的用户id
         $textend=$posts['textend'];

         $abotUsMod=$abotUs->aboutUpMod($textend);

         if($abotUsMod){
             return $this->renderSuccess([],'修改成功！');
         }
         return $this->renderError('新内容与旧内容不能相同！');
     }
    /**
     * 二级密码
    */
    public function secondPawd(){
        $posts=$this->postData();
        $second_pswd = $posts['second_pswd'];
        $re_second_pswd = $posts['re_second_pswd'];
        if($second_pswd != $re_second_pswd) {
            return $this->renderError('两次密码不一致！');
        }
        $id_six = $posts['id_six'];
        $user_id = UserService::getCurrentLoginUserId();

        $user_idcar = Db::name('user_idcar')->where('user_id', $user_id)->find();

        if(!$user_idcar){
            return $this->renderError('未进行实名，请先实名认证！');
        }else{
            $temp_idcar = substr($user_idcar['idcar'], 12, 18);
            if($temp_idcar == $id_six){
                $res = Db::name('user')->where('user_id', $user_id)->update(['trade_pass' => $second_pswd]);
                if($res) {
                    return $this->renderSuccess([], '修改成功');
                }else{
                    return $this->renderError('修改失败，请重试！');
                }
            }else{
                return $this->renderError('身份证号码输入错误！');
            }
        }
    }

    /**
     * 验证二级密码
    */
    public function checkSecondPawd(){
        $posts=$this->postData();
        $second_pswd = $posts['second_pswd'];

        $user_id = UserService::getCurrentLoginUserId();

        $user = Db::name('user')->field('trade_pass')->where('user_id', $user_id)->find();

        if($second_pswd == $user['trade_pass']) {
            return $this->renderSuccess([], '验证成功');
        }else{
            return $this->renderError('二级密码输入错误');
        }
    }

    /**
     * 获取海报链接
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
     public function poster()
     {
         $values = SettingModel::getItem('poster');
         return $this->renderSuccess(compact('values'));
     }

    /**
     * 获取盲盒图片链接
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function boxBg()
    {
        $values = SettingModel::getItem('box_bg');
        return $this->renderSuccess(compact('values'));
    }

    /**
     * 获取盲盒记录
     * @return Json
     * @throws \cores\exception\BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function boxLog()
    {
        $user = new UserModels();
        $data = $user->boxLog();
        return $this->renderSuccess(compact('data'));
    }

    /**
     * 提现
     * @return Json
     * @throws \cores\exception\BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function withdrawal($price,$id,$cipcont)
    {
        $user = new UserModels();
        if($price<0){
            return $this->renderError("无效提现金额!");
       }
       
        $user_id = UserService::getCurrentLoginUserId();

        // rsa密钥检测
        if(isset($cipcont) && $cipcont) {
            $res = Rsa::rsaContCheck(3, $cipcont, $user_id);
            if(!$res) return $this->renderError('密码错误');
        }else{
            return $this->renderError('缺少传参');
        }

        $res = $user->withdrawal($price,$id);
        if ($res['code'] == 500){
            return $this->renderError($res['msg']);
        }
        return $this->renderSuccess($res['msg']);
    }

    /**
     * 绑定银行卡
     * @return Json
     * @throws \cores\exception\BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function collectionEdit()
    {
        $wallet = new UserWallet();
        $posta = input();

        $user = UserService::getCurrentLoginUser();
        // rsa密钥检测
        if(isset($posta['cipcont']) && $posta['cipcont']) {
            $res = Rsa::rsaContCheck(1, $posta['cipcont'], $user['user_id']);
            if(!$res) return $this->renderError('密码错误');
        }else{
            return $this->renderError('缺少传参');
        }

        if($posta['cardno'] && $posta['mobile']) {
            $res = $wallet->edit($posta);
            if(!$res) {
                return $this->renderError('操作失败');
            }
            return $this->renderSuccess('修改成功');
        }else{
            return $this->renderError('缺少传参');
        }
    }

    /**
     * 解绑银行卡
     * @return void
     */
    public function unbank()
    {
        $id = input('id');
        if($id) {
            $user_id = UserService::getCurrentLoginUserId();
            $res = Db::name('user_bank')->where('id', $id)->where('status', 1)->where('user_id', $user_id)->update(['status' => 2, 'is_delete' => 1]);
            if($res) {
                return $this->renderSuccess('SUCCESS');
            }else{
                return $this->renderError('参数错误');
            }
        }else{
            return $this->renderError('缺少传参');
        }
    }

    /**
     * 获取收款信息
     * @return Json
     */
    public function collectionInfo()
    {
        $wallet = new UserWallet();
        $data = $wallet->info();
        return $this->renderSuccess($data, 'SUCCESS');
    }

    /**
     * 获取用户余额
     * @return Json
     * @throws \cores\exception\BaseException
     */
    public function balance()
    {
        $user = new UserModels();
        $balance = $user->balance();
        return $this->renderSuccess($balance,'获取成功');
    }

    /**
     * 提现记录
     * @return Json
     * @throws \cores\exception\BaseException
     */
    public function balanceLog()
    {
        $user = new UserModels();
        $status = $this->getData('status');
        $balanceLog = $user->balanceLog($status);
        return $this->renderSuccess($balanceLog,'获取成功');
    }
}
