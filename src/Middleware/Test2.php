<?php 

namespace Shaoxia\Middleware;

class Test2 
{
    function handle($request, $next) {
        echo "test2";
        return $next ? $next($request) : null;
    }
}