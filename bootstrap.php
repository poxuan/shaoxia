<?php

$app = app();

// 手动绑定
$app->bind(
    Shaoxia\Boot\ExceptionHandler::class,
    Shaoxia\Exceptions\ErrorHandler::class
);

return $app;