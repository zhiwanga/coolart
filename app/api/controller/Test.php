<?php


namespace app\api\controller;


use app\api\service\Nft;
use app\common\model\nft\NftClass;
use app\common\model\nft\NftOrder;

class Test extends Controller
{

   public function index(){

       $nft = Nft::instance();

       $address = 'iaa143ayawljuklx3drlgd3c0nfum349uwu6j7y6th';

       $address_1 = 'iaa1cresf9v7q367rxjf83ex70nyfetyp46ry95mfn';



       /* dump($nft->transfer_nft([
            'class_id'      => 'a16fff86e27d51d5368fb6bc7416406a',
            'address'       => $address,
            'nft_id'        => 'avatawfnwl9pltojsx72s9mi0zuhcgnk',
            'recipient'     => $address_1,
            'operation_id'  => rand(100,999).rand(100,999)
        ]));die;*/

       /*$res = $nft->add_nft_class([
            'name'          => '1001',
            'operation_id'  => '1001'.rand(1,100),
            'class_id'      => '1001',
            'address'       => $address
       ]);

       $result = $nft->detail_fnt_class([
           'class_id'       => '1001'
       ]);

       $res = NftClass::create([
           'user_id'     => '1001',
           'tx_id'       => $result['data']['id'],
           'name'        => $result['data']['name'],
           'owner'       => $result['data']['owner'],
           'tx_hash'     => $result['data']['tx_hash']
       ]);*/


       /*dump($nft->issue_nft([
           'name'           => '测试商品1',
           'operation_id'   => 'goods'.rand(1,100),
           'tx_id'          => 'a16fff86e27d51d5368fb6bc7416406a'
       ]));die;*/

        /*$g_res = $nft->lists_nft([
//             'name'     => '【超时空】子鼠',
             'address'  => 'iaa1hjwqpr97cj9lnz65cayyunrtcw6y4l77xtu655',
//             'class_id'  => 'ade04f065579f5af967ac54ff18fe4e43'
        ]);*/

       $operation_list = $nft->operation_nft([
           'owner'       => 'iaa1hjwqpr97cj9lnz65cayyunrtcw6y4l77xtu655',
           'nft_id'      => 'avatafzythnv16a299o2ynar5dfkj0gy',
           'class_id'    => 'a219b291409ce6a0dd4005fa5e3eaa526'
       ]);

       dump($operation_list);die;
   }
}