<?php

namespace Shaoxia\Boot;

use \Shaoxia\Contracts\Response;
class CliResponse implements Response
{
    protected $resource = null;

    public function resource($resource, $type = NULL) 
    {
        $this->resource = $resource;
        return $this;
    }
    
    public function output() 
    {
        $resource = $this->preHandle($this->resource);
        if (is_string($resource)) {
            echo $resource;
        } else {
            var_dump($resource);
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

}