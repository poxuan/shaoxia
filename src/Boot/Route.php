<?php

namespace Shaoxia\Boot;

use Shaoxia\Exceptions\RouteNotMatchException;

class Route
{
    protected static $config = [];

    protected static $routes = [];

    protected static $middleware = [];

    protected static $pattern = [];

    public static function group($config, $callable) {
        static::$config = $config;
        $callable(new self);
        static::$config = [];
    }

    public static function get($name, $controller) {
        if (isset(static::$config['prefix'])) {
            $name = static::$config['prefix']. $name;
        }
        if (isset(static::$config['middleware'])) {
            $name = static::$config['prefix']. $name;
            static::$middleware['GET'][$name] = $controller;
        }
        static::$routes['GET'][$name] = $controller;
    }

    public static function pattern($name, $pattern) {
        static::$pattern[$name] = $pattern;
    }

    public static function post($name, $controller) {
        if (isset(static::$config['prefix'])) {
            $name = static::$config['prefix']. $name;
        }
        if (isset(static::$config['middleware'])) {
            $name = static::$config['prefix']. $name;
            static::$middleware['POST'][$name] = $controller;
        }
        static::$routes['POST'][$name] = $controller;
    }

    public static function put($name, $controller) {
        if (isset(static::$config['prefix'])) {
            $name = static::$config['prefix']. $name;
        }
        if (isset(static::$config['middleware'])) {
            $name = static::$config['prefix']. $name;
            static::$middleware['PUT'][$name] = $controller;
        }
        static::$routes['PUT'][$name] = $controller;
    }

    public static function delete($name, $controller) {
        if (isset(static::$config['prefix'])) {
            $name = static::$config['prefix']. $name;
        }
        if (isset(static::$config['middleware'])) {
            $name = static::$config['prefix']. $name;
            static::$middleware['DELETE'][$name] = $controller;
        }
        static::$routes['DELETE'][$name] = $controller;
    }

    public static function resource($name, $controller) {
        static::get($name, $controller."@index");
        static::post($name, $controller."@store");
        static::get($name.'/{id}', $controller."@show");
        static::put($name.'/{id}', $controller."@update");
        static::delete($name.'/{id}', $controller."@destory");
    }

    public static function match($route, $method = 'GET', &$bind = null, &$middleware = null) {
        $method = strtoupper($method);
        if (!isset(static::$routes[$method])) {
            throw new RouteNotMatchException("未匹配到路由1");
        }
        foreach(static::$routes[$method] as $name => $controller) {
            if (strpos($name, '{')) { // 含有参数项
                $pattern = '/^'.str_replace('/', '\/', $name).'$/i';
                $pertten = preg_replace_callback("/{(.*)}/", function ($r) {
                    if (isset(static::$pattern[$r[1]])) {
                        return "(?<{$r[1]}>".static::$pattern[$r[1]].')';
                    }
                    return "(?<{$r[1]}>[a-z0-9]+)";
                    
                }, $pattern);
                if (preg_match($pertten, $route, $match)) {
                    unset($match[0]);
                    $bind = $match;
                    $middleware = static::$middleware[$method][$name] ?? [];
                    return  explode("@", $controller);
                }
            } elseif ($name == $route) {
                return explode("@", $controller);
            }
        }
        throw new RouteNotMatchException("未匹配到路由2:".$route);
    }
}