<?php

namespace Shaoxia\Boot;

class CliRequest implements Request
{
    protected $params = [];
    protected $options = null;


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
        global $argv;
        $options = [];
        foreach ($argv as $arg) {
            if ($pos = strpos($arg,'=')) {
                $key = ltrim(substr($arg,0,$pos),'-');
                $value = substr($arg,$pos+1);
                $options[$key] = $value;
                $lv = strtolower($value);
                $le = strlen($value);
                if ($lv == 'null') {
                    $options[$key] = null;
                } elseif ($lv == 'true' || $lv == 'false') {
                    $options[$key] = $lv == 'true' ? true : false;
                } elseif ($value[0] == '"' && $value[$le -1] == '"') {
                    $options[$key] = substr($value,1,$le-2);
                }
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
}