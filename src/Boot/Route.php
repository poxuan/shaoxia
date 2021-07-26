<?php

namespace Shaoxia\Boot;

class Route
{
    protected static $routes = [];

    protected static $pattern = [];

    public static function get($name, $controller) {
        static::$routes['GET'][$name] = $controller;
    }

    public static function pattern($name, $pattern) {
        static::$pattern[$name] = $pattern;
    }

    public static function post($name, $controller) {
        static::$routes['POST'][$name] = $controller;
    }

    public static function put($name, $controller) {
        static::$routes['PUT'][$name] = $controller;
    }

    public static function resource($name, $controller) {
        static::$routes['GET'][$name] = $controller."@index";
        static::$routes['POST'][$name] = $controller."@store";
        static::$routes['GET'][$name.'/{id}'] = $controller."@show";
        static::$routes['PUT'][$name.'/{id}'] = $controller."@update";
        static::$routes['DELETE'][$name.'/{id}'] = $controller."@destory";
    }

    public static function match($route, $method = 'GET', &$match = null) {
       
        $method = strtoupper($method);
        if (!isset(static::$routes[$method])) {
            return null;
        }
        foreach(static::$routes[$method] as $name => $controller) {
            if (strpos($name, '{')) {
                $pattern = '/^'.str_replace('/', '\/', $name).'$/i';
                $pertten = preg_replace_callback("/{(.*)}/", function ($r) {
                    if (isset(static::$pattern[$r[1]])) {
                        return "(?<{$r[1]}>".static::$pattern[$r[1]].')';
                    }
                    return "(?<{$r[1]}>[a-z0-9]+)";
                    
                }, $pattern);
                if (preg_match($pertten, $route, $match)) {
                    return  explode("@", $controller);
                }
            } elseif ($name == $route) {
                return explode("@", $controller);
            }
        }
        return null;
    }
}