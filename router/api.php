<?php

// 设置别名后这个就可以不用了, 但编辑器没这么智能
use Shaoxia\Boot\Route;

// 自此以下，全局配置
Route::baseGroup("api", [
    // 以下是目前支持的全部配置参数
    'namespace' => 'App\Controller', // 公共名字空间，默认值就是这个
    'prefix' => '',   // 公共路由前缀
    'middleware' => [ // 组公共中间件
        'test2'
    ],
    'pattern' => [ // 公共参数限制
        'id' => '[0-9]+'
    ]
]);


Route::get("excel/import", "Excel@import");
Route::get("tag/get", "Tag@get");
Route::get("tag/e-{line}", "Tag@export");
Route::get("down/list", "Down@list");
Route::get("down/view", "Down@view");

// 最后要清空当前组，防止多文件串化
Route::clearGroup();