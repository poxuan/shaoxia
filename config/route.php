<?php

use Shaoxia\Boot\Route;

// 预定模式匹配
Route::pattern("id", "[0-9]+");

// GET路由,类名必须全拼或在config设置别名
Route::get("image/{angle}", "Media@image");

// resource路由
Route::resource("media", "Media");

Route::get("storage/db_test", "Storage@db_test");