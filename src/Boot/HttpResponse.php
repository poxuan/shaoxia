<?php

namespace Shaoxia\Boot;

class HttpResponse implements Response
{
    protected $resource = null;
    protected $type = null;

    public function resource($resource = null, $type = null) 
    {
        $this->resource = $resource;
        $this->type = $type;
        return $this;
    }

    public function withHeader($key, $val="") 
    {
        $header = $key;
        if ($val) {
            $header .= ': '.$val;
        }
        header($header);
        return $this;
    }
    
    public function output() 
    {
        $result = "";
        $type = '';
        if (!$this->type) {
            $accept = request()->header('accept');
            if ($accept && $accept != '*') {
                $as = explode(",", $accept);
                foreach($as as $a) {
                    if ($a && strpos($a, '*') === false) {
                        $type = $a;
                        break;
                    }
                }
            }
        }
        switch(strtolower($type)) {
            case 'json':
            case 'application/json':
                header('Content-Type: application/json');
                echo json_encode($this->resource);
                break;
            case 'jsonp':
            case 'application/jsonp':
                $func = $_GET['callback'];
                echo $func."(".json_encode($this->resource).")";
                break;
            default:
                echo $result ?: "";
        }
        
    }
}