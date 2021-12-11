<?php

namespace Shaoxia\Adapter\Db;

use Shaoxia\Exceptions\CustomException;

/**
 * pdo 连接基类
 */
abstract class MyPdo implements Adapter{

    /**
     * @var \Pdo
     */
    private $_connect;

    abstract function init($config);

    public static function isAvailable() {
        return class_exists('PDO');
    }

    
    public function connect($config)
    {
        try {
            $this->_connect = $this->init($config);
            $this->_connect->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            return $this;
        } catch (\PDOException $e) {
            /** 数据库异常 */
            throw new CustomException("Pdo 链接异常");
        }
    }
    

    public function getVersion()
    {
        return 'pdo:' . $this->_connect->getAttribute(\PDO::ATTR_DRIVER_NAME) 
        . ' ' . $this->_connect->getAttribute(\PDO::ATTR_SERVER_VERSION);
    }

    public function lastInsertId()
    {
        return $this->_connect->lastInsertId();
    }

    public function query($sql, $bindings = []) {
        try {
            if ($sql instanceof Query) {
                $bindings = $sql->getBindings();
                $sql = $this->parseSql($sql);
            }
            $resource = $this->_connect->prepare($sql);
            $resource->execute($bindings);
            return $resource;
        } catch (\PDOException $e) {
            /** 数据库链接异常 */
            throw new CustomException("Pdo 链接异常:" .$sql. $e->getMessage());
        }
    }

    /**
     * @param \PDOStatement $resource 
     */
    public function fetch($resource) {
        return $resource->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * @param \PDOStatement $resource 
     */
    public function fetchAll($resource) {
        return $resource->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @param \PDOStatement $resource 
     */
    public function fetchObject($resource) {
        return $resource->fetchObject();
    }

    abstract static function parseSql($query);
}