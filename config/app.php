<?php

return [
    // 绑定
    "bindings" => [
        
    ],
    // 别名
    'alias' => [
        
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