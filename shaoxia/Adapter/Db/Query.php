<?php

namespace Shaoxia\Adapter\Db;

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
        "fields" => "*", // 查询字段
        "joins"  => null,   // 链接表
        "where"  => [],   // 查询条件
        "group"  => null, // 分组条件
        "having" => null,// having条件
        "order"  => null, // 排序条件
        "limit"  => null, // 数量
        "offset" => null,// 偏移
        "columns"  => [],// 插入或更新的字段
        "bindings" => [],// 绑定参数
    ];

    // 适配器
    protected $adapter;

    public function __construct($adapter)
    {
        $this->adapter = $adapter;
    }

    public function table($name, $prefix = "") {
        $this->_param['table'] = $prefix.$name;
    }

    public function select($fields) {
        if (is_array($fields)) {
            $this->_param['fields'] .= implode(",", $fields);
        } else {
            $this->_param['fields'] .= $fields;
        }
    }

    public function join($table, $condition, $type = self::JOIN_LEFT) {
        $this->_param['join'] .= " $type join $table on $condition";
    }

    // where 条件
    public function where(...$params) {
        $c = count($params);
        switch($c) {
            case 1:
                $p = $params[0];
                if (is_callable($p)) {
                    $p($this);
                } elseif (is_array($p)) {
                    $is_first = true;
                    foreach($p as $k => $v) {
                        if (!is_numeric($k)) {
                            array_unshift($v, $k);
                        }
                        call_user_func_array([$this, 'where'], $v);
                    }
                    if ($is_first) {
                        $is_first = false;
                    } else {
                        if (count($this->_param['where']) >= 2) {
                            $cu = array_pop($this->_param['where']);
                            $pre = array_pop($this->_param['where']);
                            $this->_param['where'][] = implode(" and ", [$pre, $cu]);
                        }
                    }
                } elseif (is_string($p)) {
                    $this->_param['where'][] = $p;
                }
                break;
            case 2:    // 第二位表示值
                $this->_param['where'][] = $params[0]." = ? ";
                break;
            case 3:    // 第2位表示操作，第三位表示值
                $this->_param['where'][] = $params[0]." " . $params[1]." ". $params[2];
                break;
            default:
                throw new CustomException("参数数目异常");
        }
        return true;
    }

    // orwhere 条件
    public function orWhere(...$params) {
        call_user_func_array([$this, 'where'], $params);
        if (count($this->_param['where']) >= 2) {
            $cu = array_pop($this->_param['where']);
            $pre = array_pop($this->_param['where']);
            $this->_param['where'][] = implode(" or ", [$cu, $pre]);
        }
    }

    // 原始 where 条件
    public function whereRaw($where, $bindings = []) {
        $this->_param['where'][] = $where;
        $this->_param['bindings'] = array_merge($this->_param['bindings'], $bindings);
    }

    // 分组
    public function group($group) {
        $this->_param['group'] = " GROUP BY " . $group;
    }

    // 排序
    public function order($column, $type = ""){
        if ($this->_param['order']) {
            $this->_param['order']  .= ",$column $type"; 
        } else {
            $this->_param['order'] = " ORDER BY $column $type";
        }
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
    }

    public function offset($offset){
        $this->_param['offset']  = " OFFSET $offset";
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