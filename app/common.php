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

/**
 * 应用公共函数库文件
 */

use think\Response;
use think\facade\Env;
use think\facade\Log;
use think\facade\Config;
use think\facade\Request;
use app\common\library\helper;
use app\common\model\Integrals;
use cores\exception\BaseException;
use cores\exception\DebugException;
use think\exception\HttpResponseException;
use think\facade\Db;
use Yansongda\Pay\Pay;
use think\response\Json;
/**
 * 打印调试函数 html
 * @param $content
 * @param bool $export
 */
function pre($content, bool $export = false)
{
    $output = $export ? var_export($content, true) : print_r($content, true);
    echo "<pre>{$output}</pre>";
    app_end();
}

/**
 * 打印调试函数 json
 * @param $content
 * @param bool $export
 * @throws DebugException
 */
function pree($content, bool $export = false)
{
    $output = $export ? var_export($content, true) : $content;
    throw new DebugException([], $output);
}

/**
 * 输出错误信息
 * @param string $message 报错信息
 * @param int|null $status 状态码,默认为配置文件status.error
 * @param array $data 附加数据
 * @throws BaseException
 */
function throwError(string $message, ?int $status = null, array $data = [])
{
    is_null($status) && $status = config('status.error');
    throw new BaseException(['status' => $status, 'message' => $message, 'data' => $data]);
}

/**
 * 下划线转驼峰
 * @param string $uncamelized_words
 * @param string $separator
 * @return string
 */
function camelize(string $uncamelized_words, string $separator = '_'): string
{
    $uncamelized_words = $separator . str_replace($separator, " ", strtolower($uncamelized_words));
    return ltrim(str_replace(" ", "", ucwords($uncamelized_words)), $separator);
}

/**
 * 驼峰转下划线
 * @param string $camelCaps
 * @param string $separator
 * @return string
 */
function uncamelize(string $camelCaps, string $separator = '_'): string
{
    return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $camelCaps));
}

/**
 * 生成密码hash值
 * @param string $password
 * @return string
 */
