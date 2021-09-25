<?php

namespace Shaoxia\Decode\Js;

class JsCommon {
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
    }

    /**
     * 获取数组结果
     */
    public function getKey($key) {
        $x3b0e20 = $key - $this->offset;
        $x535640 = $this->replaceArr[$x3b0e20];
        return $x535640;
    }

    /**
     * 数组重排序
     */
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

    /**
     * 处理
     */
    public function resolve() {
        // 16进制数赚10进制
        $r = preg_replace_callback("/([^a-z0-9_])0x([0-9a-f]*)/i", function ($item) {
            $d = hexdec(substr($item[0],1));
            return $item[1].$d;
        }, $this->constant);
        $p = 1;
        $params = [];
        $r = preg_replace("/\['([a-z][a-z0-9]+)'\]/i", ".$1", $r); // 数组格式转成.格式
        // 参数可读化
        $r = preg_replace_callback("/_0x([0-9a-f]+)/i", function ($item) use (&$params, &$r, &$p) {
            $key = $item[0];
            if (isset($params[$key])) {
                $res = $params[$key];
            } else {
                $pattern = "/".$key." = ([^;,'\"\?\(\[\]\)\s\{]+)/i";
                // var_dump($pattern);die;
                if (strpos($r, 'function '.$key)) { // 函数参数
                    $res = 'f' . $p++;
                } elseif (preg_match($pattern, $r, $match)) {
                    if ($match[1][0] == '[') { // 是数组
                        $res = 'a' . $p++;
                    } elseif ((strpos($match[1],'.')) > 0) { // 对象的某个值
                        $end = end(explode(".", $match[1]));
                        $res =  '_' . $end;
                        if (in_array($res, $params)) { // 已经出现过的, 在后面加数字区分
                            $i = 1;
                            do {
                                $res1 = $res . '_' . ($i++);
                            } while(in_array($res1, $params));
                            $res = $res1;
                        }
                    } elseif (substr($match[1],0,3) == '_0x') { // 赋值操作参数名一致化
                        $key2 = $match[1];
                        $res =  isset($params[$key2]) ? "_". $params[$key2] : ('p' . $p++);
                    } elseif ($match[1] == 'function' || strpos($r, 'function '.$key)) { // 有同名函数,函数参数
                        $res = 'f' . $p++;
                    } else {  // 其他赋值参数
                        $res = 'p' . $p++;
                    }
                } else { // 不定参数
                    $res = 'c' . $p++;
                }
                $params[$key] = $res;
            }
            return $res;
        }, $r );
        
        return $r;
    }
}


