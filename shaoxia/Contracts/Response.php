<?php

namespace Shaoxia\Contracts;

interface Response
{
    public function resource($resource, $type = null);

    public function output();

}