function encryption_hash(string $password): string
{
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * 获取当前域名及根路径
 * @return string
 */
function base_url(): string
{
    static $baseUrl = '';
    if (empty($baseUrl)) {
        $request = Request::instance();
        // url协议，设置强制https或自动获取
        $scheme = Config::get('route')['url_force_https'] ? 'https' : $request->scheme();
        // url子目录
        $rootUrl = root_url();
        // 拼接完整url
        $baseUrl = "{$scheme}://" . $request->host() . $rootUrl;
    }
    return $baseUrl;
}

/**
 * 获取当前url的子目录路径
 * @return string
 */
function root_url(): string
{
    static $rootUrl = '';
    if (empty($rootUrl)) {
        $request = Request::instance();
        $subUrl = str_replace('\\', '/', dirname($request->baseFile()));
        $rootUrl = $subUrl . ($subUrl === '/' ? '' : '/');
    }
    return $rootUrl;
}

/**
 * 获取当前uploads目录访问地址
 * @return string
 */
function uploads_url(): string
{
    return base_url() . 'uploads';
}

/**
 * 获取当前temp目录访问地址
 * @return string
 */
function temp_url(): string
{
    return base_url() . 'temp/';
}

/**
 * 获取当前的应用名称
 * @return mixed
 */
function app_name()
{
    return app('http')->getName();
}

/**
 * 获取web根目录
 * @return string
 */
function web_path(): string
{
    static $webPath = '';
    if (empty($webPath)) {
        $request = Request::instance();
        $webPath = dirname($request->server('SCRIPT_FILENAME')) . DIRECTORY_SEPARATOR;
    }
    return $webPath;
}

/**
 * 获取runtime根目录路径
 * @return string
 */
function runtime_root_path(): string
{
    return dirname(runtime_path()) . DIRECTORY_SEPARATOR;
}

/**
 * 写入日志 (使用tp自带驱动记录到runtime目录中)
 * @param $value
 * @param string $type
 */
function log_record($value, string $type = 'info')
{
    $content = is_string($value) ? $value : print_r($value, true);
    Log::record($content, $type);
}

/**
 * 多维数组合并
 * @param array $array1
 * @param array $array2
 * @return array
 */
function array_merge_multiple(array $array1, array $array2): array
{
    $merge = $array1 + $array2;
    $data = [];
    foreach ($merge as $key => $val) {
        if (
            isset($array1[$key])
            && is_array($array1[$key])
            && isset($array2[$key])
            && is_array($array2[$key])
        ) {
            $data[$key] = is_assoc($array1[$key]) ? array_merge_multiple($array1[$key], $array2[$key]) : $array2[$key];
        } else {
            $data[$key] = $array2[$key] ?? $array1[$key];
        }
    }
    return $data;
}

/**
 * 判断是否为自定义索引数组
 * @param array $array
 * @return bool
 */
function is_assoc(array $array): bool
{
    if (empty($array)) return false;
    return array_keys($array) !== range(0, count($array) - 1);
}

/**
 * 二维数组排序
 * @param $arr
 * @param $keys
 * @param bool $desc
 * @return array
 */
function array_sort($arr, $keys, bool $desc = false): array
{
    $key_value = $new_array = array();
    foreach ($arr as $k => $v) {
        $key_value[$k] = $v[$keys];
    }
    if ($desc) {
        arsort($key_value);
    } else {
        asort($key_value);
    }
    reset($key_value);
    foreach ($key_value as $k => $v) {
        $new_array[$k] = $arr[$k];
    }
    return $new_array;
}

/**
 * 隐藏敏感字符
 * @param string $value
 * @return string
 */
function substr_cut(string $value): string
{
    $strlen = mb_strlen($value, 'utf-8');
    if ($strlen <= 1) return $value;
    $firstStr = mb_substr($value, 0, 1, 'utf-8');
    $lastStr = mb_substr($value, -1, 1, 'utf-8');
    return $strlen == 2 ? $firstStr . str_repeat('*', $strlen - 1) : $firstStr . str_repeat("*", $strlen - 2) . $lastStr;
}

/**
 * 获取当前系统版本号
 * @return mixed|null
 * @throws Exception
 */
function get_version()
{
    static $version = [];
    if (!empty($version)) {
        return $version['version'];
    }
    // 读取version.json文件
    $file = root_path() . '/version.json';
    if (!file_exists($file)) {
        throw new Exception('version.json not found');
    }
    // 解析json数据
    $version = helper::jsonDecode(file_get_contents($file));
    if (!is_array($version)) {
        throw new Exception('version cannot be decoded');
    }
    return $version['version'];
}

/**
 * 获取全局唯一标识符
 * @param bool $trim
 * @return string
 */
function get_guid_v4(bool $trim = true): string
{
    // Windows
    if (function_exists('com_create_guid') === true) {
        $charid = com_create_guid();
        return $trim == true ? trim($charid, '{}') : $charid;
    }
    // OSX/Linux
    if (function_exists('openssl_random_pseudo_bytes') === true) {
        $data = openssl_random_pseudo_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);    // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);    // set bits 6-7 to 10
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
    // Fallback (PHP 4.2+)
    mt_srand(intval((double)microtime() * 10000));
    $charid = strtolower(md5(uniqid((string)rand(), true)));
    $hyphen = chr(45);                  // "-"
    $lbrace = $trim ? "" : chr(123);    // "{"
    $rbrace = $trim ? "" : chr(125);    // "}"
    return $lbrace .
        substr($charid, 0, 8) . $hyphen .
        substr($charid, 8, 4) . $hyphen .
        substr($charid, 12, 4) . $hyphen .
        substr($charid, 16, 4) . $hyphen .
        substr($charid, 20, 12) .
        $rbrace;
}

/**
 * 模拟POST请求
 * @param string $url 请求地址
 * @param mixed $data 请求数据
 * @param false $useCert 是否引入微信支付证书
 * @param array $sslCert 证书路径
 * @return mixed|bool|string
 * @throws \cores\exception\BaseException
 */
//function post(string $url, $data = [], $header= ['Content-type: application/json;'],bool $useCert = false, array $sslCert = [])
//{
//    $curl = curl_init();
//    curl_setopt($curl, CURLOPT_URL, $url);
//    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
//    curl_setopt($curl, CURLOPT_HEADER, false);
//    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
//    curl_setopt($curl, CURLOPT_POST, TRUE);
//    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
//    if ($useCert == true) {
//        // 设置证书：cert 与 key 分别属于两个.pem文件
//        curl_setopt($curl, CURLOPT_SSLCERTTYPE, 'PEM');
//        curl_setopt($curl, CURLOPT_SSLCERT, $sslCert['certPem']);
//        curl_setopt($curl, CURLOPT_SSLKEYTYPE, 'PEM');
//        curl_setopt($curl, CURLOPT_SSLKEY, $sslCert['keyPem']);
//    }
//    $result = curl_exec($curl);
//    if ($result === false) {
//        throwError(curl_error($curl));
//    }
//    curl_close($curl);
//    return $result;
//}

function http_post($url, $post_data, $header = [], $proxy = [])
{
    $header = [
        'access-token' => '691fbe3ef3ac4eea3550de71e86c97bf'
    ];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if (!empty($header)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    }
    if (!empty($proxy)) {
        curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
        curl_setopt($ch, CURLOPT_PROXY, "{$proxy['ip']}:{$proxy['port']}");
        curl_setopt($ch, CURLOPT_PROXYUSERPWD, "{$proxy['username']}:{$proxy['password']}");
    }
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}

function post($url, $post_data, $header, $timeout = 20){
    $ch = curl_init();
    curl_setopt ($ch, CURLOPT_URL, $url);
    curl_setopt ($ch, CURLOPT_POST, 1);
    if($post_data != ''){
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_HEADER, false);
    $file_contents = curl_exec($ch);
    curl_close($ch);
    return $file_contents;
}

function post_test($url, $post_data, $header){
    $curl = curl_init();
//设置抓取的url
    curl_setopt($curl, CURLOPT_URL, $url);
//设置头文件的信息作为数据流输出
    curl_setopt($curl, CURLOPT_HEADER, 0);
//设置获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//设置post方式提交
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
//设置post数据
//    $post_data = array(
//        "title" => "1290800466",
//        "content" => "3424243243"
//    );
//post提交的数据
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
//执行命令
    $data = curl_exec($curl);
//关闭URL请求
    curl_close($curl);
//显示获得的数据
    return $data;
    print_r($data);
}

/**
 * 时间戳转换日期
 * @param int|string $timeStamp 时间戳
 * @param bool $withTime 是否关联时间
 * @return false|string
 */
function format_time($timeStamp, bool $withTime = true)
{
    $format = 'Y-m-d';
    $withTime && $format .= ' H:i:s';
    return $timeStamp ? date($format, $timeStamp) : '';
}

/**
 * 左侧填充0
 * @param $value
 * @param int $padLength
 * @return string
 */
function pad_left($value, int $padLength = 2): string
{
    return \str_pad($value, $padLength, "0", STR_PAD_LEFT);
}

/**
 * 重写trim方法 (解决int类型过滤后后变为string类型)
 * @param $str
 * @return mixed
 */
function my_trim($str)
{
    return is_string($str) ? trim($str) : $str;
}

/**
 * 重写htmlspecialchars方法 (解决int类型过滤后后变为string类型)
 * @param $string
 * @return mixed
 */
function my_htmlspecialchars($string)
{
    return is_string($string) ? htmlspecialchars($string) : $string;
}

/**
 * 过滤emoji表情
 * @param $text
 * @return null|string|string[]
 */
function filter_emoji($text)
{
    if (!is_string($text)) {
        return $text;
    }
    // 此处的preg_replace用于过滤emoji表情
    // 如需支持emoji表情, 需将mysql的编码改为utf8mb4
    return preg_replace('/[\xf0-\xf7].{3}/', '', $text);
}

/**
 * 根据指定长度截取字符串
 * @param $str
 * @param int $length
 * @return bool|string
 */
function str_substr($str, int $length = 30)
{
    if (strlen($str) > $length) {
        $str = mb_substr($str, 0, $length);
    }
    return $str;
}

/**
 * 结束执行
 */
function app_end()
{
    throw new HttpResponseException(Response::create());
}

/**
 * 当前是否为调试模式
 * @return bool
 */
function is_debug(): bool
{
    return (bool)Env::instance()->get('APP_DEBUG');
}

/**
 * 文本左斜杠转换为右斜杠
 * @param string $string
 * @return mixed
 */
function convert_left_slash(string $string)
{
    return str_replace('\\', '/', $string);
}


/**
 * 隐藏手机号中间四位 13012345678 -> 130****5678
 * @param string $mobile 手机号
 * @return string
 */
function hide_mobile(string $mobile): string
{
    return substr_replace($mobile, '*****', 3, 8);
}

/**
 * 获取当前登录的商城ID
 * @return int $storeId
 */
function getStoreId(): int
{
    return 10001;
}


function getAliConfig()
{

    $config = [
        //客户APPID
        'app_id' => '2021003128650213',


//        'notify_url' => 'http://yansongda.cn/notify.php',
//        'return_url' => 'http://yansongda.cn/return.php',
        'notify_url' => "https://shucang.zhongyuansc.net/index.php/api/Checkout/returnnotify",//异步回调
        'return_url' => Request::instance()->domain() . "/#/pages/order/index",//同步回调

        //客户公钥
        'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAxCbj1/n5aHbIMb84YwU3nSRMAuzzR+r2XdpjzwF+jkiGp24jvGKpgIt2+HZlVZvUnJo85/Zjyio+x6SAxVXNsy92DMtF/6AzLmU81nuv8qpQoUEEWHpa99QgPmjlbKN2Ep7QwQG3LVEeshat0AXPJ9szOR+6UVxO9qlUvmor+sAiznQuUKnpEFGCMIyn0Xak6OUFMB3wgvum7O0E9PFkk0XqKwRgaThWQxFHnIGZT+94//yo1NCBHPrET5sZyNB9g//0IbPHgvNLchWu8iYXHGJF+xztIqA0WPRFXdoULjmYPG1Go9zGJ15hideXCPrvDwYO9uoeuk45nWXdYuR9uwIDAQAB',


        // 加密方式： **RSA2**

        ////客户公钥
        'private_key' => 'MIIEowIBAAKCAQEAphSGqdv0BYQEp+Cnx/zySez3eB/4CP+NIQ4AINJE+RHxxPwaivqO+hiyv3AhZxq7jYsKZX/dP+r70EfmY0d+ZIyLXk5aT77O1fplD53m8PP0Cid1iehLwyfUkXnaSMtT1niPe3KkgdtVDDuEVQN5IgHzGEbhvoUAMzS630Z3B2JylytTR171bfmUYUT8bShpYwpE1wGASxuwYDcUf9ZtDOjlluVdl74gxQqQst1oAnGXpfvKZ+doareH+tdjuKlaGXc5xJCw6n5y86XiLQ4UYaFME23qfJYKRIAFyUOMVlDPoSchE9/SMpi18Num1VgoSlCViFUlcoT3M80aWBxeuwIDAQABAoIBAQCKdeZnSpdA2fW/uE5pLvsYotUWcyCo79VLT47Pzj3uoQU9wyil4Yq9+0Va0TMn8GOY9+htApMErPXPiYIMFxjK31l6ADzO6zcpsh37TRuymtBEfu1BUkeHpndT6ZEQy2NUFSMaOlPoZ2GaVyxWbBTbQ2DWRpIB8oReLIH3j5nArvsCywnraakeVg6UlBg1U4996l9kO9yfG15/XRbrzmgv+XqKsIlACygCQi8xBOEBHcRJN7ckUP5rUXSeMM23cO2AT8N9GpirBEAnfgsGs0D7vvvxTs8pLC60JTNdHoskm6L/YaHWLr+lb2rbhAUIGdAFaHN6nWYK3ZJtgTQr0wgRAoGBAOZ/G6+SzHDxGScmzzKUzLFP0r8Zk6UrTk95J5hckDeZXeHLOWEB8GdE+GD5O1Gt8vPi6mUp0Q5SXj53NZ3rxyfDK6xWi2g2Lid2TbqqtFXbHllAt9Jmsf9RY0YVJpDCVLzkq8GTfJBGkR26C7D9IEtd27ERJ1IMzGTX6MaSRcTfAoGBALh0zagUUyZgj+PXLtuAnIrMR+MUTxtA0yvoTwk/e8DeKkmPZ0MA9z8JVUiggoH0DncMqBXJotqRHqxeOf3pTVZrCTyJiGaeHo8JCvcDEOzsvyqiTsneKBM7XIKFNB4wM3makPBYqYxmZcT5UTfXpIQ22VL9HuK8bjVnv2YIF+WlAoGAY9o2vTXEGT1pHxvh8NCjaEDjVGQZIRmEoEslCw02cM+g6xsl7U9wvNwVGbV+rCDGOj9YOaEG7sRiDX+JG7OzoBcdyVfopqmqDvBiNfaTjwoQuKuftqdEfw3cCtVI0rdNDFOn0IE7Giln3k/llrPhlKTBcpCyzwm6Vgluzu85CFMCgYBIBtwzkNQzPSuSxXj3n44BKOm9QzXsp3COSaVrMA3r7xTGKqgg4uSpCcTymjK7+ggX9qo/77+W52n51sWAX7SiVSODLxVDNOUr51UPxXO7gGrZhA2OCCDP+bMTCF6klx/SkUedgpGaUHXW56AOVTdZpW1RWaPQ19GVKxDzD5/9PQKBgEEKYXBnCfMO2iJxAmMoUUbTapRFVxy6UWzY/HIEVeqUl5h5a82Loqz0lex6Q1hyPhIQNMoyVpBXWCBQYN0SG/YUXYT6INK1AiY9cpncNee851zBkJg7ymIGBluWP2iB9KUfe+kXHEmP9lsgP5v+cj0QZwSwMnJLjuF0JpGEFIfH',


        // 使用公钥证书模式，请配置下面两个参数，同时修改ali_public_key为以.crt结尾的支付宝公钥证书路径，如（./cert/alipayCertPublicKey_RSA2.crt）
//         'app_cert_public_key' => './cert/appCertPublicKey.crt', //应用公钥证书路径
        // 'alipay_root_cert' => './cert/alipayRootCert.crt', //支付宝根证书路径
        'log' => [ // optional
            'file' => './logs/alipay.log',
            'level' => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
            'type' => 'single', // optional, 可选 daily.
            'max_file' => 30, // optional, 当 type 为 daily 时有效，默认 30 天
        ],
        'http' => [ // optional
            'timeout' => 5.0,
            'connect_timeout' => 5.0,
            // 更多配置项请参考 [Guzzle](https://guzzle-cn.readthedocs.io/zh_CN/latest/request-options.html)
        ],
        // 'mode' => 'dev', // optional,设置此参数，将进入沙箱模式  默认dev

    ];

    $alipay = Pay::alipay($config);
    return $alipay;
}

function getWeAliConfid()
{
    $config = [
        // 'appid' => 'wx6e93f79fa8fbf086', // APP APPID
        'app_id' => 'wx6e93f79fa8fbf086', // 公众号 APPID
        // 'miniapp_id' => 'wx6e93f79fa8fbf086', // 小程序 APPID
        'mch_id' => '1624406025',
        'key' => 'daomengkejiyouxiangongsi12345678',
        'notify_url' => "https://shucang.zhongyuansc.net/index.php/api/Checkout/returnnotifywe",//异步回调
        // 'cert_client' => './cert/apiclient_cert.pem', // optional，退款等情况时用到
        // 'cert_key' => './cert/apiclient_key.pem',// optional，退款等情况时用到
        'log' => [ // optional
            'file' => './logs/wechat.log',
            'level' => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
            'type' => 'single', // optional, 可选 daily.
            'max_file' => 30, // optional, 当 type 为 daily 时有效，默认 30 天
        ],
        'http' => [ // optional
            'timeout' => 5.0,
            'connect_timeout' => 5.0,
            // 更多配置项请参考 [Guzzle](https://guzzle-cn.readthedocs.io/zh_CN/latest/request-options.html)
        ],
//        'mode' => 'dev', // optional, dev/hk;当为 `hk` 时，为香港 gateway。
    ];
    $alipay = Pay::wechat($config);


    return $alipay;
}

/**
 * 盲盒概率
 * @param $proArr
 * @return int|string
 */
function get_rand($proArr) {
    $result = '';

    //概率数组的总概率精度
    $proSum = array_sum($proArr);

    //概率数组循环
    foreach ($proArr as $key => $proCur) {
        $randNum = mt_rand(1, $proSum);
        if ($randNum <= $proCur) {
            $result = $key;
            break;
        } else {
            $proSum -= $proCur;
        }
    }
    unset ($proArr);

    return $result;
}

/**
 * 生成随机hash
 * @param $length
 * @return string
 */
function randomkeys($length) {
    $returnStr='';
    $pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
    for($i = 0; $i < $length; $i ++) {
        $returnStr .= $pattern {mt_rand ( 0, 61 )}; //生成php随机数
    }
    return $returnStr;
}


/**
 * 生成随机编号
 * @param $length
 * @return string
 */
function random($length) {
    $returnStr='';
    $pattern = '1234567890';
    for($i = 0; $i < $length; $i ++) {
        $returnStr .= $pattern {mt_rand ( 0, 9 )}; //生成php随机数
    }
    //判断是否重复
    $no = \app\common\model\Coll::where('coll_no',$returnStr)->find();
    if (!empty($no)){
        return random($length);
    }
    return $returnStr;
}

function jdConfig(){
    $data = [
        'customerNum' => '10001116536583322991023',
        'shopNum' => '10001216536585039620385',
        'authId' => 'ojiuXuA3uRjfHd6LtbSfs51Q2UjQ',
        'bankType' => 'JD',
        'requestNum' => 'WX202115069853421',
        'amount' => '1',
        'callbackUrl' => 'https://market.web3metadao.cn/index.php/api/notify/jdPay',
    ];

    return $data;
}

function jdConfigBank(){
    $data = [
        "version" => "V4.0",
        "customerNum" => "10001116536583322991023",
        "shopNum" => "10001216536585039620385",
        "clientIp" => "127.0.0.1",
        "callbackUrl" => "https://www.web3metadao.cn/index.php/api/notify/jdPay",
        "terminalType" => "IMEI",
        "terminalId" => "122121212121",
        "appType" => "H5",
        "appName" => "某宝",
        "tradeScene" => "QUICKPAY",
        "source" => "API",
    ];

    return $data;
}

function renderSuccess($data = [], string $message = 'success'): Json
{
    if (is_string($data)) {
        $message = $data;
        $data = [];
    }
    return renderJson(config('status.success'), $message, $data);
}
function renderJson(int $status = null, string $message = '', array $data = []): Json
{
    return json(compact('status', 'message', 'data'));
}
function renderError(string $message = 'error', array $data = []): Json
{
    return renderJson(config('status.error'), $message, $data);
}

/**
 * 获取提现手续费
 * @return int
 */
function withdrawalRate()
{
    return 1.5;
}

/**
 * 费率减扣+价格计算
 * @param [type] $type 1：提现，2：转卖
 * @param [type] $user_id
 * @param [type] $price
 * @return array
 */
function rateLess($type = 1, $user_id, $price)
{
    $rate = Db::name('coll')
                ->alias('a')
                ->leftJoin('goods b', 'a.goods_id = b.goods_id')
                ->leftJoin('rate_control c', 'b.rate_id = c.id')
                ->field('c.withdrawal_rate, c.transac_rate')
                ->where('b.rate_id', '>', 0)
                ->where('a.user_id', $user_id)
                ->find();

    if(!$rate){
        if($type == 1) {
            // 提现费率
            $result = [
                'ratedis' => 0,
                'price'   => number_format($price - ($price * 0.015), 2)
            ];
        }else{
            // 转卖费率
            // 如果商品单独设置了手续费则使用商品的
            $config = Integrals::where('id', 1)->field('charges, copyright')->find();
            $price = $price * (100 - (($config['charges'] + $config['copyright']))) / 100;
            $result = [
                'ratedis' => 0,
                'price'   => number_format($price, 2)
            ];
        }
    }else{
        if($type == 1) {
            // 提现费率
            $price = $price - ($price * (0.015 - ($rate['withdrawal_rate'] / 100)));
            $result = [
                'ratedis'   => $rate['withdrawal_rate'],
                'price'     => number_format($price, 2)
            ];
        }else{
            // 转卖费率
            // 如果商品单独设置了手续费则使用商品的
            $config = Integrals::where('id', 1)->field('charges, copyright')->find();
            $price = $price * (100 - (($config['charges'] + $config['copyright']) - $rate['withdrawal_rate'])) / 100;

            $result = [
                'ratedis' => $rate['withdrawal_rate'],
                'price'   => number_format($price, 2)
            ];
        }
    }
    return $result;
}