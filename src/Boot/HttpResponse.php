<?php

namespace Shaoxia\Boot;

class HttpResponse implements Response
{
    protected $resource = null;

    public function resource($resource = null) 
    {
        $this->resource = $resource;
        return $this;
    }
    
    public function output() 
    {
        $result = "";
        if (is_object($this->resource)) {
            if ($this->resource instanceof Collection){
                $result = $this->resource->toArray();
            } elseif ($this->resource instanceof ArrayAccess) {
                $result = (array) $this->resource;
            } elseif ($this->resource instanceof Serializable) {
                $result = $this->resource->serialize();
            } else {
                $result = (string) $this->resource;
            }
        } else {
            $result = $this->resource;
        }
        if (\is_array($result)) {
            header('Content-type: application/json');
            echo \json_encode($result);
        } else {
            header( 'Content-Type:text/html;charset=utf-8 ');
            echo $result ?: "";
        }
    }
}