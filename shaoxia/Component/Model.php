<?php

namespace Shaoxia\Component;

use ArrayAccess;
use \Shaoxia\Adapter\Db\Query;
use \Shaoxia\Support\Collection;
use \Shaoxia\Contracts\Arrayable;

class Model implements Arrayable, ArrayAccess {
    
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

    // 修改
    protected $cast;

    public function __construct($data = [])
    {
        $this->rawData = $data;
    }

    public function connection() {
        $this->connect = Db::connect($this->connection);
        $this->connect->setTable($this->getTable(), $this->alias);
    }


    protected function getTable() {
        return $this->table ?: toUnderScore(basename(str_replace('\\', '/', get_class($this))));
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

    public function first($query = []) {
        $res = $this->connect->where($query)->first();
        if ($res) {
            $this->rawData = $res;
            return $this;
        }
        return null;
    }

    public function count() {
        return $this->connect->count();
    }

    public function find($id) {
        $res = $this->connect->where($this->primaryKey, $id)->first();
        if ($res) {
            $this->rawData = $res;
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

    public function delete() {
        $res = $this->connect->delete();
        return $res;
    }

    public function destory() {
        $res = $this->connect->where($this->primaryKey, $this->rawData[$this->primaryKey])->delete();
        return $res;
    }

    public function create($data = []) {
        $data = array_merge($this->rawData ?: [], $data);
        $lastInsertID = $this->connect->insert($data);
        if ($lastInsertID) {
            return $this->find($lastInsertID);
        }
        return $this;
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
            if (isset($this->cast[$name])) {
                if ($this->cast[$name] == 'json') {
                    $val = json_decode($val);
                } else if ($this->cast[$name] == 'obj') {
                    $val = unserialize($val);
                } else if (is_callable( $this->cast[$name])) {
                    $val = $this->cast[$name]($val);
                } else if (strpos($this->cast[$name], "|")) { // 带参数的方法
                    list($func, $vals) = explode("|", $this->cast[$name]);
                    $vals = explode(";", $vals);
                    array_unshift($vals, $val);
                    $val = call_user_func_array($func, $vals);
                }
            }
            $arr[$name] = $val;
        }
        return $arr;
    }

    public function offsetExists($offset) : bool {
        return true;
    }

    public function offsetGet($offset) {

        return $this->rawData[$offset] || null;
    }

    public function offsetSet($offset, $val)  {
        $this->updated[$offset] = $val;
        $this->rawData[$offset] = $val;
    }

    public function offsetUnset($offset) {
        unset($this->updated[$offset]);
        unset($this->rawData[$offset]);
    }
}