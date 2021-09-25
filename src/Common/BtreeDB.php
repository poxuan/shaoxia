<?php

namespace Shaoxia\Common;

use Shaoxia\Structure\Bptree\BPlusTree;
use Shaoxia\Structure\Bptree\Data;
use Shaoxia\Structure\Bptree\DataFilter;

class BtreeDB
{
    const VERSION = 1.0;

    /**
     * @var BPlusTree
     */
    private $bptree = null;

    private $db_path = null;
    private $order = 3;
    private $index_key = 'id';

    /**
     * @var DataFilter
     */
    private $dataFilter = null;

    public function __construct($db_path = '/tmp/file_db', $order = 3)
    {
        $this->db_path = $db_path;
        $this->order = $order;
        $this->dataFilter = new DataFilter();
    }

    public function open($table, $index_key = 'id', $lock = false) {
        // 把之前打开的表先存起来
        $this->_save();
        
        if ($lock) {
            $lock_path = $this->db_path . DS . $table . '.lk';
            $this->file = fopen($lock_path, 'w+');
            if (!flock($this->file, LOCK_EX)) {
                throw new \Exception('数据库加锁失败');
            }
        }
        $this->db_file = $this->db_path . DS . $table . '.bp';
        $this->index_key = $index_key ?: 'id';
        if (file_exists($this->db_file)) {
            $this->bptree = unserialize(file_get_contents($this->db_file));
        } else {
            $this->bptree = new BPlusTree($this->order);
        }
    }

    public function insert($data) {
        $data = new Data($data, $this->index_key);
        $this->bptree->insert($data);
    }

    public function __call($name, $arguments)
    {
        try {
            $res = call_user_func_array([$this->bptree, $name], $arguments);
            if (is_null($res)) {
                return $this;
            }
            return $res;
        } catch(\Throwable $t) {
            throw new \Exception("function $name not found or param error: ".$t->getMessage());
        }
    }


    // 把打开的数据库存起来
    public function _save() {
        if ($this->bptree && $this->db_file) {
            file_put_contents($this->db_file, serialize($this->bptree));
            flock($this->db_file, LOCK_UN);
        }
    }

    public function __destruct()
    {
        $this->_save();
    }

        /**
     * @param $start
     * @param $end
     * @return self
     *
     * 添加查询规则
     */
    public function where($column, $operation = '=', $data = null)
    {
        $this->dataFilter->append('and', $column, $operation, $data);
        return $this;
    }

    /**
     * @param $start
     * @param $end
     * @return self
     *
     * 添加或查询规则
     */
    public function orWhere($column, $operation = '=', $data = null)
    {
        $this->dataFilter->append('or', $column, $operation, $data);
        return $this;
    }

    /**
     * 筛选
     */
    public function select($columns = "*") {
        $data = $this->filterFind($this->dataFilter, $columns);
        $this->dataFilter = new DataFilter;
        return $data;
    }

    /**
     * 首条
     */
    public function first($columns = "*") {
        $nodes = $this->select($columns);
        return array_shift($nodes);
    }

}
