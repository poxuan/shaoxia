<?php

namespace Shaoxia\Component;

use Shaoxia\Adapter\Db\Query;
use Shaoxia\Exceptions\CustomException;

class Db {

    const DB_READ = 1;
    const DB_WIRTE = 2;

    // 设置适配器
    /**
     * @var Shaoxia\Adapter\Db\Adapter
     */
    private $_adapter;

    private $_config = [];
    // 读写各自适配示例
    private $_connect_pool = []; 

    // 当前是否在全局事务中
    protected static $globalTransaction = false;
    
    /**
     * @var Shaoxia\Adapter\Db\Query
     */
    private $_query;

    public function __construct($name = 'default')
    {
        $config = config($name ?: 'default', null, 'database');
        $this->setConfig($config);
        $this->_query = new Query($this->_adapter);
    }

    public function setAdapter($driver) {
        $adapter = "Shaoxia\Adapter\Db\\{$driver}";
        if (!class_exists($adapter)) {
            throw new CustomException("数据库驱动不存在");
        }
        if (!$adapter::isAvailable()) {
            throw new CustomException("数据库驱动不可用");
        }
        $this->_adapter = $adapter;
    }

    /**
     * 选择链接
     * 如果连接池已经构建此链接，则直接使用
     */
    public function selectConnect($type = Db::DB_READ)
    {
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
        }
        return $this->_connect_pool[$type];
    }

    
    public function setConfig($config) {
        $this->_config = $config;
        $this->setAdapter($config['driver'] ?? "");
    }

    public static function connect($name = 'default') {
        return new self($name);
    }

    public function setTable($name) {
        $this->_query->table($name, $this->_config['prefix'] ?? '');
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
        return $this->_adapter::parseSql($this->_query);
    }

    public function update($data) {
        $connection = $this->selectConnect(static::DB_WIRTE);
        $this->_query->setType(Query::TYPE_UPDATE);
        $this->_query->columns($data);
        
        $resource = $connection->query($this->_query);
        return $connection->fetchAll($resource);
    }

    public function insert($data) {
        $connection = $this->selectConnect(static::DB_WIRTE);
        $this->_query->setType(Query::TYPE_INSERT);
        $this->_query->columns($data);
        $connection->query($this->_query);
        return $connection->lastInsertId();
    }
}