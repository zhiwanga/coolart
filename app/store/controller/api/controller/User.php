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
use app\common\exception\BaseException;
use app\api\model\UserCoupon as UserCouponModel;
use app\api\service\User as UserService;
use Grafika\Gd\Image;
use think\Request;
use think\response\Json;
use think\tests\FilesystemTest;

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

}