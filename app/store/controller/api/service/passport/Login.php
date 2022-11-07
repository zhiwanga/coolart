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

namespace app\api\service\passport;

use dh2y\qrcode\QRcode;
use think\db\Where;
use think\facade\Cache;
use think\facade\Db;
use think\helper\Str;
use think\response\Jsonp;
use yiovo\captcha\facade\CaptchaApi;
use app\api\model\{User as UserModel, Setting as SettingModel};
use app\api\service\{user\Oauth as OauthService, user\Avatar as AvatarService, passport\Party as PartyService};
use app\api\validate\passport\Login as ValidateLogin;
use app\common\service\BaseService;
use app\common\enum\Setting as SettingEnum;
use cores\exception\BaseException;
use think\facade\Request;


/**
 * 服务类：用户登录
 * Class Login
 * @package app\api\service\passport
 */
class Login extends BaseService
{
    /**
     * 用户信息 (登录成功后才记录)
     * @var UserModel|null $userInfo
     */
    private $userInfo;

    // 设置json类型字段
    protected $json = ['info'];

    // 用于生成token的自定义盐
    const TOKEN_SALT = 'user_salt';

    /**
     * 执行用户登录
     * @param array $data
     * @return bool
     * @throws BaseException
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function login(array $data): bool
    {
        //var_dump($this->getUserId());die;
        // 数据验证
        $this->validate($data);
        // 自动登录注册
        $this->register($data);
        // 保存第三方用户信息
        $this->createUserOauth($this->getUserId(), $data['isParty'], $data['partyData']);
        // 记录登录态
        return $this->setSession();
    }

    /**
     * 快捷登录：微信小程序用户
     * @param array $form
     * @return bool
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\Exception
     */
    public function loginMpWx(array $form): bool
    {
        // 获取微信小程序登录态(session)
        $wxSession = PartyService::getMpWxSession($form['partyData']['code']);

        // 判断openid是否存在
        $userId = OauthService::getUserIdByOauthId($wxSession['openid'], 'MP-WEIXIN');
        // 获取用户信息
        $userInfo = !empty($userId) ? UserModel::detail($userId) : null;

        // 用户信息存在, 更新登录信息
        if (!empty($userInfo)) {
            // 更新用户登录信息
            $this->updateUser($userInfo, true, $form['partyData']);
            // 记录登录态
            return $this->setSession();
        }

        // 用户信息不存在 => 注册新用户 或者 跳转到绑定手机号页
        $setting = SettingModel::getItem(SettingEnum::REGISTER);
        // 后台设置了需强制绑定手机号, 返回前端isBindMobile, 跳转到手机号验证页
        if ($setting['isForceBindMpweixin']) {
            throwError('当前用户未绑定手机号', null, ['isBindMobile' => true]);
        }
        // 后台未开启强制绑定手机号, 直接保存新用户
        if (!$setting['isForceBindMpweixin']) {
            // 用户不存在: 创建一个新用户
            $this->createUser('', true, $form['partyData']);
            // 保存第三方用户信息
            $this->createUserOauth($this->getUserId(), true, $form['partyData']);
        }
        // 记录登录态
        return $this->setSession();
    }

    /**
     * 快捷登录：微信小程序用户
     * @param array $form
     * @return bool
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\Exception
     */
    public function loginMpWxMobile(array $form): bool
    {
        // 获取微信小程序登录态(session)
        $wxSession = PartyService::getMpWxSession($form['code']);
        // 解密encryptedData -> 拿到手机号
        $wxData = OauthService::wxDecryptData($wxSession['session_key'], $form['encryptedData'], $form['iv']);
        // 整理登录注册数据
        $loginData = [
            'mobile' => $wxData['purePhoneNumber'],
            'isParty' => $form['isParty'],
            'partyData' => $form['partyData'],
        ];
        // 自动登录注册
        $this->register($loginData);
        // 保存第三方用户信息
        $this->createUserOauth($this->getUserId(), $loginData['isParty'], $loginData['partyData']);
        // 记录登录态
        return $this->setSession();
    }

    /**
     * 保存oauth信息(第三方用户信息)
     * @param int $userId 用户ID
     * @param bool $isParty 是否为第三方用户
     * @param array $partyData 第三方用户数据
     * @return void
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function createUserOauth(int $userId, bool $isParty, array $partyData = []): void
    {
        if ($isParty) {
            $Oauth = new PartyService;
            $Oauth->createUserOauth($userId, $partyData);
        }
    }

    /**
     * 当前登录的用户信息
     */
    public function getUserInfo(): ?UserModel
    {
        return $this->userInfo;
    }

    /**
     * 当前登录的用户ID
     * @return int
     */
    private function getUserId(): int
    {
        return (int)$this->getUserInfo()['user_id'];
    }

