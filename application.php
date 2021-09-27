<?php

use Shaoxia\Boot\Request;
use Shaoxia\Boot\Response;
use Shaoxia\Boot\Route;
use Shaoxia\Exceptions\CustomException;
use Shaoxia\Exceptions\MethodNotFoundException;
use Shaoxia\Exceptions\RouteNotMatchException;

class application
{
    private static $instance = null;
    
    private $is_cli = false;
    // 初始化配置数组，在 /config/app.php 中配置
    private $iniConfig = [];
    // 请求处理类
    private $request = null;
    // 返回处理类
    private $response = null;
    // 异常处理类
    private $handler = null;
    // 绑定关系
    private $binded = [];
    // 全局中间件
    private $middleware = [];
    // 路由结果
    private $clazz = null;    // 最终执行类
    private $func = null;     // 最终执行方法
    private $pathParams = []; // 路径中参数
    private $routeMiddleware = []; // 路由中间件

    private function __construct($is_cli)
    {
        $this->ini();
        if ($is_cli) {
            $this->is_cli = true;
            $this->request = new Shaoxia\Boot\CliRequest();
            $this->response = new Shaoxia\Boot\CliResponse();
        } else {
            $this->request = new Shaoxia\Boot\HttpRequest();
            $this->response = new Shaoxia\Boot\HttpResponse();
        }
        // 绑定请求处理类
        $this->binded[Request::class] = $this->request;
        $this->binded[Response::class] = $this->response;
    }

    // 单例
    public static function getInstance($is_cli = true)
    {
        if (!self::$instance) {
            self::$instance = new self($is_cli);
        }
        return self::$instance;
    }

    // 防止克隆
    public function __clone() {

    }

    // 初始化
    private function ini()
    {
        $this->iniConfig = config('', [], 'app');
        $this->ini_alias();
        $this->ini_bind();
        $this->ini_route();
        $this->ini_middleware();
    }

    private function ini_alias()
    {
        foreach ($this->iniConfig['alias'] as $alias => $clazz) {
            class_alias($clazz, $alias);
            class_uses($alias);
        }
    }

    private function ini_bind()
    {
        $this->binded = $this->iniConfig['bindings'] ?? [];
    }

    private function ini_middleware()
    {
        $this->middleware = $this->iniConfig['middleware'] ?? [];
    }

    private function ini_route() 
    {
        require_once CONFIG_PATH . '/route.php';
    }

    public function bind($interface, $clazz)
    {
        $this->binded[$interface] = $clazz;
    }

    public function alias($alias, $clazz)
    {
        class_alias($clazz, $alias);
        class_uses($alias);
    }

        /**
     * 解析控制器和方法名
     */
    protected function parse_path()
    {
        $pathParams = $routeMiddleware = [];
        $uri = $this->is_cli ? cli_uri() : $_SERVER['REQUEST_URI'];
        $method =  $this->is_cli ? 'GET' : $_SERVER['REQUEST_METHOD'];
        $path = explode('#', explode("?", ltrim($uri,'/'))[0])[0];
        $path = $path ?: '/';
        // 解析出对应类名、方法名、路由参数、中间件
        $cf = Route::match($path, $method, $pathParams, $routeMiddleware);
        if ($cf) {
            $this->clazz = $cf[0];
            $this->func  = $cf[1];
            if (!method_exists($this->clazz, $this->func)) {
                throw new MethodNotFoundException("class mothod {$this->clazz}@{$this->func} not found");
            }
            $this->pathParams  = $pathParams;
            $this->setRouteMiddleware($routeMiddleware);
            return true;
        }
        throw new RouteNotMatchException("路由{$path}无法识别!!!");
    }

    public function getResponse() {
        return $this->response;
    }

    public function getRequest() {
        return $this->request;
    }

    protected function setRouteMiddleware($arr) {
        $arr = is_array($arr) ? $arr : [$arr];
        foreach($arr as $key) {
            // 别名替换成类名
            $this->routeMiddleware[] = $this->iniConfig['route_middlewares'][$key];
        }
    }

    /**
     * 执行请求
     */
    public function exec()
    { 
        try {
            // 解析路径
            $this->parse_path();
            // 执行中间件
            $this->run_middleware();
            // 执行结果
            $result = $this->handle();
        } catch (Throwable $t) {
            $msg = $t->getFile().":".$t->getLine()."#".$t->getMessage();
            $result = $this->handler ? $this->handler->render($this->request, $t) : $msg;
        }
        if ($result instanceof Response) {
            $result->output();
        } else {
            $this->response->resource($result)->output();
        }
    }

    // 最终执行方法
    protected function handle() {
        $class = $this->ini_clazz($this->clazz);
        $params = $this->ini_param($class, $this->func, true);
        return call_user_func_array([$class, $this->func], $params);
    }


    // 依次执行中间件
    protected function run_middleware()
    {
        $stack = array_merge($this->middleware, $this->routeMiddleware);
        if ($stack) {
            $pipeline = array_reduce(
                array_reverse($stack),
                $this->carry()
            );
            $pipeline($this->request);
        }
    }
    
    protected function carry()
    {
        return function ($stack, $pipe) {
            return function ($passable) use ($stack, $pipe) {
                if (is_string($pipe) && class_exists($pipe)) {
                    $pipe = $this->ini_clazz($pipe);
                }
                $parameters = [$passable, $stack];
                return $pipe->handle(...$parameters);
            };
        };
    }

    private function ini_clazz($clazz)
    {
        if ($c = $this->binded[$clazz] ?? null) {
            if (is_object($c)) { // 如果已经实例化,直接返回
                return $c;
            } elseif (class_exists($c)) { // 如果是个类名,转换传入值
                $clazz = $c;
            }
        }
        if (!class_exists($clazz)) {
            throw new MethodNotFoundException("class {$clazz} not found");
        }
        if (!method_exists($clazz, '__construct')) {
            return new $clazz();
        }
        $params = $this->ini_param($clazz, '__construct');
        return call_user_func_array([$clazz, '__construct'], $params);
    }

    private function ini_param($clazz, $func, $is_path = false)
    {
        $method = new ReflectionMethod($clazz, $func);
        foreach ($method->getParameters() as $param) {
            $name    = $param->getName();
            $clazz2  = $param->getClass();
            if ($clazz2) {
                $params[] = $this->ini_clazz($clazz2->getName());
            } elseif ($param->isPassedByReference()) {
                throw new MethodNotFoundException("class {$clazz} methed {$func} params {$name} is reference");
            } elseif ($is_path && isset($this->pathParams[$name])) {
                $params[] = $this->pathParams[$name];
            } else {
                $has_default = true;
                try {
                    $default =  $param->getDefaultValue() ?? null;
                } catch(\Throwable $t) {
                    $has_default = false;
                    $default = null;
                }
                if (!$has_default && !$this->request->has($name)){
                    throw new CustomException("参数未定义");
                }
                $params[] = $this->request->get($name, $default);
            }
        }
        return $params;
    }

}
