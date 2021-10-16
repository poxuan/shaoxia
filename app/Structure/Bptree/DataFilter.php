<?php

namespace App\Structure\Bptree;

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
                if ($key == '_logic') {
                    $rule['_logic'] = $val;
                } elseif (is_numeric($key) && is_array($val)) {
                    array_unshift($val, 'rule');
                    $rule[] = $val;
                } else {
                    $rule[] = ['rule', $key , '=', $val];
                }
            }
        } else {
            $rule = ['rule', $column, $operation , $data];
        }
        if ($scope == 'or') { // or 只和前一个（组）条件绑定
            $pop = array_pop($this->filter);
            $rule = [$pop, $rule, '_logic' => 'or'];
        }
        $this->filter[] = $rule;
    }

    public function filter($data, $columns = '*') {
        foreach($data as $key => $item) {
            $item = $item->toArray();
            if (!$this->check($item, $this->filter)){
                unset($data[$key]);
            } else {
                $data[$key] = $this->columns($item, $columns);
            }
        }
        return array_values($data);
    }

    public function columns($item, $columns) {
        if ($columns == '*') {
            return $item;
        }
        if (is_string($columns)) {
            $columns = array_map('trim', explode(',', $columns));
        }
        $res = [];
        foreach($columns as $column) {
            $c = explode(" as ", $column);
            $res[$c[1] ?? $c[0]] = $item[$c[0]] ?? null;
        }
        return $res;
    }

    public function check($item, $rules = null) {
        $rules = $rules ?: $this->filter;
        if (empty($rules)) {
            return true;
        }
        
        $logic = strtolower($rules['_logic'] ?? 'and');
        unset($rules['_logic']);
        
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
                $res = $res && $this->check($item, $rule);
            }
        } else {
            $res = false;
            foreach($rules as $rule) {
                $res = $res || $this->check($item, $rule);
            }
        }
        // var_dump($item, $logic, $rules, $res);
        return $res;
    }
}
