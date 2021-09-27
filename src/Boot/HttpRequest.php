<?php

namespace Shaoxia\Boot;

class HttpRequest implements Request
{
    protected $params = [];
    protected $options = null;
    protected $headers = [];
    public function __construct() {
        $this->getOptions();
    }

    public function get($name,$default = null) 
    {
        return $this->getOption($name,$default);
    }

    public function getOptions()
    {
        if ($this->options !== null) {
            return $this->options;
        }
        $options = [];
        if ($_GET) {
            foreach ($_GET as $key => $value) {
                $options[$key] = $value;
            }
        }
        if ($_POST) {
            foreach ($_POST as $key => $value) {
                $options[$key] = $value;
            }
        }
        if ($_FILES) {
            foreach ($_FILES as $key => $value) {
                $options[$key] = $value;
            }
        }
        if ($_SERVER['CONTENT_TYPE'] == "application/json") {
            $data = json_decode(file_get_contents('php://input'), true);
            foreach ($data as $key => $value) {
                $options[$key] = $value;
            }
        }
        $this->options = $options;
        return $options;
    }

    public function has($name) 
    {
        if (isset($this->options[$name])) {
            return true;
        }
        return false;
    }

    public function getOption($name,$default = null) 
    {
        if (isset($this->options[$name])) {
            return $this->options[$name];
        }
        return $default;
    }

    public function header($name = '',$default = null) {
        if (empty($this->headers)) {
            foreach($_SERVER as $key => $value) {
                if (substr($name, 0, 5) == 'HTTP_') {
                    $key = strtolower(str_replace('_', '-', substr($name, 5)));
                    $this->headers[$key] = $value;
                }
            }
        }
        if ($name) {
            return $this->headers[strtolower($name)] ?? $default;
        }
        return $this->headers;
    }
    
}