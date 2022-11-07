<?php

use think\facade\Route;

// 观链第三方接口
Route::get('market/collectionlist', 'market.Observation/list');  // 获取藏品列表
Route::get('market/collectiondata', 'market.Observation/data');  // 大盘数据接入