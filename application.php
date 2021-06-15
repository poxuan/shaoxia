<?php

use Shaoxia\Boot\Request;
use Shaoxia\Boot\Response;

class application
{
    private static $instance = null;
    private $clazz = null;
    private $func = null;

    // 请求处理类
    private $request = null;
    // 返回处理类
    private $response = null;
    // 异常处理类
    private $handler = null;

    // 绑定类
    private $binded = [];

    private function __construct($clazz = '', $func = '', $is_cli = false)
    {
        $this->clazz = $clazz;
        $this->func  = $func;
        if ($is_cli) {
            $this->request = new Shaoxia\Boot\CliRequest();
            $this->response = new Shaoxia\Boot\CliResponse();
        } else {
            $this->request = new Shaoxia\Boot\HttpRequest();
            $this->response = new Shaoxia\Boot\HttpResponse();
        }
        $this->ini();
    }

    public static function getInstance($clazz = '', $func = '', $is_cli = false)
    {
        if (!self::$instance) {
            self::$instance = new self($clazz, $func, $is_cli);
        }
        return self::$instance;
    }

    public function __clone() {

    }

    private function ini()
    {
        $this->alias();
        $this->ini_bind();
    }

    private function alias()
    {
        foreach (config('alias') as $alias => $clazz) {
            class_alias($clazz, $alias);
            class_uses($alias);
        }
    }

    public function bind($interface, $clazz)
    {
        $this->binded[$interface] = $clazz;
    }

    public function ini_bind()
    {
        $this->binded = [
            Request::class => $this->request,
            Response::class => $this->response,
        ];
    }

    public function exec()
    { 
        
        try {
            if (!method_exists($this->clazz, $this->func)) {
                throw new Exception("class mothod {$this->clazz}@{$this->func} not found");
            }
            $class = $this->ini_clazz($this->clazz);
            $params = $this->ini_param($class, $this->func);
            $result = call_user_func_array([$class, $this->func], $params);
        } catch (Throwable $exception) {
            $result = $this->handler ? $this->handler->render($this->request, $exception) : $exception->getMessage();
        }
        $this->response->resource($result)->output();
    }

    private function ini_clazz($clazz)
    {
        
        if ($c = $this->binded[$clazz] ?? null) {
            if (is_object($c)) {
                return $c;
            }
        }
        if (!class_exists($clazz)) {
            throw new Exception("class {$clazz} not found");
        }
        if (!method_exists($clazz, '__construct')) {
            return new $clazz();
        }
        $params = $this->ini_param($clazz, '__construct');
        return call_user_func_array([$clazz, '__construct'], $params);
    }

    private function ini_param($clazz, $func)
    {
        $method = new ReflectionMethod($clazz, $func);
        foreach ($method->getParameters() as $param) {
            
            $name    = $param->getName();
            $clazz2  = $param->getClass();
            if ($clazz2) {
                $params[] = $this->ini_clazz($clazz2->getName());
            } else {
                $default = $param->getDefaultValue() ?? null;
                $params[] = $this->request->get($name, $default);
            }
        }
        return $params;
    }

}
