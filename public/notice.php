<<<<<<< HEAD
<<<<<<< HEAD
<?php

// [ 应用入口文件 ]
namespace think;

// 检测PHP环境
if (version_compare(PHP_VERSION, '7.1.0', '<')) die('require PHP > 7.1.0 !');

// 加载核心文件
require __DIR__ . '/../vendor/autoload.php';

// 执行HTTP应用并响应
$http = (new App())->http;

// 手动指定设置路由
$_SERVER['PATH_INFO'] = '/notify/wxpay';

// $http->name()用于设置当前入口文件绑定的应用
$response = $http->name('api')->run();

$response->send();

$http->end($response);
=======
<?php

// [ 应用入口文件 ]
namespace think;

// 检测PHP环境
if (version_compare(PHP_VERSION, '7.1.0', '<')) die('require PHP > 7.1.0 !');

// 加载核心文件
require __DIR__ . '/../vendor/autoload.php';

// 执行HTTP应用并响应
$http = (new App())->http;

// 手动指定设置路由
$_SERVER['PATH_INFO'] = '/notify/wxpay';

// $http->name()用于设置当前入口文件绑定的应用
$response = $http->name('api')->run();

$response->send();

$http->end($response);
>>>>>>> 97de442374609cd47088712b56693b12ba4c5636
=======
<?php

// [ 应用入口文件 ]
namespace think;

// 检测PHP环境
if (version_compare(PHP_VERSION, '7.1.0', '<')) die('require PHP > 7.1.0 !');

// 加载核心文件
require __DIR__ . '/../vendor/autoload.php';

// 执行HTTP应用并响应
$http = (new App())->http;

// 手动指定设置路由
$_SERVER['PATH_INFO'] = '/notify/wxpay';

// $http->name()用于设置当前入口文件绑定的应用
$response = $http->name('api')->run();

$response->send();

$http->end($response);
>>>>>>> 588a41087f3b58d46fc5abbf932f282ef21891df