    /**
     * 自动登录注册
     * @param array $data
     * @return void
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function register(array $data): void
    {
        // 查询用户是否已存在
        // 用户存在: 更新用户登录信息
        $userInfo = UserModel::detail(['mobile' => $data['mobile']]);
        if ($userInfo) {
            $this->updateUser($userInfo, $data['isParty'], $data['partyData']);
            return;
        }
        // 用户不存在: 创建一个新用户
        $this->createUser($data['mobile'], $data['isParty'], $data['partyData']);
    }

    /**
     * 新增用户
     * @param string $mobile 手机号
     * @param bool $isParty 是否存在第三方用户信息
     * @param array $partyData 用户信息(第三方)
     * @return void
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function createUser(string $mobile, bool $isParty, array $partyData = []): void
    {
        // 用户信息
        $data = [
            'mobile' => $mobile,
            'nick_name' => !empty($mobile) ? hide_mobile($mobile) : '',
            'platform' => getPlatform(),
            'last_login_time' => time(),
            'store_id' => $this->storeId
        ];
        // 写入用户信息(第三方)
        if ($isParty === true && !empty($partyData)) {
            $partyUserInfo = PartyService::partyUserInfo($partyData, true);
            $data = array_merge($data, $partyUserInfo);
        }
        // 新增用户记录
        $model = new UserModel;
        $status = $model->save($data);
        // 记录用户信息
        $this->userInfo = $model;
    }

    /**
     * 更新用户登录信息
     * @param UserModel $userInfo
     * @param bool $isParty 是否存在第三方用户信息
     * @param array $partyData 用户信息(第三方)
     * @return void
     */
    private function updateUser(UserModel $userInfo, bool $isParty, array $partyData = []): void
    {
        // 用户信息
        $data = [
            'last_login_time' => time(),
            'store_id' => $this->storeId
        ];
        // 写入用户信息(第三方)
        // 如果不需要每次登录都更新微信用户头像昵称, 下面4行代码可以屏蔽掉
        if ($isParty === true && !empty($partyData)) {
            $partyUserInfo = PartyService::partyUserInfo($partyData, true);
            $data = array_merge($data, $partyUserInfo);
        }
        // 更新用户记录
        $status = $userInfo->save($data) !== false;
        // 记录用户信息
        $this->userInfo = $userInfo;
    }

    /**
     * 记录登录态
     * @return bool
     * @throws BaseException
     */
    public function setSession(): bool
    {
        //var_dump(empty($this->userInfo));die;
        empty($this->userInfo) && throwError('未找到用户信息');
        // 登录的token
        $token = $this->getToken($this->getUserId());
        // 记录缓存, 30天
        Cache::set($token, [
            'user' => $this->userInfo,
            'store_id' => $this->storeId,
            'is_login' => true,
        ], 86400 * 30);
        return true;
    }

    /**
     * 数据验证
     * @param array $data
     * @return void
     * @throws BaseException
     */
    private function validate(array $data): void
    {
        // 数据验证
        $validate = new ValidateLogin;
        if (!$validate->check($data)) {
            throwError($validate->getError());
        }
        // 验证短信验证码是否匹配
        if (!CaptchaApi::checkSms($data['smsCode'], $data['mobile'])) {
            throwError('短信验证码不正确');
        }
    }

    /**
     * 获取登录的token
     * @param int $userId
     * @return string
     */
    public function getToken(int $userId): string
    {
        static $token = '';
        if (empty($token)) {
            $token = $this->makeToken($userId);
        }
        return $token;
    }

    /**
     * 生成用户认证的token
     * @param int $userId
     * @return string
     */
    private function makeToken(int $userId): string
    {
        $storeId = $this->storeId;
        // 生成一个不会重复的随机字符串
        $guid = get_guid_v4();
        // 当前时间戳 (精确到毫秒)
        $timeStamp = microtime(true);
        // 自定义一个盐
        $salt = self::TOKEN_SALT;
        return md5("{$storeId}_{$timeStamp}_{$userId}_{$guid}_{$salt}");
    }

