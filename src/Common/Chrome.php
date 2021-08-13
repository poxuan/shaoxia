<?php 

namespace Shaoxia\Common;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;

class Chrome
{
    protected $host = 'http://localhost:4444/wd/hub';

    protected $driver = null;

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

    public function findById($id) {
        try {
            $element = $this->driver->findElement(WebDriverBy::id($id));
            return $element->getText();
        } catch(\Throwable $t) {
            echo $t->getLine() . $t->getMessage()."\n";
        }
    }

    public function __destruct()
    {
        $this->driver->quit();
    }
}