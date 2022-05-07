<?php

// 定义路径

use App\Service\WsService;

define("DS", DIRECTORY_SEPARATOR);
define("ROOT_PATH", __DIR__.DS);
define("APP_PATH", ROOT_PATH.'app'.DS);
define("CONFIG_PATH", ROOT_PATH.'config'.DS);
define("ROUTER_PATH", ROOT_PATH.'router'.DS);
define("STORAGE_PATH", ROOT_PATH.'storage'.DS);
define("TMP_PATH", STORAGE_PATH.'tmp'.DS);

require ROOT_PATH . '/vendor/autoload.php';

$app = app();

$act = $argv[1];
$worker = new WsService();
$worker->run($act);
echo $act;
