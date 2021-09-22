<?php

namespace Shaoxia\Exceptions;

use Shaoxia\Boot\Request;

/**
 * Class File
 * @package Shaoxia\Exceptions
 */
class ErrorHandler
{
    public function rander(Request $request, \Throwable $t) {
        echo $t->getMessage();
    }
}