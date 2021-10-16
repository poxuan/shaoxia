<?php

define("DS", DIRECTORY_SEPARATOR);
define("ROOT_PATH", dirname(__DIR__).DS);
define("CONFIG_PATH", ROOT_PATH.'config'.DS);
define("ROUTER_PATH", ROOT_PATH.'router'.DS);
define("SRC_PATH", ROOT_PATH.'src'.DS);
define("STORAGE_PATH", ROOT_PATH.'storage'.DS);
define("TMP_PATH", ROOT_PATH.'tmp'.DS);

require ROOT_PATH . '/vendor/autoload.php';
require ROOT_PATH . '/helper.php';

$app = require ROOT_PATH.'/bootstrap.php';

$app->exec();
