<?php

class application
{
    private static $instance = null;
    private $clazz = null;
    private $func = null;

    private $request = null;
    private $response = null;
    private $handler = null;

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
        
    }

    private function alias()
    {
        foreach (config('alias') as $alias => $clazz) {
            class_alias($clazz, $alias);
            class_uses($alias);
        }
    }

    public function exec()
    { 
        try {
            $class = $this->ini_clazz($this->clazz);
            if (!method_exists($class, $this->func)) {
                throw new Exception("class mothod {$this->clazz}@{$this->func} not found");
            }
            $method = new ReflectionMethod($this->clazz, $this->func);
            foreach ($method->getParameters() as $param) {
                $name    = $param->getName();
                $default = $param->getDefaultValue();
                $clazz2  = $param->getClass();
                $type    = $param->getType();
                if ($clazz2) {
                    $params[] = $this->ini_clazz($clazz2);
                } else {
                    $params[] = $this->request->get($name, $default);
                }
            }
            $result = call_user_func_array([$this->clazz, $this->func], $params);
        } catch (Exception $exception) {
            $result = $this->handler ? $this->handler->render($this->request, $exception) : $exception->getMessage();
        }
        $this->response->resource($result)->output();
    }

    private function ini_clazz($clazz)
    {
        if (!class_exists($clazz)) {
            throw new Exception("class {$clazz} not found");
        }
        if (!method_exists($clazz, '__construct')) {
            return new $clazz();
        }
        $method = new ReflectionMethod($clazz, '__construct');

        $params = [];
        foreach ($method->getParameters() as $param) {
            $name = $param->getName();
            $default = $param->getDefaultValue();
            $clazz2 = $param->getClass();
            if ($clazz2) {
                $params[] = $this->ini_clazz($clazz2);
            } else {
                $params = $this->request->get($name, $default);
            }
        }
        return call_user_func_array([$clazz, '__construct'], $params);
    }

}
