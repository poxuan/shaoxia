<?php

namespace Shaoxia\Boot;

class HttpRequest implements Request
{
    protected $params = [];
    protected $options = null;

    public function __construct() {
        
    }

    public function get($name,$default = null) 
    {
        if (isset($_GET[$name])) {
            return $_GET[$name];
        } elseif(isset($_POST[$name])) {
            return $_POST[$name];
        } else {
            return $default;
        }
    }
    
}