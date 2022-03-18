<?php

namespace Shaoxia\Adapter\Db;

use Shaoxia\Adapter\CacheAdapter;
use Shaoxia\Exceptions\CustomException;

/**
 * Redis cache
 */
class FileCache implements CacheAdapter
{
    public function __construct($config)
    {
               
    }

    public function connect() 
    {

    }

    public function get($key, $default = null) {

    }

    public function set($key, $value, $ttl = null) {

    }

    public function clear() {

    }

    public function delete($key) {

    }

    public function getMultiple($keys, $default = null) {

    }

    public function setMultiple($values, $ttl = null) {

    }

    public function deleteMultiple($keys) {

    }

    public function has($key) {

    }
}
