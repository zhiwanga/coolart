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

return [
    // 验证码图片宽度
    'imageW' => 0,
    // 验证码图片高度
    'imageH' => 0,
    // 验证码位数
    'length' => 4,
    // 是否画混淆曲线
    'useCurve' => false,
    // 是否添加杂点
    'useNoise' => true,
    // 验证码字体大小(px)
    'fontSize' => 26,
    // 验证码字符集合
    // 复杂版：2345678abcdefhijkmnpqrstuvwxyzABCDEFGHJKLMNPQRTUVWXY
    'codeSet' => '23456ACEFHJKLMNPRTUVWXY',
    // 验证码可重复验证的次数
    'checkTimes' => 5,
];