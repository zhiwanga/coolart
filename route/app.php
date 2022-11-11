<?php

use think\facade\Route;

// 观链第三方
Route::get('market/collectionlist', 'market.Observation/list');  // 获取藏品列表
Route::get('market/collectiondata', 'market.Observation/data');  // 大盘数据接入

// 盯链第三方
Route::get('market/staringlist', 'market.StaringChain/list');  // 获取藏品列表
Route::get('market/staringdata', 'market.StaringChain/data');  // 大盘数据接入
Route::get('market/jwttoken', 'PhpJwt/jwttoken');              // 获取token

// Route::get('market/test', 'market.Observation/test');