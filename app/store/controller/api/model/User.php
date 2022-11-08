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

namespace app\api\model;

use app\common\model\Goods;
use app\common\model\UserBoxLog;
use think\facade\Cache;
use app\api\service\User as UserService;
use app\api\model\UserOauth as UserOauthModel;
use app\common\model\User as UserModel;
use cores\exception\BaseException;
use think\facade\Db;
use think\model\relation\HasMany;
use yiovo\captcha\facade\CaptchaApi;

/**
 * 用户模型类
 * Class User
 * @package app\api\model
 */
class User extends UserModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'open_id',
        'is_delete',
        'store_id',
        'create_time',
        'update_time'
    ];

    /**
     * 获取器：隐藏手机号中间四位
     * @param string $value
     * @return string
     */
    public function getMobileAttr(string $value): string
    {
        return strlen($value) === 11 ? hide_mobile($value) : $value;
    }

    /**
     * 获取用户信息
     * @param string $token
     * @return User|array|false|null
     * @throws BaseException
     */
    public static function getUserByToken(string $token)
    {
        // 检查登录态是否存在
        if (!Cache::has($token)) {
            return false;
        }
        // 用户的ID
        $userId = (int)Cache::get($token)['user']['user_id'];
        // 用户基本信息
        $userInfo = self::detail($userId);

        if (empty($userInfo) || $userInfo['is_delete']) {
            throwError('很抱歉，用户信息不存在或已删除', config('status.not_logged'));
        }
        // 获取用户关联的第三方用户信息(当前客户端)
        try {
            $userInfo['currentOauth'] = UserOauthModel::getOauth($userId, getPlatform());
        } catch (\Throwable $e) {
            throwError($e->getMessage());
        }

        return $userInfo;
    }

    /**
     * 绑定手机号(当前登录用户)
     * @param array $data
     * @return bool
     * @throws BaseException
     */
    public function bindMobile(array $data): bool
    {
        // 当前登录的用户信息
        $userInfo = UserService::getCurrentLoginUser(true);
        // 验证绑定的手机号
        $this->checkBindMobile($data);
        // 更新手机号记录
        return $userInfo->save(['mobile' => $data['mobile']]);
    }

    /**
     * 验证绑定的手机号
     * @param array $data
     * @return void
     * @throws BaseException
     */
    private function checkBindMobile(array $data): void
    {
        // 验证短信验证码是否匹配
        if (!CaptchaApi::checkSms($data['smsCode'], $data['mobile'])) {
            throwError('短信验证码不正确');
        }
        // 判断手机号是否已存在
        if (static::checkExistByMobile($data['mobile'])) {
            throwError('很抱歉，该手机号已绑定其他账户');
        }
    }

    /**
     * 修改用户信息
     */
    public function userUpmodel($userid,$nickname,$file,$fileSize,$fileHz){
        //验证文件规则    filesize限制上传文件为5M
        $result=validate(['file' => ['fileSize:5242880,fileExt:gif,jpg,png']])->check(['file' => $file]);
        //判断是否验证成功
        if($result){
            //上传该文件到对应的路径
            $path=\think\facade\Filesystem::disk('public')->putFile( '', $file);
        }

        //修改头像
        $headUp=Db::name('upload_file')
            ->where('file_id',$userid)
            ->update([
                'storage'       =>  'local',
                'file_path'     =>  $path,
                'file_size'     =>  $fileSize,
                'file_ext'      =>  $fileHz,
                'update_time'   =>  time()
            ]);

        //修改昵称
        $userUp=Db::name('user')
            ->where('user_id',$userid)
            ->update(['nick_name'=>$nickname]);

        $arr=[
            'file_path' =>  $path,
            'nick_name' =>  $nickname
        ];

        if($headUp!=1 or $userUp!=1){
            return false;
        }
        return $arr;
    }

    /**
     * 修改头像
     */
    public function ImgUpNew($userid,$file,$fileSize,$fileHz){
        //验证文件规则    filesize限制上传文件为5M
        $result=validate(['file' => ['fileSize:5242880,fileExt:gif,jpg,png']])->check(['file' => $file]);
        //判断是否验证成功
        if($result){
            //上传该文件到对应的路径
            $path=\think\facade\Filesystem::disk('public')->putFile( '', $file);
        }
        
        
        //获取当前用户的头像ID
        $avarerid=Db::name('user')
            ->field('avatar_id')
            ->where('user_id',$userid)
            ->find();
 
        //修改头像
        $headUp=Db::name('upload_file')
            ->where('file_id',$avarerid['avatar_id'])
            ->update([
                'storage'       =>  'local',
                'file_path'     =>  $path,
                'file_size'     =>  $fileSize,
                'file_ext'      =>  $fileHz,
                'update_time'   =>  time()
            ]);
        // //同时更新用户表的avatar_id
        // $avarar=Db::name('user')
        //     ->where('user_id',$userid)
        //     ->update(['avatar_id'=>$userid]);
        
        
        if($headUp==0){
            return false;
        }
        $path=[
            'file_path' =>  $path
        ];

        return $path;
    }
    /**
     * 修改用户名
     */
    public function upNickName($userid,$newnickname){
        //判断是否是同一个昵称
        $names=Db::name('user')
            ->where('nick_name',$newnickname)
            ->find();
        $upnick=Db::name('user')
            ->where('user_id',$userid)
            ->update(['nick_name'=>$newnickname]);

        if(!empty($names)){
            //104是昵称相同
            return 104;

        }else if($upnick==0){
            //103是修改失败
            return 103;
        }
        $nickarr=['nickname'=>$newnickname];
        return $nickarr;
    }

    /**
     * 新建反馈信息
     */
    public function feedback($userid,$textend){

        //获取当前时间


        $data=[
            'user_id'   =>  $userid,
            'textend'   =>  $textend,
            'addtime'   =>  time()
        ];

        $feed=Db::name('feedback')
            ->insert($data);

        if($feed!=1){
            return false;
        }
        return true;
    }

    /**
     * 查询反馈信息
     */
    public function feedbaMod(){
        $feedSele=Db::name('feedback')
            ->alias('f')
            ->join('yoshop_user yu','f.user_id=yu.user_id')
            ->join('yoshop_upload_file uhi','yu.avatar_id=uhi.file_id')
            ->field('yu.user_id,uhi.file_path,yu.mobile,yu.nick_name,f.textend,f.addtime')
            ->order('f.id')
            ->paginate(10)->toArray();
        return $feedSele;
    }

    /**
     * 查询关于我们
     */
    public function aboutUsMod(){
        $us=Db::name('aboutus')
            ->select()->toArray();
        if(is_null($us)){
            return 0;
        }
        return $us;
    }

    /**
     * 修改关于我门
     */
    public function aboutUpMod($textend){
        $aboutUp=Db::name('aboutus')
            ->where('id',1)
            ->update([
                'text_us'   =>  $textend
            ]);

        if($aboutUp==0){
            return false;
        }
        return true;
    }


}
