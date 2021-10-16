<?php

namespace Shaoxia;

use Shaoxia\Boot\ExceptionHandler;
use Shaoxia\Boot\FinalHandle;
use Shaoxia\Boot\Request;
use Shaoxia\Boot\Response;
use Shaoxia\Boot\Route;
use Shaoxia\Exceptions\CustomException;
use Shaoxia\Exceptions\MethodNotFoundException;
use Shaoxia\Exceptions\RouteNotMatchException;

class Application
{
    // 实例 单例
    private static $instance = null;
    // 是否时命令行模式
    private $is_cli = false;
    // 初始化配置数组，在 /config/app.php 中配置
    private $iniConfig = [];
    // 请求处理类
    private $request = null;
    // 返回处理类
    private $response = null;
    // 别名关系
    private $alias = [];
    // 绑定关系
    private $binded = [];
    // 绑定实例
    private $instand = [];
    // 全局中间件
    private $middleware = [];
    // 路由结果
    private $clazz = null;         // 最终执行类
    private $func = null;          // 最终执行方法
    private $routeParams = [];     // 路径中参数
    private $routeMiddleware = []; // 路由中间件


    private function __construct($is_cli)
    {
        // 其他初始化
       
        $this->ini();
        
        // 绑定请求和返回处理类
        if ($is_cli) {
            $this->is_cli = true;
            $this->request = new \Shaoxia\Boot\CliRequest();
            $this->response = new \Shaoxia\Boot\CliResponse();
        } else {
            $this->request = new \Shaoxia\Boot\HttpRequest();
            $this->response = new \Shaoxia\Boot\HttpResponse();
        }
        
        // 绑定请求处理类
        $this->bind(Request::class, $this->request);
        $this->bind(Response::class, $this->response);
    }

    /**
     * 获取实例
     * 
     * @return self
     */
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

    /**
     * 初始化
     */
    private function ini()
    {
        try {
            
            $this->iniConfig = config('', [], 'app');
            $this->ini_alias();
            $this->ini_bind();
            $this->ini_route();
            $this->ini_middleware();
        } catch(\Throwable $t) {
            die($t);
        }
    }

    /**
     * 别名初始化
     */
    private function ini_alias()
    {
        $this->alias = $this->iniConfig['alias'];
        foreach ($this->iniConfig['alias'] as $alias => $clazz) {
            class_alias($clazz, $alias);
            class_uses($alias);
        }
    }

    /**
     * 绑定初始化
     */
    private function ini_bind()
    {
        foreach ($this->iniConfig['bindings'] as $abstract => $closure) {
            $this->bind($abstract, $closure);
        }
    }

    /**
     * 全局中间件
     */
    private function ini_middleware()
    {
        $this->middleware = $this->iniConfig['middleware'] ?? [];
    }

    /**
     * 路由初始化
     */
    private function ini_route() 
    {
        $routes = $this->iniConfig['router'] ?? ['route'];
        foreach($routes as $route) {
            require_once ROUTER_PATH . '/'.$route.'.php';
        }
    }

    /**
     * 绑定实例或类
     * 
     * @param string $abstract 虚类
     * @param string|object $closure 实例（类）
     */
    public function bind($abstract, $closure)
    {
        if (is_object($closure)) { // 本身就实例过的
            $this->binded[$abstract] = get_class($closure);
            $this->instand[$abstract] = $closure;
        } else { // 绑定关系
            $this->binded[$abstract] = $closure;
        }
    }

    /**
     * 设置别名
     * 
     * @param string $alias 别名
     * @param string $clazz 类名
     */
    public function alias($alias, $clazz)
    {
        class_alias($clazz, $alias);
        class_uses($alias);
        $this->alias[$alias] = $clazz;
    }

    /**
     * 获取绑定参数
     * 
     * @param string $name 虚类名
     * 
     * @return object|null 
     */
    public function __get($name)
    {
        // 如果有别名，先转为原名
        if (isset($this->alias[$name])) {
            $name = $this->alias[$name];
        }
        

        if ($this->instand[$name]) { // 已经实例化的，返回实例
            return $this->instand[$name];
        } elseif ($closure = $this->binded[$name]) { // 有绑定的，实例化后返回
            $instand = $this->ini_clazz($closure);
            $this->instand[$name] = $instand;
            return $instand;
        }
        return null;
    }

