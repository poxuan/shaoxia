<?php 

namespace Shaoxia\Middleware;

class Test2 
{
    function handle($request, $next) {
        $result = $next ? $next($request) : null;
        // echo "test2";
        return $result;
    }
}