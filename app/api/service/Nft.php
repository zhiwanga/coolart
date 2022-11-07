<?php


namespace app\api\service;


class Nft
{
    //   private $nft = 'I2T2K170j39141t4';//平台标识  生产
    private $nft = 'Q2x230O9p2p1';//平台标识  测试
    private $apiKey = "D2G2o1R0O3l181W4B3o3Y3C6m9g8v8Ln";
    private $apiSecret = "s2b261g0u3s1A1M4X333N3O6J9Y8B8zu";
      private $domain = "https://stage.apis.avata.bianjie.ai";//test
    // private $domain = "https://apis.avata.bianjie.ai/";//prod

    protected static $instance = null;

    public static function instance($options = []){

        if(is_null(self::$instance)){

            self::$instance = new static($options);

        }

        return self::$instance;

    }


    /**创建链账户**/
    function CreateChainAccount($params)
    {

        $body = [
            "name"          => 'kkys'.$params['name'],
            "operation_id"  => 'kkys'.$params['operation_id'],
        ];

        $res = $this->request("/v1beta1/account", [], $body, "POST");

        return $res;
    }

    /**查询链账户**/
    function QueryChainAccount($params)
    {

        $query = [
            "name"          => $this->nft.$params['name'],
            "operation_id"  => $this->nft.$params['operation_id'], // the CreateChainAccount use operation_id
        ];

        $res = $this->request("/v1beta1/accounts", $query, [], "GET");

        return $res;
    }

    /**创建NFT类别**/
    function add_nft_class($params){

        $body = [
            'name'          => $this->nft.$params['name'],
            'owner'         => $params['address'],
            'operation_id'  => $this->nft.$params['operation_id'],
            'class_id'      => md5($this->nft.$params['class_id'])
        ];
        $res = $this->request('/v1beta1/nft/classes',[],$body,"POST");

        return $res;

    }

    /**类别详情**/
    function detail_fnt_class($params){

        $id =  md5($this->nft.$params['class_id']);

        $res = $this->request("/v1beta1/nft/classes/{$id}", [], [], "GET");

        return $res;
    }

    /**发行NFT**/
    function issue_nft($params){

        $body = [
            'recipient'     => $params['recipient'],
            'name'          => $this->nft.$params['name'],
            'operation_id'  => md5($this->nft.$params['operation_id']),
        ];

        $res = $this->request("/v1beta1/nft/nfts/{$params['tx_id']}", [], $body, "POST");

        return $res;
    }

    /**查询NFT**/
    function lists_nft($params){

        $query = [
            "name"              => $this->nft.$params['name'],
            "owner"             => $params['address'],
            'class_id'          => $params['class_id'],
        ];

        if(!empty($params['id'])){

            $query['id']    = $params['id'];

        }

        $res = $this->request("/v1beta1/nft/nfts", $query, [], "GET");

        return $res;
    }

    /**NFT详情**/
    function detail_fnt($params){

        $res = $this->request("/v1beta1/nft/nfts/{$params['class_id']}/{$params['nft_id']}", [], [], "GET");

        return $res;
    }

    /**转让NFT**/
    function transfer_nft($params){

        $body = [
            'recipient'     => $params['recipient'],
            'operation_id'  => md5($this->nft.$params['operation_id']),
        ];

        $res = $this->request("/v1beta1/nft/nft-transfers/{$params['class_id']}/{$params['address']}/{$params['nft_id']}", [], $body, "POST");

        return $res;
    }

    /**NFT操作记录**/
    function operation_nft($params){

        $query = [
            'signer'    => $params['owner'],
            'operation' => 'transfer',
        ];

        $res = $this->request("/v1beta1/nft/nfts/{$params['class_id']}/{$params['nft_id']}/history", $query, [], "GET");

        return $res;
    }

    private function request($path, $query = [], $body = [], $method = 'GET')
    {
        $method = strtoupper($method);
        $apiGateway = rtrim($this->domain, '/') . '/' . ltrim($path,
                '/') . ($query ? '?' . http_build_query($query) : '');

        $timestamp = $this->getMillisecond();
        $params = ["path_url" => $path];
        if ($query) {
            // 组装 query
            foreach ($query as $k => $v) {
                $params["query_{$k}"] = $v;
            }
        }
        if ($body) {
            // 组装 post body
            foreach ($body as $k => $v) {
                $params["body_{$k}"] = $v;
            }
        }
        // 数组递归排序
        $this->SortAll($params);
        $hexHash = hash("sha256", "{$timestamp}" . $this->apiSecret);

        if (count($params) > 0) {
            // 序列化且不编码
            $s = json_encode($params, JSON_UNESCAPED_UNICODE);
            $hexHash = hash("sha256", stripcslashes($s . "{$timestamp}" . $this->apiSecret));
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiGateway);
        $header = [
            "Content-Type:application/json",
            "X-Api-Key:{$this->apiKey}",
            "X-Signature:{$hexHash}",
            "X-Timestamp:{$timestamp}",
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $jsonStr = $body ? json_encode($body) : ''; //转换为json格式
        if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, 1);
            if ($jsonStr) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonStr);
            }
        } elseif ($method == 'PATCH') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
            if ($jsonStr) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonStr);
            }
        } elseif ($method == 'PUT') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            if ($jsonStr) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonStr);
            }
        } elseif ($method == 'DELETE') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            if ($jsonStr) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonStr);
            }
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response, true);

        return $response;

    }


    function SortAll(&$params)
    {
        if (is_array($params)) {
            ksort($params);
        }
        foreach ($params as &$v) {
            if (is_array($v)) {
                $this->SortAll($v);
            }
        }
    }

    /** get timestamp
     *
     * @return float
     */
    private function getMillisecond()
    {
        list($t1, $t2) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($t1) + floatval($t2)));
    }
}
