<?php

namespace Shaoxia\Boot;

class CliResponse implements Response
{
    protected $resource = null;

    public function resource($resource = null) 
    {
        $this->resource = $resource;
        return $this;
    }
    
    public function output() 
    {
        if (is_string($this->resource)) {
            echo $this->resource;
        } elseif($this->resource !== null) {
            var_dump($this->resource);
        }
    }
}