<?php

/**
 * 获取配置参数
 */
function config($name = '', $default = null, $file = 'config')
{
    $configs = require ROOT_PATH . '/config/'.$file.'.php';
    $value = null;
    if ($name == '') {
        return $configs;
    }
    $names = explode('.', $name);
    for ($i = 0; $i < count($names); $i++) {
        $value = $configs[$names[$i]] ?? null;
    }
    return is_null($value) ? $default : $value;
}

function is_cli()
{
    $sapi = php_sapi_name();
    return $sapi == 'cli' ? true : false;
}

function cli_uri() {
    global $argv;
    return $argv[1];
}

function app()
{
    require_once ROOT_PATH . '/application.php';
    return application::getInstance(is_cli());
}

function request($key = '') {
    if (empty($key)) {
        return app()->getRequest();
    } else {
        return app()->getRequest()->get($key);
    }
}

function response() {
    return app()->getResponse();
}
