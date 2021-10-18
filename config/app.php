<?php

return [
    // 绑定
    "bindings" => [
        Shaoxia\Boot\ExceptionHandler::class => Shaoxia\Exceptions\ErrorHandler::class, // 异常处理绑定
    ],
    // 别名
    'alias' => [
        "Route" => Shaoxia\Boot\Route::class
    ],
    // 全局中间件
    "middleware" => [
        App\Middleware\Test1::class,
    ],
    // 路由中间件
    'route_middleware' => [
        'test2' => App\Middleware\Test2::class,
        'test3' => App\Middleware\Test3::class,
    ],
    // 载入路由组
    'router' => [
        'api'
    ]
];