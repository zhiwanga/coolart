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
declare (strict_types=1);

namespace app\common\library\sms;

use app\common\library\helper;
use cores\traits\ErrorTrait;
use cores\exception\BaseException;
use Overtrue\EasySms\EasySms;
use Overtrue\EasySms\Exceptions\InvalidArgumentException;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;

/**
 * 短信通知模块驱动
 * Class Driver
 * @package app\common\library\sms
 */
class Driver
{
    use ErrorTrait;

    // 后台短信配置信息
    private $smsConfig;

    // 当前短信平台 (后台设置)
    private $gateway;

    /**
     * 构造方法
     * Driver constructor.
     * @param array $smsConfig
     */
    public function __construct(array $smsConfig)
    {
        // 配置信息
        $this->smsConfig = $smsConfig;
        // 当前短信平台
        $this->gateway = $this->smsConfig['default'];
    }

    function api_request_curl($url, $postData = array()) {

        if (empty($url)) return '';
        $postData = json_encode($postData);

        $curl = curl_init();  //初始化
        curl_setopt($curl,CURLOPT_URL,$url);  //设置url
        curl_setopt($curl,CURLOPT_HTTPAUTH,CURLAUTH_BASIC);  //设置http验证方法
        curl_setopt($curl, CURLOPT_TIMEOUT,30);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);  //设置curl_exec获取的信息的返回方式
        curl_setopt($curl,CURLOPT_POST,1);  //设置发送方式为post请求
        curl_setopt($curl,CURLOPT_POSTFIELDS,$postData);  //设置post的数据

        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($postData))
        );

        $result = curl_exec($curl);
        if($result === false){
            throw new Exception('Http request message :'.curl_error($curl));
        }

        curl_close($curl);
        return $result;
    }

    private function alySms($params){

        $host = "https://cxkjsms.market.alicloudapi.com";
        $path = "/chuangxinsms/dxjk";
        $method = "POST";
        $appcode = "37d5719a108b41c78ba042bf6eab5bd7";//开通服务后 买家中心-查看AppCode
        $headers = array();
        array_push($headers, "Authorization:APPCODE " . $appcode);

        $content = "【长青meta】你的验证码是：{code}，5分钟内有效！";

        $content = str_replace('{code}',$params['code'],$content);

        $querys = "content=".urlencode($content)."&mobile=".$params['mobile'];

        $bodys = "";
        $url = $host . $path . "?" . $querys;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        if (1 == strpos("$" . $host, "https://")) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        return json_decode(curl_exec($curl),true);

    }

    /**
     * 发送短信通知
     * @param string $acceptPhone 接收的手机号
     * @param string $templateCode 短信模板ID
     * @param array $templateParams 短信模板参数
     * @return bool
     * @throws BaseException
     * @throws InvalidArgumentException
     */
    public function sendSms(string $acceptPhone, string $templateCode, array $templateParams): bool
    {
        // 实例化EasySms
        $easySmsConfig = Config::getEasySmsConfig($this->smsConfig);
        $easySms = new EasySms($easySmsConfig);
        try {
            // 执行发送短信
            $result = $easySms->send($acceptPhone, [
                'template' => $templateCode,
                'data' => $this->getSmsTemplateData($templateParams),
            ]);

            // 短信发送成功的错误处理
            $sendStatus = $this->resultHandle($result);
        } catch (NoGatewayAvailableException $e) {
            // 短信发送异常的错误处理
            $sendStatus = false;
            $this->exceptionHandle($e);
        }
        // 记录日志
        helper::logInfo('发送短信', [
            'gateway' => $this->smsConfig['default'],
            'acceptPhone' => $acceptPhone,
            'templateCode' => $templateCode,
            'templateParams' => $templateParams,
            'sendStatus' => $sendStatus,
        ]);
        // 存在异常时抛错
        $sendStatus === false && throwError($this->getError());
        return $sendStatus;
    }

    /**
     * 短信发送成功的错误处理
     * @param array $result
     * @return bool
     */
    private function resultHandle(array $result): bool
    {
        // 腾讯云短信错误: 模板ID不正确
        if ($this->gateway === 'qcloud') {
            $response = $result[$this->gateway]['result']['Response']['SendStatusSet'];
            if (isset($response[0]) && isset($response[0]['Code']) && $response[0]['Code'] !== 'Ok') {
                $this->error = '请检查后台短信平台参数和模板ID是否正确';
                return false;
            }
        }
        return true;
    }

    /**
     * 短信发送异常的错误处理
     * @param NoGatewayAvailableException $e
     */
    private function exceptionHandle(NoGatewayAvailableException $e)
    {
        // 短信发送失败
        $err = $e->getLastException();
        if ($err instanceof \GuzzleHttp\Exception\ClientException) {
            $body = (string)$err->getResponse()->getBody();
            $result = helper::jsonDecode($body);
            if (isset($result['Message'])) {
                // $errMsg = $result['Message'];
                $this->error = '请检查后台短信平台的参数设置';
                return;
            }
            if (isset($result['message'])) {
                $this->error = $result['message'];
                return;
            }
        }
//        if ($err instanceof \Overtrue\EasySms\Exceptions\GatewayErrorException) {
//            $this->error = $err->getMessage();
//            return;
//        }
        $this->error = $err->getMessage();
    }

    /**
     * 生成短信模板数据
     * @param array $templateParams
     * @return array
     */
    private function getSmsTemplateData(array $templateParams): array
    {
        if ($this->gateway === 'qcloud') {
            $templateParams = array_values($templateParams);
        }
        return $templateParams;
    }
}
