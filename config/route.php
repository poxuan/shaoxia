<?php

use Shaoxia\Boot\Route;

// 基础模式匹配
Route::basePattern("id", "[0-9]+");

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


Route::get("decode/jsc", "Decode@jsc");