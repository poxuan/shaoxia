<?php

require __DIR__.'/vendor/autoload.php';
require __DIR__.'/helper.php';

$configs = require __DIR__.'/config/config.php';

$cf = $argv[1];

list($clazz, $func) = explode('\\',$cf);

$class = new $configs['alias'][$clazz]();

call_user_func_array([$class, $func], array_slice($argv, 2));
