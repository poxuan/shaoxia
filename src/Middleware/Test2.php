<?php 

namespace Shaoxia\Middleware;

class Test2 
{
    function handle($request, $next) {
        // echo "test 2.1\n";
        $result = $next ? $next($request) : null;
        // echo "test 2.2\n";
        return $result;
    }
}