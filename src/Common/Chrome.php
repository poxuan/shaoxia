<?php 

namespace Shaoxia\Common;

use Exception;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;

class Chrome
{
    protected $host = 'http://localhost:4444/wd/hub';

    protected $driver = null;

    const TYPE_ID = 'id';
    const TYPE_CLASS = 'className';
    const TYPE_NAME = 'name';
    const TYPE_SELECTOR = 'cssSelector';
    const TYPE_XPATH = 'xpath';

    public function __construct()
    {
        try {
            $this->driver = RemoteWebDriver::create($this->host, DesiredCapabilities::chrome(), 50000);
        } catch(\Throwable $t) {
            echo $t->getLine() . $t->getMessage()."\n";
        }
    }
 
    public function url($url) {
        try {
            $this->driver->get($url);
            return $this;
        } catch(\Throwable $t) {
            echo $t->getLine() . $t->getMessage()."\n";
        }
    }

    public function sleep($sec = 1) {
        sleep($sec);
        return $this;
    }

    // 根据ID获取DOM对象
    public function findById($id) {
        return $this->findBy(static::TYPE_ID, $id);
    }

    protected function _makeDirver($type, $str) {
        if (method_exists(WebDriverBy::class, $type)) {
            return WebDriverBy::$type($str);
        } else {
            throw new Exception("查找类型未知");
        }
    }

    public function findBy($type, $str, $multi = false) {
        $driverBy = $this->_makeDirver($type, $str);
        try {
            if ($multi) {
                $element = $this->driver->findElement($driverBy);
                return $element;
            } else {
                $elements = $this->driver->findElements($driverBy);
                return $elements;
            }
        } catch(\Throwable $t) {
            throw new Exception("无法获取 $type: {$str} 的元素:".$t->getMessage());
        }
    }

    public function __call($name, $arguments)
    {
        if (substr($name, 0, 6) == 'findBy') {
            $type = substr($name, 6);
            return $this->findBy($type, $arguments[0], false);
        } elseif (substr($name, 0, 9) == 'findAllBy') {
            $type = substr($name, 9);
            return $this->findBy($type, $arguments[0], true);
        }
    }

    public function __destruct()
    {
        $this->driver->quit();
    }
}