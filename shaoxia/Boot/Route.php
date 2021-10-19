<?php

namespace Shaoxia\Boot;

use Shaoxia\Exceptions\CustomException;
use Shaoxia\Exceptions\RouteNotMatchException;

class Route
{   
    const DEFAULT_NAMESPACE = 'App\Controller';
    // 当前组配置
    protected $currentRoute = []; // 当前路由组,匹配后置项
    protected $namespace = '';    // 
    protected $prefix = '';
    protected $middleware = [];
    protected $pattern = [];

    // 所有路由
    protected static $allRoutes = [];
    // 路由的完整正则数组
    protected static $urlPatterns = null;
    // 路由对应中间件
    protected static $routeMiddlewares = [];
    // 路由对应模式匹配
    protected static $routePatterns = [];
    // 路由对应别名
    protected static $routeAlias = [];
    // 全局模式匹配
    protected static $basePattern = [];
    // 当前全局组
    protected static $baseGroup = '';
    protected static $groupConfig = [];
    // 实例
    protected static $instance = null;

    public static function getInstance() {
        if (!static::$instance) {
            static::$instance = new self();
        }
        return static::$instance;
    }

    public static function basePattern($name, $pattern) {
        static::$basePattern[$name] = $pattern;
    }

    public static function baseGroup($name, $config) {
        static::$baseGroup = $name;
        static::$groupConfig[$name] = $config;
    }

    public static function clearGroup() {
        static::$baseGroup = '';
    }

    protected function clear() {
        $this->prefix = [];
        $this->middleware = [];
        $this->currentRoute = [];
        $this->pattern = [];
    }

    /**
     * 配置项处理
     */
    protected function config($name, $val = null) {
        if (is_array($name)) {
            foreach($name as $k => $v) {
                $this->config($k, $v);
            }
            return;
        }
        if (empty($val)) {
            return ;
        }
        switch(strtolower($name)) {
            case 'namespace':
                $val = trim($val,'\\');
                $this->namespace = $this->namespace ? $this->namespace."\\".$val : $val;
                return;
            case 'prefix':
                $val = trim($val,'/');
                $this->prefix = $this->prefix ? $this->prefix."/".$val : $val;
                return;
            case 'middleware':
                $val = is_array($val) ? $val : [$val];
                $this->middleware = array_values(array_unique($this->middleware + $val));
                return;
            case 'pattern':
                $this->pattern = array_merge($this->pattern, $val);
                return;
        }
    }

    /**
     * 静态调用会清空当前实例，后续操作基于当前实例状态
     */
    public static function __callStatic($name, $arguments)
    {
        $instance = static::getInstance();
        $instance->clear();
        $name = '_'.$name;
        if (method_exists($instance, $name)) {
            return $instance->$name(...$arguments);
        }
        throw new \Exception($name . '方法不存在');
    }

    /**
     * 非静态调用，基于当前实例处理
     */
    public function __call($name, $arguments)
    {
        $name = '_'.$name;
        if (method_exists($this, $name)) {
            return $this->$name(...$arguments);
        }
        throw new \Exception($name . '方法不存在');
    }

    protected function _group($config, $callable = null) {
        if (is_array($config)) {
            $this->config($config);
            is_callable($callable) && $callable($this);
            return $this;
        } elseif (is_callable($config)) {
            $config($this);
            return $this;
        }
    }

    protected function _prefix($prefix, $callable = null) {
        $this->config('prefix', $prefix);
        is_callable($callable) && $callable($this);
        return $this;
    }

    // 后置操作，设置别名
    protected function _alias($alias) {
        if (is_string($alias)) {
            $route = current($this->currentRoute);
            if ($route) {
                static::$routeAlias[$alias] = $route;
            }
        } elseif(is_array($alias)) {
            foreach ($alias as $func => $name) {
                if (isset($this->currentRoute[$func])) {
                    static::$routeAlias[$name] = $this->currentRoute[$func];
                }
            }
        }
        return $this;
    }

