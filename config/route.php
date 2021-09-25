<?php

use Shaoxia\Boot\Route;

// 预定模式匹配
Route::pattern("id", "[0-9]+");

// GET路由,类名必须全拼或在config设置别名
Route::get("image/{angle}", "Media@image");

// resource路由
Route::resource("media", "Media");

Route::get("bdb/insert", "Filedb@bdb_insert");
Route::get("bdb/find", "Filedb@bdb_find");
Route::get("cdb/insert", "Filedb@cdb_insert");
Route::get("cdb/find", "Filedb@cdb_find");
Route::get("jdb/insert", "Filedb@jdb_insert");
Route::get("jdb/find", "Filedb@jdb_find");

Route::get("dh/hide", "MyEncypt@dh_hide");
Route::get("dh/show", "MyEncypt@dh_show");
Route::get("dh/test", "MyEncypt@dh_test");

Route::get("decode/jsc", "Decode@jsc");