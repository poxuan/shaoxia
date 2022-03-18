<?php

namespace Shaoxia\Component;

use Shaoxia\Adapter\Query;
use Shaoxia\Adapter\DbAdapter as Adapter;
use Shaoxia\Exceptions\CustomException;

class Db {

    const DB_READ = 1;
    const DB_WRITE = 2;

    /**
     * 连接适配器
     * @var \Shaoxia\Adapter\DbAdapter
     */
    private $_adapter;

    /**
     * SQL构造器
     * @var \Shaoxia\Adapter\Query
     */
    private $_query;

    // 配置信息
    private $_config = [];

    // 读写各自适配连接
    private $_connect_pool = []; 

    // 是否支持事务
    private $_IST = false;

    // 局部事务是否开启
    private $_transaction = false;

    // 当前事务连接
    private $_transaction_adapter = null;

    // 当前是否在全局事务中
    protected static $globalTransaction = false;
    
    // 全局事务期间注入的事务集
    private static $globalTransactionDBs = [];
    
    public function __construct($name = 'default')
    {
        $config = config($name ?: 'default', null, 'database');
        $this->_config = $config;
        $this->setAdapter($config['driver'] ?? "");
        $this->_query = new Query($this->_adapter);
    }

    /**
     * 设置驱动器
     */
    public function setAdapter($driver) {
        $adapter = "Shaoxia\Adapter\Db\\{$driver}";
        if (!class_exists($adapter)) {
            throw new CustomException("数据库驱动不存在");
        }
        if (!new $adapter instanceof Adapter) {
            throw new CustomException("数据库驱动不存在");
        }
        if (!$adapter::isAvailable()) {
            throw new CustomException("数据库驱动不可用");
        }
        $this->_adapter = $adapter;
        $this->_IST =  $adapter::isSupportTransaction();
    }

    /**
     * 开始全局事务
     */
    public static function beginTransaction() {
        static::$globalTransaction = true;
    }

    /**
     * 开启一个内部事务
     */
    public static function transaction($callable) {
        static::beginTransaction();
        try {
            $callable();
            static::commit();
        } catch(\Throwable $t) {
            static::rollback();
        }
    }

    /**
     * 回滚全局事务
     */
    public static function rollback() {
        try {
            foreach (static::$globalTransactionDBs as $db) {
                $db->_rollback();
            }
        } catch (\Throwable $e){
            throw new CustomException("回滚发生异常:". $e->getMessage());
        } finally {
            static::$globalTransactionDBs = [];
            static::$globalTransaction = false;
        }
    }

    /**
     * 提交全局事务
     */
    public static function commit() {
        try {
            foreach (static::$globalTransactionDBs as $db) {
                $db->_commit();
            }
        } catch (\Throwable $e){
            throw new CustomException("提交发生异常:". $e->getMessage());
        } finally {
            static::$globalTransactionDBs = [];
            static::$globalTransaction = false;
        }
        
    }


    public function _rollback() {
        if ($this->_transaction_adapter) {
            $this->_transaction_adapter->rollback();
        }
        $this->_transaction_adapter = null;
        $this->_transaction = false;
    }

    public function _commit() {
        if ($this->_transaction_adapter) {
            $this->_transaction_adapter->commit();
        }
        $this->_transaction_adapter = null;
        $this->_transaction = false;
    }

    /**
     * 选择链接
     * 如果连接池已经构建此链接，则直接使用
     */
    public function selectConnect($type = Db::DB_READ)
    {
        // 如果当前有事务适配器, 直接使用事务适配器
        if ($this->_transaction_adapter) {
            return $this->_transaction_adapter;
        }
        // 是否需要开启事务, 或当前是否在事务中
        $beginTransaction = false;
        if ($this->_IST) { // 是否支持事务
            if ($this->_transaction) {
                $type = Db::DB_WRITE;
            } else if (static::$globalTransaction) {
                $type = Db::DB_WRITE;
                $beginTransaction = true;
            }
        }
        if (!isset($this->_connect_pool[$type])) {
            $config = $this->_config;
            $host = $this->_config['host'];
            if (is_string($host)) {
                $host = explode(",", $host);
            }
            if (count($host) > 1 && $type == static::DB_READ && !static::$globalTransaction) {
                $config['host'] = $host[rand(1, count($host) -1)];
            } else {
                $config['host'] = $host[0];
            }
            $adapter = new $this->_adapter;
            $adapter->connect($config);
            $this->_connect_pool[$type] = $adapter;
        } else {
            $adapter = $this->_connect_pool[$type];
        }
        // 开启事务, 并加入全局事务
        if ($beginTransaction) {
            $adapter->beginTransaction();
            $this->_transaction_adapter = $adapter; 
            static::$globalTransaction && static::$globalTransactionDBs[] = $this;
        }
        return $adapter;
    }

    public static function connect($name = 'default') {
        return new self($name);
    }

    public function setTable($name, $as= "" ) {
        $this->_query->table($name, $this->_config['prefix'] ?? '', $as);
    }

    public function setQuery($query) {
        $this->_query = $query;
    }

    public static function __callStatic($name, $arguments)
    {
        $ins = new self();
        $ins->_query->$name(...$arguments);
        return $ins;
    }

    public function __call($name, $arguments)
    {
        $this->_query->$name(...$arguments);
        return $this;
    }

    public function get() {
        $connection = $this->selectConnect();
        $this->_query->setType(Query::TYPE_SELECT);
        $resource = $connection->query($this->_query);
        return $connection->fetchAll($resource);
    }

    public function toSql() {
        $sql = $this->_adapter::parseSql($this->_query);
        return $sql;
    }

    public function update($data) {
        $connection = $this->selectConnect(static::DB_WRITE);
        $this->_query->setType(Query::TYPE_UPDATE);
        $this->_query->columns($data);
        
        $resource = $connection->query($this->_query);
        return $connection->fetchAll($resource);
    }

    public function insert($data) {
        $connection = $this->selectConnect(static::DB_WRITE);
        $this->_query->setType(Query::TYPE_INSERT);
        $this->_query->columns($data);
        $connection->query($this->_query);
        return $connection->lastInsertId();
    }
}