<?php


namespace app\command;


use app\common\model\Goods;
use app\common\model\Make;
use app\common\model\nft\NftClass;
use app\common\model\nft\NftOrder;
use think\console\Command;
use think\console\Input;
use think\console\Output;

class GoodsAdd extends  Command
{
    private $nft;

    protected function configure()
    {
        // 指令配置
        $this->setName('goods_add')
            ->setDescription('中签预告/添加商品编号');

        $this->nft = \app\api\service\Nft::instance();

    }

    protected function execute(Input $input, Output $output)
    {

        /**发布中签结果**/
        $make_goods = Goods::where('status','>',0)
            ->where('stock_total','>',0)
            ->where('type',3)
            ->where('publishtime','<=',time())
            ->select();

        foreach($make_goods as $g){

            //获取库存+随机获取,中签
            $get_ids = Make::where('goods_id',$g['goods_id'])
                ->where('type',0)
                ->orderRaw('rand()')
                ->limit($g['stock_total'])
                ->column('id');

            //中签
            Make::where([['id','in',implode(',',$get_ids)]])->save(['status'=>1,'type'=>1]);

            //未中签
            Make::where('status',0)->where('goods_id',$g['goods_id'])->save(['status'=>2,'type'=>1]);

        }

        $output->info('中签已执行');



    }


}
