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
    protected $_connect;

    protected $_inTrans = false;

    protected $lastPing = 0;

    protected $_config;

    abstract function init($config);

    public static function isAvailable() {
        return class_exists('PDO');
    }


    function ping() {
        // 不在事务中，且上次ping不超过两秒, 用于长期连接处理
        if (!$this->_inTrans && $this->lastPing + 2 < time()) {
            try {
                $ret = $this->_connect->getAttribute(\PDO::ATTR_SERVER_INFO);
                if ($ret === null) {
                    $this->connect();
                    return false;
                }
            } catch (\PDOException $e) {
                if(strpos($e->getMessage(), 'MySQL server has gone away')!==false){
                    $this->connect();
                    return false;
                }
            }
            $this->lastPing = time();
        }
        return true;
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
            $this->ping();  // 事务开启前做 ping 操作
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
    
    public function connect($config = [])
    {
        try {
            if ($config) {
                $this->_config = $config;
            } else {
                $config = $this->_config;
            }
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
            $this->ping();
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
    public function fetchObject($resource, $class) {
        return $resource->fetchObject($class);
    }

    /**
     * @param \PDOStatement $resource 
     */
    public function rowCount($resource) {
        return $resource->rowCount();
    }

    abstract static function parseSql($query);
}