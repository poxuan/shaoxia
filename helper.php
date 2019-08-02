<?php

$configs = require_once __DIR__ . '/config/config.php';

function config($name = '', $default = null)
{
    global $configs;
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
    return preg_match("/cli/i", php_sapi_name()) ? 1 : 0;
}

function getCf()
{
    global $argv;
    list($dc, $df) = explode('@', config('default_uri', 'Index@index'));
    if (is_cli()) {
        $cf = explode('\\', $argv[1] ?? '');
    } else {
        $cf = explode('/', explode('#', explode("?", str_replace('index.php', '', $_SERVER['REQUEST_URI']))[0])[0]);
    }
    $cf = array_values(array_filter($cf));
    return [$cf[0] ?? $dc, $cf[1] ?? $df];
}

function myapplication()
{
    require_once __DIR__ . '/application.php';
    list($clazz, $func) = getCf();
    return application::getInstance($clazz, $func, is_cli());
}

function myexplode(string $glue, string $str)
{
    if ($glue !== '') {
        return explode($glue, $str);
    }
    $word = [];
    $len = $lastLen = 1;
    $lastPos = 0;
    for ($i = 0; $i < strlen($str); $i += $len) {
        if (ord($str[$i]) >= 0xFC) {
            $len = 6;
        } elseif (ord($str[$i]) >= 0xF8) {
            $len = 5;
        } elseif (ord($str[$i]) >= 0xF0) {
            $len = 4;
        } elseif (ord($str[$i]) >= 0xE0) {
            $len = 3;
        } elseif (ord($str[$i]) >= 0xC0) {
            $len = 2;
        } else {
            $len = 1;
        }
        if ($len > 1 || $lastLen > 1) {
            $word[] = substr($str, $lastPos, $i - $lastPos);
            $lastPos = $i;
        } elseif (in_array($str[$i], [' ', "\t", "\r", "\n"])) {
            $word[] = substr($str, $lastPos, $i - $lastPos);
            $word[] = "\n";
            $lastPos = $i + 1;
            $lastPos = $i + 1;
        }
        $lastLen = $len;
    }
    $word[] = substr($str, $lastPos);
    return array_filter($word, function ($item) {return $item !== '';});
}
