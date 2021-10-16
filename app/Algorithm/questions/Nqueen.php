<?php

namespace App\Algorithm\Questions;

/**
 * N皇后问题
 */
class Nqueen {
    private $results = [];

    private $n;

    public function __construct($n = 6) 
    {
        $this->n = $n;
    }

    public function solve() 
    {
        $board = $this->init();
        $this->backtrack($board, 0);
        return $this->showAnswer();
    }

    public function init() 
    {
        $board = [];
        for($i=0;$i<$this->n;$i++) {
            for($j=0;$j<$this->n;$j++) {
                $board[$i][$j] = '.';            
            }
        }
        return $board;
    }

    public function showAnswer() 
    {
        if (empty($this->results)) {
            echo "找不到答案\n";
            return;
        }
        foreach($this->results as $key => $result) {
            echo "Answer $key:\n";
            foreach($result as $level) 
                echo implode(' ', $level)."\n";
        }
    }

    // 回溯主体方法
    function backtrack($board, $level) 
    {
        if ($level >= $this->n) {
            $this->results[] = $board;
            return;
        }

        for($i = 0; $i< $this->n; $i++) {
            if ($this->available($board, $level, $i)) {
                $board[$level][$i] = 'Q';
                $this->backtrack($board, $level + 1);
                $board[$level][$i] = '.';
            }
        }
    }

    public function available($board, $level, $p) 
    {
        for($i = 0; $i < $level; $i++) {
            $q = $level - $i;
            if (isset($board[$i][$p]) && $board[$i][$p] == 'Q') {
                return false;
            }
            if (isset($board[$i][$p - $q]) && $board[$i][$p - $q] == 'Q') {
                return false;
            }
            if (isset($board[$i][$p + $q]) && $board[$i][$p + $q] == 'Q') {
                return false;
            }
        }

        return true;
    }

}