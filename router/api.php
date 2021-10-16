<?php

use Shaoxia\Boot\Route;

// 自此以下，文件全局配置
Route::baseGroup("api", [
    // 以下是目前支持的全部配置参数
    'namespace' => 'App\Controller', // 公共名字空间
    'prefix' => '',   // 公共路由前缀
    'middleware' => [ // 组公共中间件
        'test2'
    ],
    'pattern' => [ // 公共参数限制
        'id' => '[0-9]+'
    ]
]);

// 匹配空
Route::get("/", "Media@image");

// GET路由,类名必须全拼或在config设置别名
Route::middleware(['test3'])->get("image/dd-{angle}", "Media@image")->pattern(['angle' => '\d+']);

// resource路由
Route::resource("media", "Media");

Route::get("bdb/insert", "Filedb@bdb_insert");
Route::get("bdb/find", "Filedb@bdb_find");
Route::get("cdb/insert", "Filedb@cdb_insert");
Route::get("cdb/find", "Filedb@cdb_find");
Route::get("jdb/insert", "Filedb@jdb_insert");
Route::get("jdb/find", "Filedb@jdb_find");

// 组配置
Route::prefix('dh')->middleware('test3')->group(function($route) {
    $route->get("hide", "MyEncypt@dh_hide");
    $route->get("show", "MyEncypt@dh_show");
    $route->get("test", "MyEncypt@dh_test");
    $route->get("try", "MyEncypt@dh_try");
});

// 组配置
Route::prefix('algo')->middleware('test3')->group(function($route) {
    $route->get("sort", "Algorithm@sort");
    $route->get("nqueen", "Algorithm@nqueen");
    $route->get("sudoku", "Algorithm@sudoku");
    $route->get("floordrop", "Algorithm@floordrop");
});


Route::get("decode/jsc", "Decode@jsc");

// 最后要清空当前组，防止多文件串化
Route::clearGroup();