<?php

namespace App\Decode\Js;

use App\Common\Chrome;

/**
 * 本方法处理
 */
class Jsv5 {
    // 内容
    public $content = '';
    // 替换数组
    public $replaceArr = [];
    // 偏移量
    public $offset = 0;
    // 计算公式
    public $formule = '';
    // 目标值
    public $target = 0;

    function __construct($content) {
        if (empty($content)) {
            die("请先加载文件");
        }
        $this->constant = $content;
        preg_match("/\[.+\];/", $content ,$match);
        $arr = eval('return '.$match[0]);
        $this->replaceArr = $arr;
    }

    /**
     * 预处理
     */
    public function preproccess($midFile, $url, $ul_id = "aaa", $func_name = 'f6') {
        $r = $this->resolve(false);
        preg_match_all("/{$func_name}\(\"([0-9]+)\", \"(.{4})\"\)/", $r, $matches);
        $chars = [];
        $count = count($matches[0]);
        for($i = 0; $i < $count; $i++) {
            $chars[intval($matches[1][$i])] = "[{$matches[1][$i]}, '{$matches[2][$i]}']";
        }
        ksort($chars);
        // 结尾添加一段js
        $chars = implode(",", $chars);
        $js  = "var preArr = [".$chars."];";
        $js .= 'preArr.forEach(function(el) {$("#'.$ul_id.'").append("<li>"+el[0] + " %%% "+ '.$func_name.'(el[0], el[1]) + "</li>")});';
        $r  .= $js;
        // 保存为中间文件
        file_put_contents($midFile, $r);
        // 打开文件,获取参数对应关系
        $chrome = new Chrome();
        $data = $chrome->url($url)->findById($ul_id)->getText();
        $lines = explode("\n", $data);
        foreach($lines as $line) {
            list($i, $val) = explode(" %%% ", $line);
            $this->replaceArr[$i] = $val;
        }
    }

    // 获取替换值
    public function getKey($i) {
        $x535640 = $this->replaceArr[$i] ?? null;
        return $x535640;
    }

    /**
     * 处理获取结果
     */
    public function resolve($replace = true) {
        
        // 替换16进制数
        $r = preg_replace_callback("/([^a-z0-9_])0x([0-9a-f]*)/i", function ($item) {
            $d = hexdec(substr($item[0],1));
            return $item[1].$d;
        }, $this->constant);

        if ($replace) { 
            // 替换加密值
            $r = preg_replace_callback("/_[a-z0-9]*\(\"([0-9]+)\", \"(.{4})\"\)/i", function ($item) {
                $data = $this->getKey($item[1]);
                if (strpos($data, "'") !== false) {
                    return $data ? "`$data`" : $item[0];
                } else {
                    return $data ? "'$data'" : $item[0];
                }
            }, $r);
            // []格式替换为.格式
            $r = preg_replace("/\[\"([a-z][a-z0-9]*)\"\]/i", ".$1", $r);
            $r = preg_replace("/\['([a-z][a-z0-9]*)'\]/i", ".$1", $r);
            // 混淆对象替换
            preg_match_all('/var ([_a-z0-9]+) = \{/i', $r, $matches);
            foreach($matches[0] as $key => $match) {
                $firstKey = $matches[1][$key];
                $start = strpos($r, $match);
                $end   = strpos($r, "};", $start);
                $substr = substr($r, $start, $end - $start);
                $lines = array_map('trim', explode("\n", $substr));
                foreach($lines as $i => $line) {
                    if (preg_match("/^([_a-z0-9]+): ('[^']+'),$/i", $line, $mat)) { // 字符串替换
                        $secondKey = $mat[1];
                        $val = $mat[2];
                        $r = str_replace("{$firstKey}.{$secondKey}", $val, $r);
                    } elseif (preg_match("/^([_a-z0-9]+): (\"[^\"]+\"),$/i", $line, $mat)) { // 字符串替换
                        $secondKey = $mat[1];
                        $val = $mat[2];
                        $r = str_replace("{$firstKey}.{$secondKey}", $val, $r);
                    } elseif (preg_match("/^([_a-z0-9]+): function ([_a-z0-9]+)\(([^\)]*)\) \{$/i", $line, $mat)) { // 函数替换
                        $secondKey = $mat[1];
                        $params = explode("," ,$mat[2]);
                        if (strpos($lines[$i+1], '(')) {
                            $pattern = "/{$firstKey}\.{$secondKey}\(([_a-zA-Z0-9]+),/";
                            $r = preg_replace($pattern, '$1(', $r);
                        } elseif (preg_match("/[-+*\/\|\&\!=]+/", $lines[$i+1], $m)) {
                            $pattern = "/{$firstKey}\.{$secondKey}\(([_a-zA-Z0-9]+?),/";
                            $r = preg_replace($pattern, '($1 '.$m[0], $r);
                            $pattern2 = "/{$firstKey}\.{$secondKey}\((\"[^\"]+\"?),/";
                            $r = preg_replace($pattern2, '($1 '.$m[0], $r);
                            echo $pattern ." ". $m[0]. "\n";
                        }
                    }
                }
            }
        }

        $p = 1;
        $params = [];
        // 参数可读化
        $r = preg_replace_callback("/_0x([0-9a-f]+)/i", function ($item) use (&$params, &$r, &$p) {
            $key = $item[0];
            if (isset($params[$key])) {
                $res = $params[$key];
            } else {
                $pattern = "/".$key." = ([^;,\?\(\]\)\s\{]+)/i";
                if (strpos($r, 'function '.$key)) {
                    $res = 'f' . $p++;
                } elseif (preg_match($pattern, $r, $match)) {
                    
                    if ($match[1][0] == '[') { // 数组
                        $res = 'a' . $p++;
                    } elseif (($px = strpos($match[1],'[')) > 0) { // 多维数据 
                        $k = str_replace(['"',"'",], '', substr($match[1], $px + 1));
                        if (preg_match('/^[a-z0-9]+$/i', $k)) {
                            $res =  '_' . $k;
                        } else {
                            $res = 'p' . $p++;
                        }
                        if (in_array($res, $params)) {
                            $i = 1;
                            do {
                                $res1 = $res . '_' . ($i++);
                            } while(in_array($res1, $params));
                            $res = $res1;
                        }
                    } elseif (substr($match[1],0,3) == '_0x') { // 等价参数
                        $key2 = $match[1];
                        $res =  isset($params[$key2]) ? "_". $params[$key2] : ('p' . $p++);
                    } elseif ($match[1] == 'function' || strpos($r, 'function '.$key)) { // 方法参数
                        $res = 'f' . $p++;
                    } else { // 普通参数
                        $res = 'p' . $p++;
                    }
                } else { // 其他参数
                    $res = 'c' . $p++;
                }
                $params[$key] = $res;
            }
            return $res;
        }, $r );
        return $r;
    }
}


