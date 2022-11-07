<?php
declare (strict_types = 1);

namespace app\command;

use app\common\model\GoodsSn;
use app\common\model\User;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\facade\Db;

class AddWallet extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('addwallet')
            ->setDescription('添加地址');
    }

    protected function execute(Input $input, Output $output)
    {
       
        $nft = \app\api\service\Nft::instance();

        $user = User::where('address','')
            ->whereOr('address','null')
            ->where('idcar_id','>',0)
            ->limit(30)
            ->select();

        foreach($user as $u){

            $result = $nft->CreateChainAccount([
                'name'        => $u['user_id'],
                'operation_id'=> $u['user_id'].rand(100,999)
            ]);
    var_dump($result);
            if(!empty($result['data'])){

                $u->save([
                    'address'       => $result['data']['account']
                ]);

                $output->info('ok');

            }else{

                $output->info('err');
            }


            Sleep(1);
        }

        // 指令输出
        $output->writeln('addwallet');
    }
}
