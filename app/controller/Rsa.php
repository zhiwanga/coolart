<?php
declare (strict_types = 1);

namespace app\controller;
use app\api\service\User as UserService;
use think\facade\Db;

class Rsa
{
    /**     
     * 获取私钥     
     * @return string     
     */   
    private static function getPrivateKey() 
    {        
        $abs_path = '/www/wwwroot/serve/rsa_private_key.pem';
        // $abs_path = public_path().'..\rsa_private_key.pem';
        $content = file_get_contents($abs_path);    
        return openssl_pkey_get_private($content);    
    }

    /**
     * 获取公钥
     * @return string
     */   
    private static function getPublicKey()
    {   
        $abs_path = '/www/wwwroot/serve/rsa_public_key.pem';
        // $abs_path = public_path().'..\rsa_public_key.pem';
        $content = file_get_contents($abs_path);    
        return openssl_pkey_get_public($content);
    }

    /**     
     * 私钥加密     
     * @param string $data     
     * @return null|string     
     */   
    public static function privEncrypt($data = '')    
    {        
        if (!is_string($data)) {            
            return null;       
        }        
        return openssl_private_encrypt($data,$encrypted, self::getPrivateKey()) ? base64_encode($encrypted) : null;    
    }

    /**
     * 公钥加密     
     * @param string $data     
     * @return null|string     
     */   
    public static function publicEncrypt($data = '')   
    {        
        if (!is_string($data)) {            
            return null;        
        }        
        return openssl_public_encrypt($data,$encrypted,self::getPublicKey()) ? base64_encode($encrypted) : null;    
    }

    /**     
     * 私钥解密     
     * @param string $encrypted     
     * @return string     
     */   
    public static function privDecrypt($encrypted = '')    
    {        
        if (!is_string($encrypted)) {            
            return null;        
        }
        return (openssl_private_decrypt(base64_decode($encrypted), $decrypted, self::getPrivateKey())) ? $decrypted : null;    
    }

    /**     
     * 公钥解密     
     * @param string $encrypted     
     * @return null     
     */   
    public static function publicDecrypt($encrypted = '')    
    {        
        if (!is_string($encrypted)) {            
            return null;        
        }
        return (openssl_public_decrypt(base64_decode($encrypted), $decrypted, self::getPublicKey())) ? $decrypted : null;    
    }

    /**
     * rsa密钥验证
     *
     * @param integer $type 1:收款编辑
     * @param [type] $str rsa 加密字符串
     * @param [type] $user_id
     * @return bool
     */
    public static function rsaContCheck($type = 0, $str = '', $user_id = 0)
    {
        $rsacont = self::privDecrypt($str);
        $user = Db::name('user')->where('id', $user_id)->find();
        if(!$user) {
            return false;
        }else{
            $result = true;
            switch ($type) {
                case 1: // 收款编辑
                    $pswd = substr($rsacont, 0, strlen($rsacont)-10);
                    if(!password_verify($pswd, $user['password'])){
                        $result = false;
                    }
                    break;
                default:
                    return false;
                    break;
            }
        }
        return $result;
    }
}