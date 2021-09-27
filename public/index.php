<?php

define("ROOT_PATH", dirname(__DIR__));
define("DS", DIRECTORY_SEPARATOR);
define("CONFIG_PATH", ROOT_PATH.DS.'config');
define("SRC_PATH", ROOT_PATH.DS.'src');
define("STORAGE_PATH", ROOT_PATH.DS.'storage');
define("TMP_PATH", ROOT_PATH.DS.'tmp');


require ROOT_PATH . '/vendor/autoload.php';
require ROOT_PATH . '/helper.php';

$app = require ROOT_PATH.'/bootstrap.php';

$app->exec();
