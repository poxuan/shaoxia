<?php

namespace App\Structure\Bptree;

/**
 * Class Data
 *
 * 数据类
 */
class Data implements \ArrayAccess
{
    private $data = [];

    private $indexKey;

    public function __construct(array $data, $indexKey = 'id')
    {
        $this->data = $data;
        $this->indexKey = $indexKey;
    }

    public function getIndex()
    {
        return isset($this->data[$this->indexKey]) ? $this->data[$this->indexKey] : 0;
    }

    public function setIndex($index)
    {
        $this->data[$this->indexKey] = $index;
    }

    public function setData($data)
    {
        $this->data = array_merge($this->data, $data);
    }

    public function offsetExists($offset) {
        return isset($this->data[$offset]);
    }

    public function offsetGet($offset) {
        return $this->data[$offset] ?? null;
    }

    public function offsetSet($offset, $value) {
        $this->data[$offset] = $value;
    }

    public function offsetUnset($offset) {
        unset($this->data[$offset]);
    }

    public function toArray() {
        return $this->data;
    }

    public function __toString()
    {
        return json_encode($this->data);
    }

}