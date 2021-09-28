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
        $this->params = $params;
    }

    public function handle($request, $next) {
        // echo "test e1\n";
        $next ? $next($request) : null;
        // echo "test e2\n";
        $result = call_user_func_array([$this->clazz, $this->func], $this->params);
        return $result;
    }
}