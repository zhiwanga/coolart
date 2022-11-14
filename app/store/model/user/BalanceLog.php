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

namespace app\store\model\user;

use app\common\model\User;
use app\common\model\user\BalanceLog as BalanceLogModel;
use app\common\model\UserWallet;
use think\Model;
use app\store\service\store\User as StoreUserService;
use app\common\enum\user\balanceLog\Scene as SceneEnum;

/**
 * 用户余额变动明细模型
 * Class BalanceLog
 * @package app\store\model\user
 */
class BalanceLog extends BalanceLogModel
{
    /**
     * 获取余额变动明细列表
     * @param array $param
     * @return \think\Paginator
     */
    public function getList(array $param = [])
    {
        // 设置查询条件
        $filter = $this->getFilter($param);
        // 获取列表数据
        $data = $this->with(['user.avatar'])
                    ->alias('log')
                    ->field('log.*, a.cardNo, a.bankName')
                    ->where($filter)
                    ->join('user', 'user.user_id = log.user_id')
                    ->leftjoin('user_bank a', 'log.bank_id = a.id')
                    ->order(['log.create_time' => 'desc'])
                    ->paginate(15);

        return $data;
    }

    /**
     * 设置查询条件
     * @param array $param
     * @return array
     */
    private function getFilter(array $param): array
    {
        // 设置默认的检索数据
        $params = $this->setQueryDefaultValue($param, [
            'user_id' => 0,         // 用户ID
            'search' => '',         // 用户昵称
            'scene' => 0,           // 余额变动场景
            'betweenTime' => []    // 起止时间
        ]);
        // 检索查询条件
        $filter = [];
        // 用户ID
        $params['user_id'] > 0 && $filter[] = ['log.user_id', '=', $params['user_id']];
        // 用户昵称
        !empty($params['search']) && $filter[] = ['user.nick_name', 'like', "%{$params['search']}%"];
        // 余额变动场景
        $params['scene'] > 0 && $filter[] = ['log.scene', '=', (int)$params['scene']];
        
          isset($params['type']) && $params['type'] > 0 && $filter[] = ['log.scene','=',50];
        // 起止时间
        if (!empty($params['betweenTime'])) {
            $times = between_time($params['betweenTime']);
            $filter[] = ['log.create_time', '>=', $times['start_time']];
            $filter[] = ['log.create_time', '<', $times['end_time'] + 86400];
        }
        return $filter;
    }

    public function examine($logId,$status)
    {
        $log = $this->get($logId);
        if ($log['status'] != 0 || $log['scene'] != 50){
            return false;
        } 
        if ($status == 2){
            User::setIncBalance($log['user_id'],abs($log['money']));
             $diffMoney = abs((float)$log['money']);
                BalanceLogModel::adds(SceneEnum::ADMIN, [
                'user_id' => $log['user_id'],
                'money' =>    $diffMoney, 
                'remark' => '提现审核拒绝返还',
                'describe' => '提现审核拒绝返还',
            ]);
        }else{
            BalanceLogModel::adds(SceneEnum::ADMIN, [
                'user_id'  => $log['user_id'],
                'money'    =>    0,  
                'status'   =>    1,
                'describe' => '审核通过，请注意查收到账信息',
            ]);
        }
        $log->save(['status' => $status]);  //更新状态
        return true;
    }
}
