<?php

namespace Shaoxia\Adapter\Db;

use PDO;
use Shaoxia\Exceptions\CustomException;

class PdoMysql extends MyPdo{
    public function init($config) {
        $pdo = new PDO("mysql:dbname={$config['db']};host={$config['host']};port={$config['port']}", $config['user'], $config['pass']);
        return $pdo;
    }

    public static function parseSql($query) {
        switch ($query->type) {
            case Query::TYPE_SELECT: // 
                $where = $query->where;
                return "SELECT " . $query->fields . " FROM " . $query->table . $query->join 
                . ($where ? " where ". implode('and', $where) : '')
                . $query->group. $query->having . $query->order . $query->offset. $query->limit;
            case Query::TYPE_INSERT: // 暂时只支持插入一个数据
                $column = $value = "";
                foreach($query->columns as $c => $v) {
                    $column .= ",". self::quoteColumn($c);
                    $value  .= ",". self::quoteValue($v);
                }
                $column = substr($column, 1);
                $value  = substr($value, 1);
                return "INSERT into {$query->table} ($column) values ($value)";
            case Query::TYPE_UPDATE:
                $where = $query->where;
                $sets = [];
                foreach($query->columns as $c => $v) {
                    $sets[] = self::quoteColumn($c) ." = " .self::quoteValue($v);
                }
                $sets = implode(" and ",$sets);
                return "UPDATE {$query->table} {$query->join} set {$sets} " . ($where ? " where ". implode('and', $where) : '') . $query->limit;

            case Query::TYPE_DELETE:
                $where = $query->where;
                return "DELETE FROM {$query->table} {$query->join} " . ($where ? " where ". implode('and', $where) : '') . $query->limit;
        }
        throw new CustomException("未知SQL类型");
    }

    public static function quoteColumn($string)
    {
        return '`' . $string . '`';
    }

    public static function quoteValue($string)
    {
        return '\'' . str_replace(array('\'', '\\'), array('\'\'', '\\\\'), $string) . '\'';
    }

}