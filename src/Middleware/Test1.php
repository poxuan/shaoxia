<?php 

namespace Shaoxia\Middleware;

class Test1 
{
    function handle($request, $next) {
        // echo "test1";
        return $next ? $next($request) : null;
    }
}