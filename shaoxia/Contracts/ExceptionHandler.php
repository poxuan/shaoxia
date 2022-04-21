<?php

namespace Shaoxia\Contracts;

interface ExceptionHandler
{
    /**
     * 错误报告
     */
    public function report(\Throwable $e);
    /**
     * 渲染结果（web）
     */
    public function render(Request $r, \Throwable $t);
    /**
     * 渲染结果（cli）
     */
    public function renderForCli(Request $r, \Throwable $t);
}