    /**
     * 解析路由获取控制器和方法名
     * 
     * @return bool
     * @throws CustomException
     */
    protected function parse_path()
    {
        
        $routeParams = $routeMiddleware = [];
        $uri = $this->is_cli ? cli_uri() : $_SERVER['REQUEST_URI'];
        $method =  $this->is_cli ? 'GET' : $_SERVER['REQUEST_METHOD'];
        $path = explode('#', explode("?", ltrim($uri,'/'))[0])[0];
        $path = $path ?: '/';
        // 解析出对应类名、方法名、路由参数、中间件
        $cf = Route::match($path, $method, $routeParams, $routeMiddleware);
        if ($cf) {
            $this->clazz = $cf[0];
            $this->func  = $cf[1];
            if (!method_exists($this->clazz, $this->func)) {
                throw new MethodNotFoundException("class mothod {$this->clazz}@{$this->func} not found");
            }
            $this->routeParams  = $routeParams;
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

    /**
     * 设置路由中间件
     * 
     * @param string|array $arr 中间件数组
     */
    protected function setRouteMiddleware($arr) {
        $arr = is_array($arr) ? $arr : [$arr];
        foreach($arr as $key) {
            // 别名替换成类名
            if (isset($this->iniConfig['route_middleware'][$key])) {
                $this->routeMiddleware[] = $this->iniConfig['route_middleware'][$key];
            } else {
                throw new CustomException('中间件 '.$key .' 不存在');
            }
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
            // 执行控制器方法
            $result = $this->handle();
            
        } catch (\Throwable $t) {
            $handler = ExceptionHandler::class;
            $render = $this->is_cli ? 'renderForCli' : 'render';
            $result = $this->$handler ? $this->$handler->$render($this->request, $t) : die($t);
        }
        if ($result instanceof Response) {
            $result->output();
        } else {
            $this->response->resource($result)->output();
        }
    }

    /**
     *  执行获取结果
     */
    protected function handle() {
        // 初始化控制器类，并设置在中间件末尾
        $class = $this->ini_clazz($this->clazz);
        $params = $this->ini_param($class, $this->func, true);
        $finalPoint = new FinalHandle($class, $this->func, $params);
        return $this->run_middleware($finalPoint);
    }

    /**
     * 依次执行中间件
     * 
     * @param mixed 尾节点
     */
    protected function run_middleware($finalPoint)
    {
        $stack = array_merge($this->middleware, $this->routeMiddleware, [$finalPoint]);
        $pipeline = array_reduce(
            array_reverse($stack),
            $this->carry()
        );
        return $pipeline($this->request);
    }
    
    /**
     * 构建中间件
     * 
     * @return callable
     */
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

    /**
     * 初始化一个类
     * 
     * @param string $class
     * @param bool $single 是否唯一
     * 
     * @return object
     * @throws CustomException
     */
    public function ini_clazz($clazz, $single = true)
    {
        if ($single && $ins = $this->instand[$clazz] ?? null) {
            return $ins;
        }
        $clazz2 = $clazz;
        if ($c = $this->binded[$clazz] ?? null) {
            if ($this->instand[$clazz] ?? null) { // 如果已经实例化,直接返回
                return $this->instand[$clazz];
            } elseif (class_exists($c)) { // 如果是个类名,改为被绑定类
                $clazz2 = $c;
            }
        }
        if (!method_exists($clazz2, '__construct')) { // 没有构造类直接生成
            return new $clazz2;
        }
        $method = new \ReflectionMethod($clazz2, '__construct');
        if (!$method->isPublic()) {
            throw New CustomException("类 {$clazz} 的 构造函数 是私有的");
        }
        // 先生成构造参数，再初始化
        $params = $this->ini_param($clazz2, '__construct');
        $obj = $params ? new $clazz2(...$params) : new $clazz();
        $this->instand[$clazz] = $obj;
        return $obj;
    }

    /**
     * 初始化类的参数
     * 
     * @param string $clazz 类名
     * @param string $func 方法名
     * @param bool $is_route 是否路由类
     * 
     * @return array
     * @throws CustomException
     */
    public function ini_param($clazz, $func, $is_route = false)
    {
        $method = new \ReflectionMethod($clazz, $func);
        foreach ($method->getParameters() as $param) {
            $name    = $param->getName();
            $clazz2  = $param->getClass();
            if ($clazz2) { // 当前参数有定义类
                $params[] = $this->ini_clazz($clazz2->getName());
            } elseif ($param->isPassedByReference() && !$param->isDefaultValueAvailable()) { // 当前参数是引用？
                throw new CustomException("类 {$clazz} 的方法 {$func} 中参数 {$name} 是引用，无法实例化");
            } elseif ($is_route && isset($this->routeParams[$name])) { // 当前类是控制器类，且有此路由参数
                $params[] = $this->routeParams[$name];
            } elseif($this->request->has($name)) { // 请求参数中有此值
                $params[] = $this->request->get($name);
            } elseif ($param->isDefaultValueAvailable()) { // 参数有默认值
                $params[] = $param->getDefaultValue();
            } else {
                throw New CustomException("类 {$clazz} 的方法 {$func} 中参数 {$name} 不能初始化");
            }
        }
        return $params;
    }

    /**
     * 初始化类的参数
     * 
     * @param string $func 方法名
     * @param bool $is_route 是否路由类
     * 
     * @return array
     * @throws CustomException
     */
    public function ini_func_param($func)
    {
        $method = new \ReflectionFunction($func);
        foreach ($method->getParameters() as $param) {
            $name    = $param->getName();
            $clazz2  = $param->getClass();
            if ($clazz2) { // 当前参数有定义类
                $params[] = $this->ini_clazz($clazz2->getName());
            } elseif ($param->isPassedByReference() && !$param->isDefaultValueAvailable()) { // 当前参数是引用？
                throw new CustomException("方法 {$func} 中参数 {$name} 是引用，无法实例化");
            } elseif($this->request->has($name)) { // 请求参数中有此值
                $params[] = $this->request->get($name);
            } elseif ($param->isDefaultValueAvailable()) { // 参数有默认值
                $params[] = $param->getDefaultValue();
            } else {
                throw New CustomException("方法 {$func} 中参数 {$name} 不能初始化");
            }
        }
        return $params;
    }
}