<?php

namespace App\Algorithm\Questions;

/**
 * 数独求解
 */
class Soduku {
    private $results = [];

    // 最多找几个结果
    private $max = 1;

    private $begin;

    public function __construct($max = 1) 
    {
        $this->max = $max;
        $this->clear();
    }

    /**
     * 清空初始结果
     */
    public function clear() {
        $n = 9;
        $board = [];
        for($i=0;$i<$n;$i++) {
            for($j=0;$j<$n;$j++) {
                $board[$i][$j] = '_';            
            }
        }
        $this->begin = $board;
    }

    /**
     * 处理问题
     */
    public function solve() 
    {
        $this->backtrack($this->begin, 0, 0);
        return $this->showAnswer();
    }

    /**
     * 初始化设置
     */
    public function init($i, $j, $v, $alert = true) 
    {
        $range = range(1,9);
        if (!in_array($i, $range) || !in_array($j, $range) || !in_array($v, $range)) {
            if ($alert) echo "初始值 $i, $j, $v 异常 均必须为 1-9 的整数 \n";
            return false;
        }
        if ($this->begin[$i-1][$j-1] != 0) {
            if ($alert) echo "位置 $i, $j 已有值 \n";
            return false;
        }
        if ($this->available($this->begin, $i-1, $j-1, $v)) {
            $this->begin[$i-1][$j-1] = $v;
            return true;
        } else {
            if ($alert) echo "位置 $i, $j 初始化 $v 异常\n";
            return false;
        }
        
    }

    /**
     * 随机初始化
     */
    public function rand($count = 10) 
    {
        if ($count < 0 || $count > 81) {
            return false;
        }
        do {
            $i = rand(1,9);
            $j = rand(1,9);
            $v = rand(1,9);
            $this->init($i, $j, $v, false) && $count--;
        } while($count);
    }

    /**
     * 显示随机一个结果
     */
    public function showAnswer() 
    {
        if (empty($this->results)) {
            echo "找不到答案\n";
            return;
        }
        $c = count($this->results);
        echo "共找到答案 $c 个,示例:\n";
        $result = $this->results[rand(0, $c - 1)];
        foreach($result as $level) 
            echo implode(' ', $level)."\n";
    }

    /**
     * 显示初始化结果
     */
    public function showBegin() 
    {
        echo "Begin:\n";
        foreach($this->begin as $level) 
            echo implode(' ', $level)."\n";
    }

    // 回溯主体方法
    function backtrack($board, $x, $y) 
    {
        if (count($this->results) >= $this->max) {
            return true;
        }
        if ($x >= 9) {
            $this->results[] = $board;
            return true;
        }

        if ($board[$x][$y] > 0) { // 此位置已有初始值。直接去下一位
            if ($y >= 8) {
                $res = $this->backtrack($board, $x+1, 0);
            } else {
                $res = $this->backtrack($board, $x, $y + 1);
            }
            return $res;
        }
        for($i = 1; $i <= 9; $i++) {
            if ($this->available($board, $x, $y, $i)) {
                $board[$x][$y] = $i;
                if ($y >= 8) {
                    $res = $this->backtrack($board, $x+1, 0);
                } else {
                    $res = $this->backtrack($board, $x, $y + 1);
                }
                // if ($res) return true; // 找到一个就结束
                $board[$x][$y] = '_';
            }
        }
        return false;
    }

    public function available($board, $x, $y, $p) 
    {
        if ($board[$x][$y] == $p) { // 已经初始化为此值
            return true;
        }
        if ($board[$x][$y] != 0) {
            return false; // 已经初始化填入
        }
        for($i = 0; $i< 9; $i++) {
            if ($board[$x][$i] == $p) {
                return false;
            }
            if ($board[$i][$y] == $p) {
                return false;
            }
        }

        // 所在九宫格
        $k = intval($x / 3) * 3;
        $t = intval($y / 3) * 3;
        for($i= $k; $i < $k+3; $i++) {
            for($j= $t; $j < $t+3; $j++) {
                if ($board[$i][$j] == $p) {
                    return false;
                }
            }
        }
        return true;
    }

}