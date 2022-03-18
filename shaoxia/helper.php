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

//驼峰命名转下划线命名
function toUnderScore($str)
{
    $dstr = preg_replace_callback('/[A-Z]/',function($matchs)
    {
        return '_'.strtolower($matchs[0]);
    },$str);
    return trim(preg_replace('/_{2,}/','_',$dstr),'_');
}

//下划线命名到驼峰命名
function toCamelCase($str)
{
    $array = explode('_', $str);
    $result = $array[0];
    $len=count($array);
    if($len>1)
    {
        for($i=1;$i<$len;$i++)
        {
            $result.= ucfirst($array[$i]);
        }
    }
    return $result;
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
 * @return Shaoxia\Application
 */
function app()
{
    return Shaoxia\Application::getInstance(is_cli());
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

function xml_encode($data, $encoding='utf-8', $root='body') {
    $xml    = '<?xml version="1.0" encoding="' . $encoding . '"?>';
    $xml   .= '<' . $root . '>';
    $xml   .= data_to_xml($data);
    $xml   .= '</' . $root . '>';
    return $xml;
}

function data_to_xml($data) {
    $xml = '';
    foreach ($data as $key => $val) {
        is_numeric($key) && $key = "item id=\"$key\"";
        $xml    .=  "<$key>";
        $xml    .=  ( is_array($val) || is_object($val)) ? data_to_xml($val) : $val;
        list($key, ) = explode(' ', $key);
        $xml    .=  "</$key>";
    }
    return $xml;
}

function jsonp_encode($data, $callback = '') {
    !$callback && $callback = request('callback','callback');
    $res = sprintf("%s(%s);", $callback, json_encode($data));//$arr为返回数据
    return $res;
}

/**
 * 浏览器友好的变量输出
 * @param mixed $var 变量
 * @param boolean $echo 是否输出 默认为True 如果为false 则返回输出字符串
 * @param string $label 标签 默认为空
 * @param boolean $strict 是否严谨 默认为true
 * @return void|string
 */
function dump($var, $echo=true, $label=null, $strict=true) {
    $label = ($label === null) ? '' : rtrim($label) . ' ';
    if (!$strict) {
        if (ini_get('html_errors')) {
            $output = print_r($var, true);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        } else {
            $output = $label . print_r($var, true);
        }
    } else {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        if (!extension_loaded('xdebug')) {
            $output = preg_replace("/\]\=\>\n(\s+)/m", '] => ', $output);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        }
    }
    if ($echo) {
        echo($output);
        return null;
    }
    return $output;
}

function dd($data) {
    dump($data);
    exit;
}