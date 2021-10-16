<?php

namespace Shaoxia\Boot;

use Shaoxia\Exceptions\RouteNotMatchException;

class Route
{
    // 控制器名字空间
    protected static $ctr_ns = 'Shaoxia\Controller';
    
    // 当前组配置
    protected $current = [];
    protected $prefix = '';
    protected $middlewares = [];
    protected $pattern = [];

    // 所有路由
    protected static $allRoutes = [];
    // 路由对应中间件
    protected static $routeMiddlewares = [];
    // 路由对应模式匹配
    protected static $routePatterns = [];
    // 路由对应别名
    protected static $routeAlias = [];
    // 全局模式匹配
    protected static $basePattern = [];
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

    protected function clear() {
        $this->prefix = [];
        $this->middlewares = [];
        $this->current = [];
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
            case 'prefix':
                $val = trim($val,'/');
                $this->prefix = $this->prefix ? $this->prefix."/".$val : $val;
                return;
            case 'middleware':
                $val = is_array($val) ? $val : [$val];
                $this->middlewares = array_values(array_unique($this->middlewares + $val));
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
            $route = current($this->current);
            if ($route) {
                static::$routeAlias[$alias] = $route;
            }
        } elseif(is_array($alias)) {
            foreach ($alias as $func => $route) {
                if (isset($this->current[$func])) {
                    static::$routeAlias[$route] = $this->current[$func];
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
        foreach($this->current as $route) {
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

    // 通用设置
    protected function common($method, $route, $controller, $config) { // 这里的$config 不具有传递性，不要写入路由
        $prefix = '';
        if ($this->prefix) {
            $prefix .= $this->prefix.'/';
        } if (isset($config['prefix'])) {
            $prefix .= trim($config['prefix'],'/').'/';
        }
        $route = $prefix.$route;
        if ($this->middlewares) {
            static::$routeMiddlewares[$method][$route] = $this->middlewares;
        }
        
        if (isset($config['middleware'])) {
            static::$routeMiddlewares[$method][$route] += $config['middleware'];
        }
        if (isset($config['alias'])) {
            static::$routeAlias[$config['alias']] = $method.'#'.$route;
        }
        $pattern = array_merge($this->pattern, $config['pattern'] ?? []);
        if ($pattern) {
            static::$routePatterns[$method][$route] = $pattern;
        }
        static::$allRoutes[$method][$route] = $controller;
    }

    
    protected function _get($route, $controller, $config = []) {
        $this->common('GET',$route, $controller, $config);
        $this->current = ['GET#'.$route];
        return $this;
    }

    protected function _post($route, $controller, $config = []) {
        $this->common('POST',$route, $controller, $config);
        $this->current = ['POST#'.$route];
        return $this;
    }

    protected function _put($route, $controller, $config = []) {
        $this->common('PUT',$route, $controller, $config);
        $this->current = ['PUT#'.$route];
    }

    protected function _delete($route, $controller, $config = []) {
        $this->common('DELETE',$route, $controller, $config);
        $this->current = ['DELETE#'.$route];
        return $this;
    }

    protected function _resource($route, $controller, $config = []) {
        $this->_get($route, $controller."@index", $config);
        $this->_post($route, $controller."@store", $config);
        $this->_get($route.'/{id}', $controller."@show", $config);
        $this->_put($route.'/{id}', $controller."@update", $config);
        $this->_delete($route.'/{id}', $controller."@destory", $config);
        $this->current = [
            'index' => 'GET#'.$route,
            'store' => 'POST#'.$route,
            'show' => 'GET#'.$route.'/{id}',
            'update' => 'PUT#'.$route.'/{id}',
            'destory' => 'DELETE#'.$route.'/{id}',
        ];
        return $this;
    }

    /**
     * 获取匹配的路由
     */
    public static function match($route, $method = 'GET', &$bind = null, &$middleware = null) {
        $method = strtoupper($method);
        if (!isset(static::$allRoutes[$method])) {
            throw new RouteNotMatchException("未匹配到路由");
        }
        foreach(static::$allRoutes[$method] as $name => $path) {
            if (strpos($name, '{')) { // 含有参数项
                $raw = '/^'.str_replace('/', '\/', $name).'$/i';
                // 替换正则式
                $pattern = preg_replace_callback("/{(.*)}/", function ($r) use ($method, $name) {
                    $routePattern = static::$routePatterns[$method][$name][$r[1]] ?? null;
                    if ($routePattern) {
                        return "(?<{$r[1]}>".$routePattern.')';
                    }
                    $basePattern = static::$basePattern[$r[1]] ?? null;
                    if (isset($basePattern)) {
                        return "(?<{$r[1]}>".$basePattern.')';
                    }
                    return "(?<{$r[1]}>[^\/?#]+)";
                    
                }, $raw);
                if (preg_match($pattern, $route, $match)) {
                    unset($match[0]);
                    $bind = $match;
                    $middleware = static::$routeMiddlewares[$method][$name] ?? [];
                    list($controller, $func) =  explode("@", $path);
                    $controller = static::$ctr_ns.'\\'.$controller;
                    return [$controller,$func];
                }
            } elseif ($name == $route) {
                $middleware = static::$routeMiddlewares[$method][$name] ?? [];
                list($controller, $func) =  explode("@", $path);
                $controller = static::$ctr_ns.'\\'.$controller;
                return [$controller,$func];
            }
        }
        throw new RouteNotMatchException("未匹配到路由:".$route);
    }
}