<?php
declare (strict_types = 1);

namespace app\controller;

use think\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use think\facade\Cache;

class PhpJwt
{
    private static $key = 'coolart';

    public function jwttoken()
    {
        $token = self::jwtCrypt();
        $res = [
            'code' => 200,
            'msg'  => "success",
            'data' => [
                'token' => $token
            ]
        ];
        return json($res);
    }

    public static function jwtCrypt()
    {
        $time = time();
        $payload = [
            'iss' => 'coolart',
            'iat' => $time,
            'nbf' => $time,
            'exp' => $time + 86400
        ];

        /**
         * IMPORTANT:
         * You must specify supported algorithms for your application. See
         * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
         * for a list of spec-compliant algorithms.
         */
        $jwt = JWT::encode($payload, self::$key, 'HS256');
        Cache::set($jwt, json_encode($payload), 86400);
        return $jwt;
    }

    public static function jwtencrypt($jwt)
    {
        if(!$jwt) {
            return [
                'code' => 0,
                'msg'  => 'token 不存在'
            ];
        }
        $decoded = JWT::decode($jwt, new Key(self::$key, 'HS256'));
        $jwtjson = Cache::get($jwt);
        if(!$jwtjson) {
            return [
                'code' => 0,
                'msg'  => 'token 已过期'
            ];
        }
        $jwtarr = json_decode($jwtjson, true);
        $res = array_diff_assoc($jwtarr, (array)$decoded);
        if($res) {
            return [
                'code' => 0,
                'msg'  => '验签失败'
            ];
        }
        return [
            'code' => 1,
        ];;
    }
}
