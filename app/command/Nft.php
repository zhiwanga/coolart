<?php
declare (strict_types = 1);

namespace app\command;

use app\common\model\BlindOrder;
use app\common\model\nft\NftIssue;
use app\common\model\Transaction as TransactionModel;
use app\common\model\BlindTransaction as BlindTransactionModel;
use app\store\model\Setting;
use app\common\model\Coll;
use app\common\model\Goods;
use app\common\model\nft\NftClass;
use app\common\model\nft\NftOrder;
use app\common\model\Order;
use app\common\model\User;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\Exception;
use think\facade\Db;


class Nft extends Command
{
    private $nft;

    protected function configure()
    {
        // 指令配置
        $this->setName('nft')
            ->setDescription('铸造NFT/自动关闭订单');

        $this->nft = \app\api\service\Nft::instance();

    }

    protected function execute(Input $input, Output $output)
    {


        /**自动关闭订单**/
        $order = Order:: where('pay_status',10)->where('type', 0)->where('is_delete',0)->select();

        $not_cofig = Setting::getItem('box',10001)['price'];


        foreach($order as $o){

            $not_time = strtotime($o['create_time']) + 60*$not_cofig;

            if($not_time < time()){

                if($o['type'] == 0){

                    //根据商品id获取商品信息
                    $goodsList = Db::name('goods')
                        ->alias('g')
                        ->join('yoshop_goods_image gi', 'g.goods_id=gi.goods_id')
                        ->where('g.goods_id', $o['goods_id'])
                        ->find();

                    //针对下单减库存
                    if ($goodsList['deduct_stock_type'] == 10) {

                        Db::name('goods_sku')
                            ->where('goods_id', $o['goods_id'])
                            ->inc('stock_num', $o['goods_sum'])
                            ->update();
                        Db::name('goods')
                            ->where('goods_id', $o['goods_id'])
                            ->inc('stock_total', $o['goods_sum'])
                            ->update();

                    }

                }else{

                    $transaction = TransactionModel::get($o['transaction_id']);
                    $transaction->save(['status' => 0]);

                }

                $o->save(['is_delete'=>1, 'order_status' => 20]);
            }
        }

        $blind_order = BlindOrder::where('status',0)->where('is_delete',0)->select();

        foreach($blind_order as $b){

            $not_time = strtotime($b['create_time']) + 60*$not_cofig;

            if($not_time < time()){

                if($b['type'] == 1){

                    $transaction = BlindTransactionModel::get($b['transaction_id']);
                    $transaction->save(['status' => 0]);
                }

                $b->save(['is_delete'=>1]);
            }
        }

        /**end**/



        /**铸造**/
        $nft_class_info = NftClass::where('store_id',10001)->find();

        if(empty($nft_class_info)){

            $user = User::order('user_id asc')->find();

            NftClass::add_nft_class($user['user_id'],$user['address'],'nft5');

        }

        if(is_string($nft_class_info)){

            $output->info($nft_class_info);
            die;

        }


        //铸造NFT
        $order_list = Coll::where('is_fan',0)
            ->where('is_sl',0)
            ->limit(30)
            ->select();

        foreach($order_list as $o){

            $o['address'] = User::where('user_id',$o['user_id'])->value('address');

            if(empty($o['address'])){

                $output->info('not_address');
                continue;
            }

            //发行NFT
            $nft_add_issue = $this->nft_add_issue($o,$nft_class_info);

            if($nft_add_issue !== true){

                $output->info($nft_add_issue);

            }

            $g_res = $this->nft->lists_nft([
                'name'     => $o['goods_name'].'_'.$o['coll_id'],
                'address'  => $o['address'],
                'class_id' => $nft_class_info['tx_id']
            ]);

            if(!empty($g_res['data'])){

                $g_data_info = $g_res['data']['nfts'];

                if(!empty($g_data_info)){

                    try {

                        $g_data = $g_data_info[0];

                        $o_res = NftOrder::create([
                            'order_id'   => $o['coll_id'],
                            'user_id'    => $o['user_id'],
                            'class_id'   => $g_data['class_id'],
                            'nft_id'     => $g_data['id'],
                            'nft_name'   => $g_data['name'],
                            'owner'      => $o['address'],
                            'tx_hash'    => $g_data['tx_hash'],
                            'store_id'   => '10001'
                        ]);

                        $output->info('插入成功');

                        $o->save(['is_fan'=>1]);

                    }catch (Exception $e){

                        $output->info($e->getMessage());

                    }

                }else{

                    $output->info('重新获取');
                    continue;

                }

            }

        }

        //转让NFT
        $transfer_list = Coll::where('is_fan',2)
            ->where('is_sl',0)
            ->limit(30)
            ->select();

        foreach($transfer_list as $c){

            $address = User::where('user_id',$c['user_id'])->value('address');

            $receive_info = NftOrder::where('order_id',$c['receive_id'])->find();

            if(empty($receive_info)){

                $output->info('获取数据错误');
                continue;

            }

            $operation_list = $this->nft->operation_nft([
                'owner'       => $receive_info['owner'],
                'nft_id'      => $receive_info['nft_id'],
                'class_id'    => $receive_info['class_id'],
            ]);

            if(!empty($operation_list['data'])){

                $res_data = $operation_list['data']['operation_records'];

                if(!empty($res_data)){

                    try {

                        NftOrder::create([
                            'nft_id'        => $receive_info['nft_id'],
                            'class_id'      => $receive_info['class_id'],
                            'owner'         => $address,
                            'tx_hash'       => $res_data[0]['tx_hash'],
                            'nft_name'      => $receive_info['nft_name'],
                            'user_id'       => $c['user_id'],
                            'order_id'      => $c['coll_id'],
                            'store_id'      => 10001
                        ]);

                        $c->save(['is_fan'=>3]);

                        $output->info('ok');

                    }catch (Exception $e){

                        $output->info($e->getMessage());

                    }

                }
            }

        }

        // 指令输出
        $output->writeln('nft');
    }

    protected function nft_add_issue($info,$class){

        //查询是否已经发行
        $nft_order = NftOrder::where('order_id',$info['coll_id'])->find();

        if(empty($nft_order)){

            $request_data = [
                'recipient'     => $info['address'],
                'name'          => $info['goods_name'].'_'.$info['coll_id'],
                'operation_id'  => $info['order_no'].rand(1,999999),
                'tx_id'         => $class['tx_id']
            ];

            $nft_issue = NftIssue::where('coll_id',$info['coll_id'])
                ->find();

            if(empty($nft_issue)){

                //发行NFT
                $is_res = $this->nft->issue_nft($request_data);

                if(!empty($is_res['data'])){

                    try {

                        NftIssue::create([
                            'operation_id'  => $request_data['operation_id'],
                            'tx_id'         => $request_data['tx_id'],
                            'name'          => $request_data['name'],
                            'address'       => $request_data['recipient'],
                            'coll_id'       => $info['coll_id']
                        ]);

                        return true;

                    }catch (Exception $e){

                        return $e->getMessage();

                    }

                }else{

                    return $is_res['error']['message'];

                }

            }else{

                return true;

            }


        }else{

            return true;

        }

    }
}
