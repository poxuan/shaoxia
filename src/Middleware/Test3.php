<?php 

namespace Shaoxia\Middleware;

class Test3
{
    function handle($request, $next) {
        $result = $next ? $next($request) : null;
        echo "test 3\n";
        return $result;
    }
}