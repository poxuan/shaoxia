<?php

return [
    // 绑定
    "bindings" => [],
    // 别名
    'alias' => [
        'Media' => Shaoxia\Media\Media::class,
        'Algorithm'  => Shaoxia\Algorithm\Algorithm::class,
        'Decode' => Shaoxia\Decode\Decode::class,
        'Filedb' => Shaoxia\Storage\Filedb::class,
    ],
    // 中间件
    "middleware" => [
        Shaoxia\Middleware\Test1::class,
        Shaoxia\Middleware\Test2::class,
    ],
    // 路由中间件
    'route_middleware' => [

    ]
];