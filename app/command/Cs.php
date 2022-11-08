<?php


namespace app\command;


use app\common\model\Coll;
use app\common\model\GoodsSn;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\facade\Db;

class Cs extends Command
{
    private $nft;

    protected function configure()
    {
        // 指令配置
        $this->setName('cs')
            ->setDescription('test');

        $this->nft = \app\api\service\Nft::instance();

    }

    protected function execute(Input $input, Output $output)
    {

        $sn_list = GoodsSn::where('goods_id',75)
            ->where('create_time','>',0)
            ->order('id asc')->select();

        $n = 1;

        foreach($sn_list as $sn){

            Db::name('goods_sn')->where('id',$sn['id'])->update(['number'=>$n]);

            $n ++;

            $output->info($n);
        }die;
    }
}
