<?php

namespace Shaoxia\Boot;

interface Request
{
    public function get($name,$default = null);
}