<?php

namespace Shaoxia\Boot;

use \Shaoxia\Contracts\Response;

class HttpResponse implements Response
{
    protected $resource = null;
    protected $outType = null;

    public function resource($resource = null, $type = null) 
    {
        $this->resource = $resource;
        $this->outType = $type;
        return $this;
    }

    public function withHeader($key, $val="") 
    {
        $header = $key;
        if ($val) {
            $header .= ': '.$val;
        }
        header($header);
        return $this;
    }
    
    public function output() 
    {
        try {
            $resource = $this->preHandle($this->resource);
            $type = $this->outType();
            switch (strtolower($type)) {
                case 'xml':
                case 'application/xml':
                    $this->withHeader('Content-Type: application/xml');
                    echo xml_encode($resource);
                    break;
                case 'json':
                case 'application/json':
                    $this->withHeader('Content-Type: application/json');
                    echo json_encode($resource);
                    break;
                case 'jsonp': // 手动设置吧
                    $func = $_GET['callback'];
                    echo jsonp_encode($resource, $func);
                    break;
                default:
                    if(is_array($resource) || is_object($resource)) {
                        dump($resource);
                    } else {
                        echo $resource ?: "";
                    }
            }
        }catch(\Throwable $t) {
            die($t);
        }
    }

    /**
     * 预处理返回值
     */
    protected function preHandle($resource) {
        if(is_object($resource)) { // 对象的话 只认这几个处理方法
            $clazz = get_class($resource);
            if (method_exists($clazz, 'toArray')){
                $resource = $resource->toArray();
            } elseif(method_exists($clazz, 'toJson')) {
                $resource = $resource->toJson();
            }  elseif(method_exists($clazz, '__toString')) {
                $resource = (string) $resource;
            } else { // 当数组处理
                $resource = (array) $resource;
            }
        } elseif (is_callable($resource)) { // 返回是一个闭包
            $func = $resource;
            $params = app()->ini_func_param($func);
            $resource = call_user_func_array($func, $params);
        }
        if (is_array($resource)) {
            foreach($resource as $key => $item) {
                $resource[$key] = $this->preHandle($item);
            }
        }
        return $resource;
    }

    /**
     * 输出类型
     */
    protected function outType($type = null) {
        if ($type) {
            $this->outType = $type;
            return $type;
        } else {
            $type = $this->outType;
            if (!$type) {
                $accept = request()->header('accept');
                
                if ($accept && $accept != '*') {
                    $as = explode(",", $accept);
                    foreach ($as as $a) {
                        $a = trim($a);
                        if ($a && strpos($a, '*') === false) {
                            $type = $a;
                            break;
                        }
                    }
                }
                $this->outType = $type;
            }
            return $type;
        }
    }
}