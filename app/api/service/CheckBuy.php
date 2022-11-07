<?php


namespace app\api\service;

use think\Exception;

class CheckBuy
{

    public   function check_info()
    {
        try {

            //
            # 验证token 、可以直接加在路由后面
            # 验证时间戳
            $this->checkTime();
            # 验证签名
            $this->checkSign();

            return true;
        }catch (Exception $exception) {

           return $exception->getMessage();

        }

    }

    /**
     * 验证时间戳
     * @throws Exception
     */
    public function checkTime(){
        $client_time = request()->get('timestamp') ?: request()->post('timestamp');

        if (!is_numeric($client_time)) {
            throw new Exception('时间戳格式不正确');
        }
        if (time() - $client_time > 60) {
            throw new Exception('请求超时');
        }
    }

    /**
     * 检查签名
     */
    public function checkSign(){
        $client_sign = request()->get('sign') ?: request()->post('sign');
        # 判断是否有签名
        if (!$client_sign) {
            throw new Exception('签名不正确');
        }

        # 判断签名是否正确
        $server_sign = $this->getSign();

        if ($client_sign != $server_sign) {
            throw new Exception('签名不正确');
        }
    }

    /**
     * 获取服务端签名
     * @return string
     */
    public function getSign(){
        # 获取所有请求的参数
        $params = [
            'md_id'     => request()->param('md_id'),
            'md_str'    => request()->param('md_str'),
            'timestamp' => request()->param('timestamp'),
        ];

        # 签名规则
        # 第一步 参与签名的参数不包括签名本身、不包括
        unset($params['sign']);
        /* unset($params['token']);*/
        # 第二步 按照ASCII排序
        ksort($params);
        $wait_sign = '';
        foreach ($params as $key=> $value) {
            $wait_sign .= $key.'='.$value.'&';
        }
        # 去除多余的& 符号
        $wait_sign = rtrim($wait_sign,'&');
        return  md5($wait_sign);
    }

}
