<?php

namespace Shaoxia\Adapter;

use Shaoxia\Component\Db;
use Shaoxia\Exceptions\CustomException;

/**
 * SQL构造类
 */
class Query {

    // 请求类型
    const TYPE_SELECT = 'SELECT';
    const TYPE_INSERT = 'INSERT';
    const TYPE_UPDATE = 'UPDATE';
    const TYPE_DELETE = 'DELETE';

    // 连接方式
    const JOIN_LEFT = "LEFT";
    const JOIN_RIGHT = "RIGHT";
    const JOIN_INNER = "INNER";

    // sql数组
    private $_param = [
        "type"   => self::TYPE_SELECT,  // 请求类型 select, update, delete, insert
        "table"  => null, // 表名
        "fields" => "", // 查询字段
        "joins"  => null,   // 链接表
        "where"  => [],   // 查询条件
        "group"  => null, // 分组条件
        "having" => null,// having条件
        "order"  => null, // 排序条件
        "limit"  => null, // 数量
        "offset" => null,// 偏移
        "lock"   => false, // 锁定
        "columns"  => [],// 插入或更新的字段
        "bindings" => [],// 绑定参数
    ];

    // 适配器
    protected $adapter;

    public function __construct($adapter)
    {
        $this->adapter = $adapter;
    }

    public function table($name, $prefix = "", $as = "") {
        $this->_param['table'] = $prefix.$name. ($as ? ' as '. $as : '');
        return $this;
    }

    public function select($fields) {
        $glue = $this->_param['fields'] ? ',' : "";
        if (is_array($fields)) {
            $this->_param['fields'] .= $glue . implode(",", $fields);
        } else {
            $this->_param['fields'] .= $glue . $fields;
        }
        return $this;
    }

    public function join($table, $condition, $type = self::JOIN_LEFT) {
        $this->_param['join'] .= " $type join $table on $condition";
        return $this;
    }

    public function when($condition, $call1 = null, $call2 = null) {
        if ($condition) {
            $call1 && $call1($this);
        } else {
            $call2 && $call2($this);
        }
        return $this;
    }

    // where 条件
    public function where(...$params) {
        $c = count($params);
        switch($c) {
            case 1:
                $p = $params[0];
                if (is_callable($p)) {
                    $c1 = count($this->_param['where']);
                    $p($this);
                    $c2 = count($this->_param['where']);
                    $count = $c2 - $c1;
                    if ($c2 - $c1 > 1) {
                        $arr = [];
                        while($count > 1) {
                            $cu = array_pop($this->_param['where']);
                            $arr[] = $cu;
                            $count--;
                        }
                        $arr = array_reverse($arr);
                        $this->_param['where'][] = "( " .implode(" and ", $arr) .")";
                    }
                } elseif (is_array($p)) {
                    $count = 0;
                    foreach($p as $k => $v) {
                        if (!is_numeric($k)) {
                            $v = is_array($v) ? $v : [$v];
                            array_unshift($v, $k);
                        }
                        call_user_func_array([$this, 'where'], $v) && $count++;
                    }
                    while($count > 1) {
                        $cu = array_pop($this->_param['where']);
                        $pre = array_pop($this->_param['where']);
                        $this->_param['where'][] = "( " .implode(" and ", [$pre,$cu]) .")";
                        $count--;
                    }
                } elseif (is_string($p)) {
                    $this->_param['where'][] = $p;
                }
                break;
            case 2:    // 第二位表示值
                $this->_param['where'][] = $params[0]." = ? ";
                $this->_param['bindings'][] = $params[1];
                break;
            case 3:    // 第2位表示操作，第三位表示值
                $glue = strtolower(trim($params[1]));
                switch($glue) {
                    case '=':
                    case '!=':
                    case '<>':
                    case '>=':
                    case '>':
                    case '>=':
                    case '<':
                    case '<=':
                    case 'like':
                    case 'not like':
                        $this->_param['where'][] = $params[0]." " . $params[1]." ? ";
                        $this->_param['bindings'][] = $params[2];
                        break;
                    case 'between':
                    case 'not between':
                        $this->_param['where'][] = $params[0]." " . $params[1]." ? and ? ";
                        $this->_param['bindings'][] = $params[2][0];
                        $this->_param['bindings'][] = $params[2][1] ?? "";
                        break;
                    case 'in':
                    case 'not in':
                        $ar = [];
                        foreach($params[2] as $val) {
                            $ar[] = "?";
                            $this->_param['bindings'][] = $val;
                        }
                        $this->_param['where'][] = $params[0] . " " . $params[1]. " (" . implode(",", $ar) . ")";
                        break;
                    case 'is':
                    case 'is not':
                        $this->_param['where'][] =  $params[0] . " " . $params[1]. " null";
                        break;
                    default:
                        throw new CustomException("不支持的条件类型");
                }
                break;
            default:
                throw new CustomException("参数数目异常");
        }
        return $this;
    }

    // 查询加锁
    public function lock() {
        $this->_param['lock'] = true;
        return $this;
    }

    // orwhere 条件
    public function orWhere(...$params) {
        call_user_func_array([$this, 'where'], $params);
        if (count($this->_param['where']) >= 2) {
            $cu = array_pop($this->_param['where']);
            $pre = array_pop($this->_param['where']);
            $this->_param['where'][] = "(" . implode(" or ", [$pre, $cu]) . ")";
        }
        return $this;
    }

    // 原始 where 条件
    public function whereRaw($where, $bindings = []) {
        $this->_param['where'][] = $where;
        $this->_param['bindings'] = array_merge($this->_param['bindings'], $bindings);
        return $this;
    }

    // 分组
    public function group($group) {
        $this->_param['group'] = " GROUP BY " . $group;
        return $this;
    }

    // 排序
    public function order($column, $type = ""){
        if ($this->_param['order']) {
            $this->_param['order']  .= ",$column $type"; 
        } else {
            $this->_param['order'] = " ORDER BY $column $type";
        }
        return $this;
    }

    // 更新字段
    public function columns($data){
        $this->_param['columns'] = $data;
        
    }

    public function limit($limit, $offset = null){
        $this->_param['limit']  = " LIMIT $limit";
        if ($offset) {
            $this->offset($offset);
        } 
        return $this;
    }

    public function offset($offset){
        $this->_param['offset']  = " OFFSET $offset";
        return $this;
    }

    public function getBindings() {
        return $this->_param['bindings'];
    }

    public function setType($type) {
        switch (strtoupper($type)) {
            case static::TYPE_DELETE:
            case static::TYPE_INSERT:
            case static::TYPE_SELECT:
            case static::TYPE_UPDATE:
                $this->_param['type'] = $type;
                break;
            default:
                throw new CustomException("未知SQL类型:". $type);
        }
    }

    public function toSql() {
        $adapter = $this->adapter;
        return $adapter::parseSql($this);
    }

    public function __toString()
    {
        return $this->toSql();
    }

    public function __get($name)
    {
        return $this->_param[$name] ?? null;
    }
}