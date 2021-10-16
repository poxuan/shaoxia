<?php

/**
 * 获取配置参数
 * 
 * @param string $name   // 参数名，为空表示全部，可以使用点号获取下一层级
 * @param mixed $default // 默认值，$name找不到时，使用此值替代
 * @param string $file   // 从 CONFIG_PATH 下哪一个文件中查找
 * 
 * @return mixed
 */
function config($name = '', $default = null, $file = 'config')
{
    $configs = require CONFIG_PATH.$file.'.php';
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

/**
 * 是否时命令行模式
 * 
 * @return bool
 */
function is_cli()
{
    $sapi = php_sapi_name();
    return $sapi == 'cli' ? true : false;
}

/**
 * 命令行模式下的uri
 * 
 * @return string
 */
function cli_uri() {
    global $argv;
    return $argv[1] ?? '';
}

/**
 * 获取 Application 实例
 * 
 * @return application
 */
function app()
{
    require_once ROOT_PATH . '/application.php';
    return application::getInstance(is_cli());
}

/**
 * 获取请求参数或请求实例
 * 
 * @return mixed|Shaoxia\Boot\Request
 */
function request($key = '') {
    if (empty($key)) {
        return app()->getRequest();
    } else {
        return app()->getRequest()->get($key);
    }
}

/**
 * 获取响应实例
 * 
 * @return Shaoxia\Boot\Response
 */
function response() {
    return app()->getResponse();
}
