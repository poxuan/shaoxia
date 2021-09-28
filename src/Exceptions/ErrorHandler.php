<?php

namespace Shaoxia\Exceptions;

use Shaoxia\Boot\ExceptionHandler;
use Shaoxia\Boot\Request;

/**
 * Class File
 * @package Shaoxia\Exceptions
 */
class ErrorHandler implements ExceptionHandler
{
    // 记录报告
    public function report(\Throwable $t) {

    }

    // 渲染结果
    public function render(Request $request, \Throwable $t) {
        die($t);
    }

    public function renderForCli(Request $request, \Throwable $t) {
        die($t);
    }
}