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

use app\common\enum\user\balanceLog\Scene as SceneEnum;
use app\store\model\user\BalanceLog as BalanceLogModel;
use cores\BaseModel;
use app\api\service\User as UserService;
use cores\exception\BaseException;
use think\Model;
use think\model\relation\HasOne;
use think\model\relation\HasMany;
use think\model\relation\BelongsTo;
use app\common\model\user\PointsLog as PointsLogModel;
use think\facade\Db;

/**
 * 用户模型类
 * Class User
 * @package app\common\model
 */
class User extends BaseModel
{
    // 定义表名
    protected $name = 'user';

    // 定义主键
    protected $pk = 'user_id';

    // 性别
    private $gender = [0 => '未知', 1 => '男', 2 => '女'];

    /**
     * 关联用户头像表
     * @return HasOne
     */
    public function avatar(): HasOne
    {
        return $this->hasOne('UploadFile', 'file_id', 'avatar_id')
            ->bind(['avatar_url' => 'preview_url']);
    }

    /**
     * 关联会员等级表
     * @return BelongsTo
     */
    public function grade(): BelongsTo
    {
        $module = self::getCalledModule();
        return $this->belongsTo("app\\{$module}\\model\\user\\Grade", 'grade_id');
    }

    /**
     * 关联收货地址表
     * @return HasMany
     */
    public function address(): HasMany
    {
        return $this->hasMany('UserAddress');
    }

    /**
     * 关联收货地址表 (默认地址)
     * @return BelongsTo
     */
    public function addressDefault(): BelongsTo
    {
        return $this->belongsTo('UserAddress', 'address_id');
    }

    /**
     * 获取器：显示性别
     * @param $value
     * @return string
     */
    public function getGenderAttr($value): string
    {
        return $this->gender[$value];
    }

    /**
     * 获取用户信息
     * @param $where
     * @param array $with
     * @return static|array|false|null
     */
    public static function detail($where, array $with = [])
    {
        $filter = ['is_delete' => 0];
        if (is_array($where)) {
            $filter = array_merge($filter, $where);
        } else {
            $filter['user_id'] = (int)$where;
        }
        return static::get($filter, $with);
    }

    /**
     * 累积用户的实际消费金额
     * @param int $userId
     * @param float $expendMoney
     * @return mixed
     */
    public static function setIncUserExpend(int $userId, float $expendMoney)
    {
        return (new static)->setInc($userId, 'expend_money', $expendMoney);
    }

    /**
     * 累积用户可用余额
     * @param int $userId
     * @param float $money
     * @return mixed
     */
    public static function setIncBalance(int $userId, float $money)
    {
        return (new static)->setInc($userId, 'balance', $money);
    }

    /**
     * 消减用户可用余额
     * @param int $userId
     * @param float $money
     * @return mixed
     */
    public static function setDecBalance(int $userId, float $money)
    {
        return (new static)->setDec([['user_id', '=', $userId]], 'balance', $money);
    }

    /**
     * 指定会员等级下是否存在用户
     * @param int $gradeId
     * @return bool
     */
    public static function checkExistByGradeId(int $gradeId): bool
    {
        $model = new static;
        return (bool)$model->where('grade_id', '=', (int)$gradeId)
            ->where('is_delete', '=', 0)
            ->value($model->getPk());
    }

    /**
     * 指定的手机号是否已存在
     * @param string $mobile
     * @return bool
     */
    public static function checkExistByMobile(string $mobile): bool
    {
        $model = new static;
        return (bool)$model->where('mobile', '=', $mobile)
            ->where('is_delete', '=', 0)
            ->value($model->getPk());
    }

    /**
     * 累积用户总消费金额
     * @param int $userId
     * @param float $money
     * @return mixed
     */
    public static function setIncPayMoney(int $userId, float $money)
    {
        return (new static)->setInc($userId, 'pay_money', $money);
    }

    /**
     * 累积用户实际消费的金额 (批量)
     * @param array $data
     * @return bool
     */
    public function onBatchIncExpendMoney(array $data): bool
    {
        foreach ($data as $userId => $expendMoney) {
            static::setIncUserExpend($userId, (float)$expendMoney);
        }
        return true;
    }

    /**
     * 累积用户的可用积分数量 (批量)
     * @param array $data
     * @return bool
     */
    public function onBatchIncPoints(array $data): bool
    {
        foreach ($data as $userId => $value) {
            $this->setInc($userId, 'points', $value);
        }
        return true;
    }

    /**
     * 累积用户的可用积分
     * @param int $userId 用户ID
     * @param int $points 累计的积分
     * @param string $describe
     * @return mixed
     */
    public static function setIncPoints(int $userId, int $points, string $describe)
    {
        // 新增积分变动明细
        PointsLogModel::add([
            'user_id' => $userId,
            'value' => $points,
            'describe' => $describe,
        ]);
        // 更新用户可用积分
        return (new static)->setInc($userId, 'points', $points);
    }

    /**
     * 更新用户某个字段
     * @param int|string $userId 用户ID
     * @param int $points 累计的积分
     * @param string $describe
     * @return mixed
     */
    public static function setIncNum($userId,$field)
    {
        // 更新用户某个字段
        return (new static)->setInc($userId, $field);
    }

    /**
     * 获取盲盒记录
     * @return UserBoxLog|UserBoxLog[]|array|\think\Collection
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function boxLog()
    {
        $user_id = UserService::getCurrentLoginUserId();
        $log = UserBoxLog::where('user_id',$user_id)->select();
        return $log;
    }

    public function withdrawal($price,$id)
    {
        $user_id = UserService::getCurrentLoginUserId();
        $res = [
            'msg' => '参数错误',
            'code' => '500',
        ];
        $user = $this->where('user_id',$user_id)->find();

        if (($price > 0) && ($user['balance'] < $price)){
            $res['msg'] = '余额不足';
            return $res;
        }

        $wallet = UserBank::field('RIGHT(cardNo, 4) as cardno, id')->where(['user_id' => $user_id,'status' => 1])->find();
        if (empty($wallet)){
            $res['msg'] = '请先完善收款信息';
            return $res;
        }
        //扣除用户余额
        $this->setDecBalance($user_id, (float)$price);
        
        // 计算费率
        $price_data = rateLess(1, $user_id, $price);
        $ratedis = '';

        if($price_data['ratedis'] != 0) {
            $ratedis = '(费率折扣-'.$price_data['ratedis'].')';
        }
        $diffMoney = '-'.$price_data['price'];
        // 新增余额变动记录
        BalanceLogModel::add(SceneEnum::WITHDRAWAL, [
            'user_id' => $user_id,
            'money' => (float)$diffMoney,
            'remark' => '申请提现',
            'type' => 2,
            'bank_id' => $id
        ], ['申请提现'.$ratedis.'-到账卡号('.$wallet['cardno'].')']);
        $res = [
            'msg' => '提现成功',
            'code' => '200',
        ];
        return $res;
    }

    /**
     * 获取用户余额
     * @return mixed
     * @throws BaseException
     */
    public function balance()
    {
        $user_id = UserService::getCurrentLoginUserId();

        $user = $this->get($user_id);
        return ['balance' => $user['balance']];
    }

    /**
     * 获取用户余额
     * @return mixed
     * @throws BaseException
     */
    public function balanceLog($status)
    {
        $user_id = UserService::getCurrentLoginUserId();
        if (!isset($status)){
            $where = ['user_id' => $user_id];
        }else {
            $where = ['user_id' => $user_id, 'status' => $status];
        }
        $log = BalanceLogModel::where($where)->order('log_id', 'desc')->select();
        return ['balanceLog' => $log];
    }
}
