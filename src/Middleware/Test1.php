<?php 

namespace Shaoxia\Middleware;

class Test1 
{
    function handle($request, $next) {
        if (rand(1,100) > 50) {
            // echo "test 1\n";
            // return response()->resource("随机中断");
        } 
        return $next($request);
    }
}