    // 后置操作，设置模式匹配
    protected function _pattern($key, $val = null) {
        $routePatterns = [];
        if (is_string($key)) {
            $routePatterns[$key] = $val;
        } elseif(is_array($key)) {
            $routePatterns = $key;
        }
        foreach($this->currentRoute as $route) {
            list($method, $path) = explode('#',$route ,2);
            $old = static::$routePatterns[$method][$path] ?? [];
            static::$routePatterns[$method][$path] = array_merge($old, $routePatterns);
        }
        return $this;
    }

    // 前值操作，设置中间件
    protected function _middleware($middleware, $callable = null) {
        $this->config('middleware', $middleware);
        is_callable($callable) && $callable($this);
        return $this;
    }

    // 最终的配置：string型
    protected function finalConfigStrs($config) {
        $baseGroup = static::$baseGroup;
        $groupConfig = static::$groupConfig[$baseGroup] ?? [];
        $namespace = '';
        if ($groupConfig['namespace'] ?? static::DEFAULT_NAMESPACE) {
            $namespace .= $groupConfig['namespace'].'\\';
        }
        if ($this->namespace) {
            $namespace .= $this->namespace.'\\';
        }
        if ($config['namespace'] ?? '') {
            $namespace .= trim($config['namespace'],'\\').'\\';
        }
        $prefix = '';
        if ($groupConfig['prefix'] ?? '') {
            $prefix .= $groupConfig['prefix'].'/';
        }
        if ($this->prefix) {
            $prefix .= $this->prefix.'/';
        }
        if ($config['prefix'] ?? '') {
            $prefix .= trim($config['prefix'],'/').'/';
        }
        return [$namespace, $prefix];
    }

    // 最终的配置：Array型
    protected function finalConfigArrs($config) {
        $baseGroup = static::$baseGroup;
        $groupConfig = static::$groupConfig[$baseGroup] ?? [];
        $middleware = [];
        if ($groupConfig['middleware'] ?? []) {
            $middleware = array_merge($middleware, $groupConfig['middleware']);
        }
        if ($this->middleware) {
            $middleware = array_merge($middleware, $this->middleware);
        }
        if ($config['middleware'] ?? []) {
            $middleware = array_merge($middleware, $config['middleware']);
        }
        $pattern = [];
        if ($groupConfig['pattern'] ?? []) {
            $pattern = array_merge($pattern, $groupConfig['pattern']);
        }
        if ($this->pattern) {
            $pattern = array_merge($pattern, $this->pattern);
        }
        if ($config['pattern'] ?? []) {
            $pattern = array_merge($pattern, $config['pattern']);
        }
        return [$middleware, $pattern];
    }

    // 通用设置
    protected function common($method, $route, $controller, $config) { // 这里的$config 不具有传递性，不要写入路由
        $prefix = $namespace = '';
        list($namespace, $prefix) = $this->finalConfigStrs($config);
        list($middleware, $pattern) = $this->finalConfigArrs($config);
        $route = $prefix.$route; // 最终路由要拼上前缀
        static::$routeMiddlewares[$method][$route] = $middleware;
        static::$routePatterns[$method][$route] = $pattern;
        static::$allRoutes[$method][$route] = $namespace . $controller;
    }

    
    protected function _get($route, $controller, $config = []) {
        $this->common('GET',$route, $controller, $config);
        $this->currentRoute = ['GET#'.$route];
        return $this;
    }

    protected function _post($route, $controller, $config = []) {
        $this->common('POST',$route, $controller, $config);
        $this->currentRoute = ['POST#'.$route];
        return $this;
    }

    protected function _put($route, $controller, $config = []) {
        $this->common('PUT',$route, $controller, $config);
        $this->currentRoute = ['PUT#'.$route];
    }

    protected function _delete($route, $controller, $config = []) {
        $this->common('DELETE',$route, $controller, $config);
        $this->currentRoute = ['DELETE#'.$route];
        return $this;
    }

