<?php

namespace Shaoxia\Component;

use Shaoxia\Adapter\Db\Query;
use Shaoxia\Support\Collection;
use Shaoxia\Support\Contracts\Arrayable;

class Model implements Arrayable {
    
    protected $table;

    // 主键
    protected $primaryKey = 'id';

    protected $alias = 't';

    // 数据库连接名
    protected $connection;

    // 数据库连接实例
    protected $connect;

    // 原始数据
    protected $rawData = [];

    // 更新数据
    protected $updated = [];

    // 不输出字段
    protected $hidden;

    // 可显示字段，优先级高于 $hidden
    protected $visiable;

    public function __construct($data = null)
    {
        $this->rawData = $data;
    }

    public function connection() {
        $this->connect = Db::connect($this->connection);
        $this->connect->setTable($this->getTable(), $this->alias);
    }


    protected function getTable() {
        return $this->table ?: toUnderScore(basename(get_class($this)));
    }

    public static function __callStatic($name, $arguments)
    {
        $ins =  new static;
        $ins->connect->$name(...$arguments);
        return $ins;
    }

    public static function query() {
        $ins =  new static;
        $ins->connection();
        return $ins;
    }

    public function __call($name, $arguments)
    {
        $this->connect->$name(...$arguments);
        return $this;
    }

    public function toSql()
    {
        return $this->connect->toSql();
    }

    public function first() {
        $this->connect->limit(1);
        $res = $this->connect->get();
        if ($res) {
            $this->rawData = $res[0];
            return $this;
        }
        return null;
    }

    public function get() {
        $res = $this->connect->get();
        $collection = new Collection();
        foreach($res as $item) {
            $collection->pull(new static($item));
        }
        return $collection;
    }

    public function update($data = []) {
        $res = $this->connect->update(array_merge($this->updated, $data));
        return $res;
    }

    public function insert($data) {

    }

    protected function setRawData($data) {
        $this->rawData = $data;
    }

    public function __get($name)
    {
        return $this->rawData[$name] ?? null;
    }

    public function __set($name, $value)
    {
        $this->updated[$name] = $value;
        $this->rawData[$name] = $value;
    }

    public function toArray() {
        $arr = [];
        foreach($this->rawData as $name => $val) {
            if ($this->hidden && in_array($name, $this->hidden)) {
                continue;
            }
            if ($this->visiable && !in_array($name, $this->visiable)) {
                continue;
            }
            $arr[$name] = $val;
        }
        return $arr;
    }
}