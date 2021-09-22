<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/helper.php';

define("ROOT_PATH", __DIR__);
define("DS", DIRECTORY_SEPARATOR);
define("CONFIG_PATH", __DIR__.DS.'config');
define("SRC_PATH", __DIR__.DS.'src');
define("STORAGE_PATH", __DIR__.DS.'storage');
define("TMP_PATH", __DIR__.DS.'tmp');

app()->exec();
