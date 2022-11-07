<?php


namespace app\common\library;


use think\facade\Request;

class Pay
{

    //商户编号
    protected  $customerNum = '10001116659766920021480';

    //店铺编号
    protected $shopNum = '10001216659911954865365';

    //序列号
    protected $machineNum = '10011023166655926001000';

    //公钥
    protected $secretKey = '4a62806d0c564cf08a58ddd8dc74317477413c49';

    //私钥
    protected $accessKey = '08cafde361c6451a8bd7f4d61df0772315f7e763';

    protected $url = 'https://openapi.duolabao.com/v1/customer/order/payurl/create';

    /**
     * 创建订单接口
     */
    public function createDLBPay($params){
        $data['dlb_customer_num'] = $this->customerNum;
        $data['dlb_shop_num'] = $this->shopNum;
        $data['dlb_machine_num'] = $this->machineNum;
        $data['request_num'] = $params['ordersn']; //注：必须为18-32位纯数字
        $data['amount'] = $params['price'];
        $data['callback_url'] = Request::domain().'/'.$params['notify_url'];
        $req_bak = $this->request_createpay($data);

        if($req_bak['code']==200){

            return ['code' => 200 ,'msg'=>'成功','pay_url'=>$req_bak['url']['payurl']];
            
        }else{

            return ['code'=>-1,'msg'=>'调用失败'];

        }

    }

    /**
     * 退款接口
     */
    public function refundDLBPay(){
        $data['dlb_customer_num'] = $_POST['customer_num'];
        $data['dlb_shop_num'] = $_POST['shop_num'];
        $data['request_num'] = $_POST['request_num']; //注：必须为18-32位纯数字
        $req_bak = $this->request_refundpay($data);
        echo json_encode($req_bak);
    }

    /**
     * 创建哆啦宝微信支付
     */
    protected function request_createpay($data){

        if(1==1){
            $pay_data['accesskey'] = $this->accessKey;
            $pay_data['secretkey'] = $this->secretKey;

            $pay_data['timestamp'] = $this->getMillisecond();
            $pay_data['path'] = "/v1/customer/order/payurl/create"; //'/v1/agent/order/payurl/create';


            $sign_data = array(
                'customerNum' => $data['dlb_customer_num'],
                'shopNum' => $data['dlb_shop_num'],
                'machineNum' => $data['dlb_machine_num'],
                'requestNum' => $data['request_num'],
                'amount' => $data['amount'],
                'source' => 'API',
                'tableNum' => '',
                'callbackUrl' => $data['callback_url'],
                'extraInfo' => '',
                'completeUrl' => '',
            );
            $pay_data['body'] = json_encode($sign_data);
            $infoArr = $this->creatTokenPost($pay_data);

            switch ($infoArr['result']) {
                case 'success'://成功
                    $payurl = $infoArr['data']['url'];
                    return  array('code'=>200,'msg'=>'订单支付创建成功','url'=>array('payurl'=>$payurl));
                    break;
                case 'fail'://失败
                    return array('code'=>502,'msg'=>'订单支付创建失败');
                    break;
                case 'error'://异常
                    return array('code'=>501,'msg'=>'服务器繁忙，支付调用失败');
                    break;
                default:
                    break;
            }
        }else{
            return array('code'=>502,'msg'=>'订单支付创建失败');
        }
    }

    /**
     * 申请哆啦宝微信退款
     */
    protected function request_refundpay($data){
        $PayConfig = $this->config('dlbpay');
        if(is_array($PayConfig)){
            $pay_data['accesskey'] = $PayConfig['dlb_access_key'];
            $pay_data['secretkey'] = $PayConfig['dlb_secret_key'];
            $pay_data['timestamp'] = $this->getMillisecond();
            $pay_data['path'] = $PayConfig['dlb_path_refund'];      //'/v1/agent/order/payurl/create';
            $sign_data = array(
                'agentNum'=>$PayConfig['dlb_agent_num'],            // 代理商编号
                'customerNum'=>$data['dlb_customer_num'],           // 哆啦宝商户号--请求传递
                'requestNum'=>$data['request_num'],                 // 订单号--请求传递  注：必须为18-32位纯数字
                'shopNum'=>$data['dlb_shop_num'],                   // 哆啦宝店铺号--请求传递
            );
            $pay_data['body'] = json_encode($sign_data);
            $infoArr = $this->creatTokenPost($pay_data);
            switch ($infoArr['result']) {
                case 'success'://成功
                    $payurl = $infoArr['data']['url'];
                    return array('code'=>200,'msg'=>'订单退款成功','url'=>array('payurl'=>$payurl));
                    break;
                case 'fail'://失败
                    return array('code'=>502,'msg'=>'订单退款失败');
                    break;
                case 'error'://异常
                    return array('code'=>501,'msg'=>'服务器繁忙，退款失败，请稍后再试试');
                    break;
                default:
                    break;
            }
        }else{
            return array('code'=>502,'msg'=>'订单退款失败');
        }
    }

    //生成token并提交
    protected function creatTokenPost($data) {
        $str = "secretKey={$data['secretkey']}&timestamp={$data['timestamp']}&path={$data['path']}&body={$data['body']}";
        $token = strtoupper(sha1($str));
        $url = 'http://openapi.duolabao.cn'.$data['path'];

        $post_data = $data['body'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'accesskey: ' . $data['accesskey'],
                'timestamp: ' . $data['timestamp'],
                'token: ' . $token)
        );
        $info = curl_exec($ch);
        $infoArr = json_decode($info,true);
        //    put_contents('log/payurl_result',$infoArr,1);
        curl_close($ch);
        return $infoArr;
    }

    /**13位时间戳**/
    function getMillisecond() {
        list($t1, $t2) = explode(' ', microtime());
        return $t2.ceil( ($t1 * 1000) );
    }


}
