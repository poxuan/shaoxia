<?php

namespace Shaoxia\Decode\Js;

class Js0x {
    public $content = '';
    public $replaceArr = [];
    public $offset = 0;
    public $formule = '';
    public $target = 0;

    function __construct($content) {
        if (empty($content)) {
            die("请先加载文件");
        }
        $this->constant = $content;
        preg_match("/\[.+\];/", $content ,$match);
        $arr = eval('return '.$match[0]);
        $lines = explode(PHP_EOL, $content);
        $formule = "";
        $offset = $target = 0;
        unset($lines[0]); //首行丢弃
        foreach($lines as $line) {
            if (empty($offset) && strpos($line, '-')) { // 默认第一个减号后是目标值
                $offset = hexdec(trim(explode("-", $line)[1]));
            }
            if (empty($formule) && strpos($line, 'parseInt')) { // 默认第一处此方法是替换方法
                $formule = trim($line);
            }
            if (empty($target) && strpos($line, '));')) { // 默认第一处 )); 的值是目标值
                $target = hexdec(trim(explode(",", $line)[1]));
            }
            if ($formule && $offset && $target) {
                break;
            }
        }
        
        if(empty($arr) || empty($formule)) {
            die("解析失败");
        }
        
        $formule = str_replace("parseInt",'intval', $formule);
        $formule = preg_replace("/_0x[0-9a-z]+/i",'$this->getKey', $formule);
        $formule = explode("=", $formule);
        $formule = 'return '. end($formule).";"; // 故意多加一个封号,没影响

        $this->replaceArr = $arr;
        $this->offset = $offset;
        $this->formule = $formule;
        $this->target = $target;
        $this->resort();
    }

    public function getKey($key) {
        $x3b0e20 = $key - $this->offset;
        $x535640 = $this->replaceArr[$x3b0e20];
        return $x535640;
    }

    public function resort() {
        while (true) {
            try {
                $x1a4136 = eval($this->formule);
                if ($x1a4136 === $this->target)
                    break;
                else {
                    $v= array_shift($this->replaceArr);
                    array_push($this->replaceArr, $v);
                }
            } catch (\Exception $e) {
                $v= array_shift($this->replaceArr);
                array_push($this->replaceArr, $v);
            }
        }
    }

    public function resolve() {
        $r = preg_replace_callback("/([^a-z0-9_])0x([0-9a-f]*)/i", function ($item) {
            $d = hexdec(substr($item[0],1));
            return $item[1].$d;
        }, $this->constant);
        
        
        $r = preg_replace_callback("/_[a-z0-9]*\(([0-9]+)\)/i", function ($item) {
            $data = $this->getKey($item[1]);
            return $data ? "'$data'" : $item[0];
        }, $r );
        
        $p = 101;
        
        $params = [];
        $r = preg_replace_callback("/_0x([0-9a-f]+)/i", function ($item) use (&$params, &$r, &$p) {
            $key = $item[0];
            if (isset($params[$key])) {
                $res = $params[$key];
            } else {
                $pattern = "/".$key." = ([^;,\?\(\]\)\s\{]+)/i";
                // var_dump($pattern);die;
                if (strpos($r, 'function '.$key)) {
                    $res = 'f_' . $p++;
                } elseif (preg_match($pattern, $r, $match)) {
                    
                    if ($match[1][0] == '[') {
                        $res = 'arr_' . $p++;
                    } elseif (($px = strpos($match[1],'[')) > 0) {
                        $res =  '_' . str_replace(['"',"'",], '', substr($match[1], $px + 1));
                    } elseif (substr($match[1],0,3) == '_0x') {
                        $key2 = $match[1];
                        $res =  $params[$key2] ?? ('p_' . $p++);
                    } elseif ($match[1] == 'function' || strpos($r, 'function '.$key)) {
                        $res = 'f_' . $p++;
                    }else {
                        $res = 'p_' . $p++;
                    }
                } else {
                    $res = 'c_' . $p++;
                }
                $params[$key] = $res;
                // preg_match($pattern , $);
            }
            return $res;
        }, $r );
        
        foreach($params as $key) {
            $r = str_replace(["$key = $key;","$key = $key\r\n"],'_ignore', $r);
            $r = str_replace("$key = $key,",'_ignore,', $r);
        }
    }
}


