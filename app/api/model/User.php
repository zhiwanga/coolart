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
use app\common\model\GoodsCategoryRel;
use app\common\model\Integrals;
use app\common\model\RewardLevel;
use app\common\model\UserBoxLog;
use app\common\model\UserIdcar;
use think\facade\Cache;
use app\api\service\User as UserService;
use app\api\model\UserOauth as UserOauthModel;
use app\common\model\User as UserModel;
use cores\exception\BaseException;
use think\facade\Db;
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
     * 实名认证model
     * idcar_id
     */
    public function idcarModel(array $post)
    {
        $user_id = UserService::getCurrentLoginUserId();
        //获取姓名
        $idcarname = $post['idcar_name'];
        //获取身份证号码
        $idcarnub = $post['idcar'];
        //银行卡
        $accountNo = '';
        //手机号
        $user_info = \app\common\model\User::where(['user_id' =>$user_id])->find();
        var_dump($user_info['mobile']);
        $mobile = $user_info['mobile'];
        $idcar = UserIdcar::where(['idcar_name' => $idcarname,'idcar' => $idcarnub])->find();

        if($idcar && $idcar['status'] == 0){

            return ['code' => 200 ,'msg' => '请继续完成实名认证'];

        }
        var_dump($mobile);
        if ($idcar && $idcar['status'] == 1){
            return ['code' => 500,'msg' => '该实名信息已存在'];
        }
        var_dump($user_info['mobile']);die;
        $idcar = $this->idcarCha($idcarnub,$idcarname,$accountNo,$mobile);
        $result_json = json_decode($idcar,true);
        if(!empty($result_json) && $result_json['code'] == 0 && $result_json['data']['res'] == 1){

            $postlist = [
                'idcar_name'    => $post['idcar_name'],
                'idcar'         => $post['idcar'],
                'account_no'    => $accountNo,
                'mobile'        => $mobile,
                'user_id'       => $user_id
            ];
            //新增身份证数据
             UserIdcar::create($postlist);

            return ['code' => 200,'msg' => '实名认证成功'];
        }
        return ['code' => 500,'msg' => '实名认证失败'];
    }

    //活体
    public function ht_model(){

        $user_id = UserService::getCurrentLoginUserId();

        $idcar = UserIdcar::where(['user_id'=>$user_id])->find();

        //实名后赠送空投奖励
        $user = UserModel::get($user_id);

        if($user['idcar_id'] > 0){

            return ['code' => 500,'msg'=>'已实名!'];

        }

        $user->save(['idcar_id' => $idcar['idcar_id']]);

        //更新状态
        $idcar->save(['status'=>1]);

        $user = UserModel::get($user_id);
        if ($user['extension_id'] != 0){
            UserModel::setIncNum($user['extension_id'],'num');  //增加总邀请人数
            $num = User::where('user_id',$user['extension_id'])->value('num');  //邀请总人数

        }
        return ['code' => 200,'msg'=>'操作成功!'];
    }

    /**13位时间戳**/
    function getMillisecond() {
        list($t1, $t2) = explode(' ', microtime());
        return $t2.ceil( ($t1 * 1000) );
    }

    /**
     * 人脸实名认证（2022/11/9 更换为三要素认证）
     * @param $idcarnub
     * @param $idcarname
     * @return bool|string
     */
    public function idcarCha_Abandonment($idcarnub,$idcarname,$accountNo,$mobile){

        $host = "http://api.chinadatapay.com";
        $path = "/communication/personal/1882";
        $method = "POST";
        $key = "b0014e9a7e7a426213da1143c4756303";

        $params = [
            'key'   => $key,
            'name'  => $idcarname,
            'idcard'=> $idcarnub,
            'mobile'=> $mobile,
        ];


        $sign = md5("idcard=".base64_encode($idcarnub)."&mobile=".base64_encode($mobile)."&name=".base64_encode($idcarname)."&timestamp=".base64_encode($this->getMillisecond()));

        $params['sign'] = $sign;

        //根据API的要求，定义相对应的Content-Type

        $url = $host . $path;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, []);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        if (1 == strpos("$".$host, "https://"))
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        return curl_exec($curl);
    }

    public function idcarCha($idcarnub,$idcarname,$accountNo,$mobile)
    {
        $host = "https://mobile3elements.shumaidata.com";
        $path = "/mobile/verify_real_name";
        $method = "POST";
        $appcode = "2dc90be803904d8c9d6bcbc4f3481101";
        $headers = array();
        array_push($headers, "Authorization:APPCODE " . $appcode);
        //根据API的要求，定义相对应的Content-Type
        array_push($headers, "Content-Type".":"."application/x-www-form-urlencoded; charset=UTF-8");
        $bodys = "idcard=".$idcarnub."&mobile=".$mobile."&name=".$idcarname;
        $url = $host . $path;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        //设定返回信息中是否包含响应信息头，启用时会将头文件的信息作为数据流输出，true 表示输出信息头, false表示不输出信息头
        //如果需要将字符串转成json，请将 CURLOPT_HEADER 设置成 false
        curl_setopt($curl, CURLOPT_HEADER, true);
        if (1 == strpos("$".$host, "https://"))
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        curl_setopt($curl, CURLOPT_POSTFIELDS, $bodys);
        var_dump(curl_exec($curl));exit;
    }


    /**
     * 静默活体
     */
    public function ht_cur($imageId){

        $host = "https://api1.chinadatapay.com";
        $path = "/trade/user/9204";
        $method = "POST";
        $key = "509751e4ac623971c31a9819696ce50a";

        $params = [
            'key'   => $key,
            'imageId'  => $imageId,
        ];

        $sign = md5("imageId=".base64_encode($imageId)."&timestamp=".base64_encode($this->getMillisecond()));

        $params['sign'] = $sign;

        //根据API的要求，定义相对应的Content-Type

        $url = $host . $path;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, []);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        if (1 == strpos("$".$host, "https://"))
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        return curl_exec($curl);
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
//  var_dump($userid);die;
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

    /**
     * 邀请获取盲盒
     * @param $codes
     * @param $userId
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getBox($codes,$userId)
    {
        $probabilitys = Goods::where(['is_box' => 1,'is_delete' => 0])->column('probability');
        $goods = Goods::where(['is_box' => 1,'is_delete' => 0])->select()->toArray();
        if (empty($goods)) {
            return false;
        }
        $key = get_rand($probabilitys);
        $good = $goods[$key];

        $config = Integrals::find();
        $time = $config['setday'] * 86400;  //转赠时间
        Db::startTrans();
        try {
            $data = [
                'user_id' => $codes,
                'goods_id' => $good['goods_id'],
                'addtime' => time(),
                'zztime' => time() + $time,
                'order_no' => '',
                'goods_name' => $good['goods_name'],
                'type' => 1,
                'coll_no' => random(8)
            ];
            //邀请人获赠盲盒
            $res = Coll::create($data);
            $log = [
                'user_id' => $codes,
                'coll_id' => $res['coll_id'],
                'type' => 1,
                'goods_name' => $good['goods_name'],
                'remark' => '',
                'create_time' => time(),
                'goods_id' => $good['goods_id']
            ];
            //写入记录
            UserBoxLog::create($log);

            //重新随机盲盒
            $key1 = get_rand($probabilitys);
            $good1 = $goods[$key1];
            $config = Integrals::find();
            $time = $config['setday'] * 86400;  //转赠时间
            $data1 = [
                'user_id' => $userId,
                'goods_id' => $good1['goods_id'],
                'addtime' => time(),
                'zztime' => time() + $time,
                'order_no' => '',
                'goods_name' => $good1['goods_name'],
                'type' => 1,
                'coll_no' => random(8)
            ];
            //被邀请人获赠盲盒
            $res1 = Coll::create($data1);
            $log1 = [
                'user_id' => $userId,
                'coll_id' => $res1['coll_id'],
                'type' => 1,
                'goods_name' => $good1['goods_name'],
                'remark' => '',
                'create_time' => time(),
                'goods_id' => $good1['goods_id']
            ];
            //写入记录
            UserBoxLog::create($log1);
            //提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return false;
        }
        return true;
    }

    /**
     * 设置交易密码
     * @param string $pass
     * @param string $smsCode
     * @return bool
     * @throws BaseException
     */
    public function setTradePass(string $pass, string $smsCode): bool
    {
        // 当前登录的用户信息
        $user_id = UserService::getCurrentLoginUserId();
        $user = UserModel::get($user_id);
        if (empty($user) || $user['is_delete']) {
            throwError('很抱歉，用户信息不存在或已删除', config('status.not_logged'));
        }

        // 验证短信验证码是否匹配,测试环境先不验证
       /* if (!CaptchaApi::checkSms($smsCode,$user['mobile'])) {
            throwError('短信验证码不正确');
        }*/

        $trade_pass = md5($pass.$user['user_id']);

        $upnick = Db::name('user')->where('user_id',$user_id)->update(['trade_pass'=>$trade_pass]);
        if($upnick == 0){
            //return false;
        }
        return true;
    }
}
