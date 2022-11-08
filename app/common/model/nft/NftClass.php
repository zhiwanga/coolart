<?php


namespace app\common\model\nft;


use app\api\service\Nft;
use cores\BaseModel;

class NftClass extends BaseModel
{

    protected $name = 'nft_class';

    protected $pk   = 'id';

    public static function add_nft_class($user_id,$address,$name){

        $nft = Nft::instance();

        $nft_class = NftClass::where('user_id',$user_id)->find();

        if(empty($nft_class)){

            $result = $nft->detail_fnt_class([
                'class_id'      => $name
            ]);

            if(!empty($result['error'])){

                $res = $nft->add_nft_class([
                    'name'        => $name,
                    'address'     => $address,
                    'operation_id'=> $user_id.rand(100,999),
                    'class_id'    => $name
                ]);

                if(!empty($res['error'])){

                    return $res['error']['message'];

                }

                $result = $nft->detail_fnt_class([
                    'class_id'      => $name
                ]);

            }

            if(!empty($result['data'])){

                NftClass::create([
                    'user_id'       => $user_id,
                    'name'          => $result['data']['name'],
                    'owner'         => $result['data']['owner'],
                    'tx_hash'       => $result['data']['tx_hash'],
                    'tx_id'         => $result['data']['id'],
                    'store_id'      => '10001'
                ]);

                return NftClass::where('user_id',$user_id)->find();

            }else{

                return $result['error']['message'];

            }


        }else{

            return $nft_class;
        }
    }
}
