<?php

namespace Shaoxia\Boot;

interface ExceptionHandler
{
    public function report(\Throwable $e);
    public function render(Request $r, \Throwable $t);
    public function renderForCli(Request $r, \Throwable $t);
}