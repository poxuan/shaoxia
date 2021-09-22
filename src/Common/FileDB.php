<?php

namespace Shaoxia\Common;

use Shaoxia\Structure\Bptree\BPlusTree;
use Shaoxia\Structure\Bptree\Data;

class FileDB
{
    const VERSION = 1.0;

    private $config = [
        'db_path' => '/tmp/file_db',
        'bptree_order' => 8,
    ];

    /**
     * @var BPlusTree
     */
    private $bptree = null;

    private $db_path = null;

    private $index_key = 'id';

    public function __construct($config = [])
    {
        $this->config = array_merge($this->config, $config);
        $this->dataFilter = new DataFilter();
    }

    public function open($table, $index_key = 'id', $lock = false) {
        // 把之前打开的表先存起来
        $this->_save();
        
        if ($lock) {
            $lock_path = $this->config['db_path'] . DS . $table . '.lk';
            $this->file = fopen($lock_path, 'w+');
            if (!flock($this->file, LOCK_EX)) {
                throw new \Exception('数据库加锁失败');
            }
        }
        $this->db_path = $this->config['db_path'] . DS . $table . '.bp';
        $this->index_key = $index_key ?: 'id';
        if (file_exists($this->db_path)) {
            $this->bptree = unserialize(file_get_contents($this->db_path));
        } else {
            $this->bptree = new BPlusTree($this->config['db_path']);
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
            throw new \Exception("function not found or param error");
        }
    }


    // 把打开的数据库存起来
    public function _save() {
        if ($this->bptree && $this->db_path) {
            file_put_contents($this->db_path, serialize($this->bptree));
            flock($this->db_path, LOCK_UN);
        }
    }

    public function __destruct()
    {
        $this->_save();
    }

        /**
     * @param $start
     * @param $end
     * @return array
     *
     * 添加查询规则
     */
    public function where($column, $operation = '=', $data = null)
    {
        $this->dataFilter->append('and', $column, $operation, $data);
    }

    /**
     * @param $start
     * @param $end
     * @return array
     *
     * 添加或查询规则
     */
    public function orWhere($column, $operation = '=', $data = null)
    {
        $this->dataFilter->append('or', $column, $operation, $data);
    }

    /**
     * 筛选
     */
    public function select($columns = "*", $limit = -1) {
        $nodes = $this->all();
        return  $this->dataFilter->filter($nodes, $columns);
    }

    /**
     * 首条
     */
    public function first($columns = "*") {
        $nodes = $this->select($columns);
        return array_unshift($nodes);
    }
}


/**
 * Class DataFilter
 *
 * 数据筛选规则
 */
class DataFilter
{
    protected $filter = [];

    public function __construct()
    {       
    }
    
    public function append($scope, $column, $operation = '=', $data = null) {
        if (is_array($column)) {
            foreach($column as $key => $val) {
                if (is_numeric($key) && is_array($val)) {
                    array_unshift($val, 'rule');
                    $rule[] = $val;
                } else {
                    $rule[] = ['rule', $key , '=', $val];
                }
            }
        } else {
            $rule = ['rule', $column, $operation , $data];
        }
        if ($scope == 'or') {
            $pop = array_pop($this->filter);
            $rule = [$pop, $rule, '_logic' => 'or'];
        }
        $this->filter[] = $rule;
    }

    public function filter($data, $columns = '*') {
        foreach($data as $key => $item) {
            if (!$this->_filter($item, $this->filter)){
                unset($data[$key]);
            } else {
                $data[$key] = $this->_filterColumn($data, $columns);
            }
        }
        return array_values($data);
    }

    private function _filterColumn($data, $columns) {
        if ($columns == '*') {
            return $data;
        }
        if (is_string($columns)) {
            $columns = array_map('trim', explode(',', $columns));
        }
        if (is_array($columns)) {
            $res = [];
            foreach($columns as $column) {
                $c = explode(" as ", $column);
                $res[$c[1] ?? $c[0]] = $data[$c[0]] ?? null;
            }
        }
    }

    private function _filter($item, $rules) {
        if (empty($rules)) {
            return true;
        }
        $logic = strtolower($rules['_logic'] ?? 'and');
        if ($rules[0] == 'rule') {
            $res = false;
            $column = $rules[1];
            $operation = $rules[2] ?? '=';
            $data = $rules[3] ?? null;
            $data = is_callable($data) ? $data[$item] : $data;
            if (is_string($column) && is_string($operation)) {
                $value = $item[$rules[1]] ?? null;
                switch(strtolower(trim($operation))) {
                    case '>':
                    case 'gt':
                        return $value > $data;
                    case '>=':
                    case 'egt':
                        return $value >= $data;
                    case '<':
                    case 'lt':
                        return $value < $data;
                    case '<=':
                    case 'elt':
                        return $value <= $data;
                    case 'like':
                        return stripos($value, trim($data,'%')) !== false;
                    case 'not like':
                        return stripos($value, trim($data,'%')) === false;
                    case 'is null':
                        return is_null($value);
                    case 'is not null':
                        return !is_null($value);
                    case '<>':
                    case '!=':
                    case 'neq':
                        return $value != $data;
                    case 'between':
                        return $value >= $data[0] && $value <= $data[1];
                    default:
                        return $value == $data;
                }
            } elseif (is_callable($column)) {
                return $column($item);
            }
        } elseif ($logic == 'and') {
            $res = true;
            foreach($rules as $rule) {
                $res &= $this->_filter($item, $rule);
            }
        } else {
            $res = false;
            foreach($rules as $rule) {
                $res |= $this->_filter($item, $rule);
            }
        }
        return $res;
    }
}
