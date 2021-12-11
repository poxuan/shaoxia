<?php

namespace Shaoxia\Boot;

class FinalHandle
{
    private $clazz = null;
    private $func = '';
    private $params = [];

    public function __construct($clazz, $func, $params = [])
    {
        $this->clazz = $clazz;
        $this->func = $func;
        $this->params = $params ?? [];
    }

    public function handle($request, $next) {
        // 如果有中间件就调用
        $next ? $next($request) : null;
        // 执行控制器方法
        $result = call_user_func_array([$this->clazz, $this->func], $this->params);
        return $result;
    }
}