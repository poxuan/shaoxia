<?php 

namespace Shaoxia\Middleware;

class Test1 
{
    function handle($request, $next) {
        // echo "test 1\n";
        return $next($request);
    }
}