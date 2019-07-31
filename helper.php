<?php


function myexplode(string $glue,string $str) 
{
    if ($glue !== '') {
        return explode($glue, $str);
    }
    $word = [];
    $len  = $lastLen = 1;
    $lastPos = 0;
    for($i = 0 ;$i < strlen($str); $i+= $len) {
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
        if ($len > 1 || $lastLen > 1 ) {
            $word[] = substr($str, $lastPos , $i - $lastPos);
            $lastPos = $i;
        } elseif( in_array($str[$i],[' ',"\t","\r","\n"])) {
            $word[] = substr($str, $lastPos , $i - $lastPos);
            $word[] = "\n";
            $lastPos = $i+1;
            $lastPos = $i + 1;
        }
        $lastLen = $len;
    }
    $word[] = substr($str, $lastPos);
    return array_filter($word,function($item){return $item !== '';});
}

function config($name = '', $default = null) 
{
    $value = $configs;
    if ($name == '') 
        return $value;
    $names = explode('.',$name);
    for($i = 0; $i <count($names); $i++) {
        $value = $value[$names[$i]] ?? null;
    }
    return  is_null($value) ? $default : $value;
}