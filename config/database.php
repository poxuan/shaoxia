<?php

return [
    'default' => [
        "driver" => 'PdoMysql',
        "host"   => '127.0.0.1', // 读写分离时，使用数组，第一个表示写，后面的表示读
        "port"   => 3306, // 端口
        "user"   => 'tongcheng', // 用户名
        "pass"   => '110120', // 密码
        "db"     => 'tongcheng', // 数据库
        "prefix" => '', // 表前缀
        'charset' => 'utf8mb4',
    ],
];