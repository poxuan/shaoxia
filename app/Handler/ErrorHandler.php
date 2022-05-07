<?php

namespace App\Handler;

use Shaoxia\Contracts\ExceptionHandler;
use Shaoxia\Contracts\Request;

/**
 * Class File
 * @package App\Exceptions
 */
class ErrorHandler implements ExceptionHandler
{
    // 记录报告
    public function report(\Throwable $t) {

    }

    // 渲染结果
    public function render(Request $request, \Throwable $t) {
        return response()->resource([
            'code' => 500,
            'message' => $t->getMessage(),
        ], 'json');
    }

    public function renderForCli(Request $request, \Throwable $t) {
        var_dump($t);
    }
}