    /**
     * 只用手机号码登录
     *
     */
    public function logina($phone,$codes){

        //手机号码正则验证
        if(Str::length($phone)!=11){
            $result=0;
        }else{
            $pattern = "/^1(3[0-9]|5[0-3,5-9]|7[1-3,5-8]|8[0-9])\d{8}$/";
            $result = preg_match($pattern, $phone);
        }
        if($result==0){
            $jsons=array(
                'status'=>201,
                'message'=>'请输入正确的手机号码'
            );
            return $jsons;
        }

        //根据获取的手机号码搜索用户是否存在
//        $usera=Db::name('user')
//            ->alias('u')
//            ->where('u.mobile',$phone)
//            ->join(['yoshop_upload_file'=>'ul'],'u.user_id=ul.user_id')
//            ->field('u.user_id,u.nick_name,u.mobile,ul.file_path,u.idcar_id')
//            ->find();

        //验证输入的手机号码是否存在
        $useras=Db::name('user')
            ->alias('u')
            ->where('u.mobile',$phone)
            ->find();
        
        //验证是否有数据，有则登录 无则注册
        if(empty($useras)){//有数据返回false
            
            //调用地址生成，并验证是否重复
            $address=$this->chasj();
            
            //生成默认头像
            $headImg=Db::name('upload_file')
                ->insertGetId([
                    'storage'   =>  'local',
                    'group_id'  =>  1,
                    'file_name' =>  'headimg.png',
                    'file_path' =>  'headimg/headimg.png',
                    'file_ext'  =>  'png',
                    'create_time'=>time(),
                ]);
        
            //没有数据，注册
            $data=[
                'mobile'=>$phone,
                'nick_name'=>$phone,
                'store_id'=>10001,
                'create_time'=>time(),
                'platform'=>'H5',
                'extension_id'=>$codes,
                'avatar_id' =>$headImg,
                'address'   =>$address,
            ];
            
            //注册用户
            $usera=Db::name('user')
                ->replace()
                ->insertGetId($data);

            //新增账号的同时设置默认头像
            // $headImg=Db::name('user_head_img')
            //     ->insert([
            //         'user_id'   =>  $usera,
            //         'group_id'  =>  1,
            //         'head_name' =>  'headimg.png',
            //         'head_path' =>  'headimg/headimg.png',
            //         'head_ext'  =>  'png',
            //         'head_uptime'   =>  time(),
            //     ]);
                
                
            //调用生成我的推广二维码接口
            $resImg=$this->erweima($usera);

            $useras=Db::name('user')
                ->alias('u')
                ->where('u.mobile',$phone)
                ->leftJoin('yoshop_upload_file uf','u.avatar_id=uf.file_id')
                ->find();
        }else{
            //有数据，查询头像
            $headImg=Db::name('user')
                ->alias('u')
                ->leftJoin('yoshop_upload_file uf','u.avatar_id=uf.file_id')
                ->where('u.user_id',$useras['user_id'])
                ->field('uf.file_path,u.address')
                ->find();
            
            //查询是否有地址，无则生成一个地址
            if(empty($headImg['address'])){
                //调用地址生成，并验证是否重复
                $address=$this->chasj();
                //将地址填充到数据库中
                $ab=Db::name('user')
                    ->where('user_id',$useras['user_id'])
                    ->update(['address'=>$address]);
                $useras['address']=$address;
            }
            
        }
        //有数据，查询头像
        // $headImg=Db::name('user_head_img')
        //     ->where('user_id',$useras['user_id'])
        //     ->field('head_path')
        //     ->find();
        
        

        //判断是否进行了实名认证，进行了认证则查询实名信息并加密
        $idcar=$useras['idcar_id'];

        //查询实名情况，如果已经实名则修改展示信息
        if($idcar!=0){//已实名
            //查询实名信息
            $userid=Db::name('user_idcar')
                ->where('idcar_id',$idcar)
                ->field('idcar_name,idcar')
                ->find();
            //将填充过的数据
            $useras['idname']=$userid['idcar_name'];
            $useras['idcar']=$userid['idcar'];

        }else{//未实名
            $useras['idname']="";
            $useras['idcar']=0;
        }

        $useras['status']='100';
        $useras['file_path']=$headImg['file_path'];

        //有数据，跳转到登录
        return $useras;
    }

    /**
     * 生成推广二维码
     */
    public function erweima($userid){
        //生成推广二维码
        $code = new QRcode();
        //网站路径
        $url = Request::instance()->domain()."#/pages/login/index?code=".$userid;
        $res = $code->png($url,false, 6,"")->entry();

//        $res = $code->png("https://shucang.zhongyuansc.net/?code=$userid",false, 6,"")->entry();
        //生成后放入到用户表中
        $erImg=Db::name('user')->where('user_id',$userid)->update(['extensionImg'=>$res]);
        //二维码图片路径
        return $res;
    }
    
    
    /**
     * 验证随机数是否唯一
     **/
    public function chasj(){
        $a=0;
        while($a>=0){
            //调用随机生成的地址
            
            $a+=1;
            $b=$this->suiji();
            
            $c=Db::name('user')
                ->where('address',$b)
                ->find();
            if($c==null){
                break;
            }
        };
        return $b;
    }
    
    /**
     * 生成随机数
     **/
     public function suiji(){
        // 密码字符集，可任意添加你需要的字符
        $chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
        
        $length=40;
        
        $str = '0x';
        for($i = 0; $i < $length; $i++)
        {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
     }
     
}