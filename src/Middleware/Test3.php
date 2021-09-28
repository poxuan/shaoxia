<?php 

namespace Shaoxia\Middleware;

class Test3
{
    function handle($request, $next) {
        // echo "test 3.1\n";
        $result = $next($request);
        // echo "test 3.2\n";
        return $result;
    }
}