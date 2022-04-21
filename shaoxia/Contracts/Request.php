<?php

namespace Shaoxia\Contracts;

interface Request
{
    public function get($name,$default = null);
    public function has($name);
}