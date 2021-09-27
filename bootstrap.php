<?php

$app = app();

$app->bind(
    Shaoxia\Boot\ExceptionHandler::class,
    Shaoxia\Exceptions\ErrorHandler::class
);

return $app;