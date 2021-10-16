<?php 

namespace App\Middleware;

class Test2 
{
    function handle($request, $next) {
        // echo "test 2.1\n";
        $result = $next($request);
        // echo "test 2.2\n";
        return $result;
    }
}