<?php

namespace Shaoxia\Adapter\Db;

use Shaoxia\Adapter\DbAdapter;
use Shaoxia\Adapter\Query;
use Shaoxia\Exceptions\CustomException;

/**
 * pdo 连接基类
 */
abstract class MyPdo implements DbAdapter{

    /**
     * @var \Pdo
     */
    private $_connect;

    private $_inTrans = false;

    abstract function init($config);

    public static function isAvailable() {
        return class_exists('PDO');
    }

    public function __destruct()
    {
        // 事务之内析出, 则将事务回滚.
        if ($this->_inTrans) {
            $this->_connect->rollBack();
        }
    }

    // 是否支持事务
    public static function isSupportTransaction() {
        return true;
    }

    // 开始事务
    public function beginTransaction() {
        if (!$this->_inTrans) {
            $res = $this->_connect->beginTransaction();
            $res && $this->_inTrans = true;
            return $res;
        } else {
            return false;
        }
    }

    // 回滚事务
    public function rollback() {
        $this->_inTrans = false;
        return $this->_connect->rollBack();
    }

    // 提交事务
    public function commit() {
        $this->_inTrans = false;
        return $this->_connect->commit();
    }
    
    public function connect($config)
    {
        try {
            $this->_connect = $this->init($config);
            $this->_connect->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            return $this;
        } catch (\PDOException $e) {
            /** 数据库异常 */
            throw new CustomException("Pdo 链接异常:" . $e->getMessage());
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
            var_dump($sql);
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

    /**
     * @param \PDOStatement $resource 
     */
    public function rowCount($resource) {
        return $resource->rowCount();
    }

    abstract static function parseSql($query);
}