    protected function _resource($route, $controller, $config = []) {
        $rs = $config['only'] ?? ['index','store','show','update','destory'];
        $rs = array_diff($rs, $config['except'] ?? []);
        in_array('index', $rs) && $this->_get($route, $controller."@index", $config);
        in_array('store', $rs) && $this->_post($route, $controller."@store", $config);
        in_array('show', $rs) && $this->_get($route.'/{id}', $controller."@show", $config);
        in_array('update', $rs) && $this->_put($route.'/{id}', $controller."@update", $config);
        in_array('destory', $rs) && $this->_delete($route.'/{id}', $controller."@destory", $config);
        $routes = [
            'index' => 'GET#'.$route,
            'store' => 'POST#'.$route,
            'show' => 'GET#'.$route.'/{id}',
            'update' => 'PUT#'.$route.'/{id}',
            'destory' => 'DELETE#'.$route.'/{id}',
        ];
        foreach($routes as $k => $v) {
            if (!in_array($k, $rs)) {
                unset($routes[$k]);
            }
        }
        $this->currentRoute = $routes;
        return $this;
    }

    /**
     * 将所有路由转成正则
     */
    protected static function urlPatterns() {
        if (is_null(self::$urlPatterns)) {
            self::$urlPatterns = [];
            foreach(static::$allRoutes as $method => $routes) {
                foreach($routes as $route => $ctrl) {
                    $pattern = static::makePattern($route, $method);
                    self::$urlPatterns[$pattern][$method] = $route;
                }
            }
        }
        return self::$urlPatterns;
    }

    /**
     * 按规则生成路由
     */
    protected static function makePattern($route, $method) {

        $raw = '/^'.str_replace(['/', '-', '.'],['\/','\-','\.'], $route).'$/i';
        // 替换正则式
        $pattern = preg_replace_callback("/{(.*)}/", function ($r) use ($method, $route) {
            $routePattern = static::$routePatterns[$method][$route][$r[1]] ?? null;
            if ($routePattern) {
                return "(?<{$r[1]}>".$routePattern.')';
            }
            $basePattern = static::$basePattern[$r[1]] ?? null;
            if (isset($basePattern)) {
                return "(?<{$r[1]}>".$basePattern.')';
            }
            return "(?<{$r[1]}>[^\/?#]+)"; // 匹配除了 /?# 之外的字符
        }, $raw);
        return $pattern;
    }

    /**
     * 通过别名生成uri
     */
    public static function makeUri($name, $attributies = []) {
        if (isset(static::$routeAlias[$name])) { // 如果找到了别名
            $raw = static::$routeAlias[$name];
            list($method, $route) = explode("#", $raw);
            $route = preg_replace_callback("/{(.*)}/", function ($r) use (& $attributies, $name) {
                if ($attributies[$r[1]] ?? '') {
                    $rep = $attributies[$r[1]];
                    unset($attributies[$r[1]]);
                    return $rep;
                } else {
                    throw new CustomException("路由 {$name} 需指定参数 {$r[1]}");
                }
            }, $route);
            return $route.($attributies ? "?" . http_build_query($attributies) : "");
        }
        // 否则直接处理
        return $name.($attributies ? "?" . http_build_query($attributies) : "");
    }

    /**
     * 获取匹配的路由
     */
    public static function match($path, $method = 'GET', &$bind = null, &$middleware = null) {
        $method = strtoupper($method);
        $patterns = static::urlPatterns();
        foreach($patterns as $pattern => $routes) {
            if (preg_match($pattern, $path, $match)) {
                $route = $routes[$method] ?? "";
                if ($route) {
                    unset($match[0]);
                    $bind = $match;
                    $middleware = static::$routeMiddlewares[$method][$route] ?? [];
                    list($controller, $func) =  explode("@", static::$allRoutes[$method][$route]);
                    $controller = $controller;
                    return [$controller,$func];
                } else {
                    throw new RouteNotMatchException("不支持的请求方式:".$route);
                }
            }
        }
        throw new RouteNotMatchException("未匹配到路由:".$path);
    }
}