<?php

// 回溯
class recall{

    // 结果
    protected $result = [];

    // 回溯主体方法
    function backtrack($path, $list) {
        if ($this->isOver($path)) {
            $this->result[] = $path;
            return;
        }

        foreach($list as $item) {
            if ($this->available($path, $item)) {
                $this->push($path, $item);
                $this->backtrack($path, $list);
                $this->pop($path);
            }
        }
    }

    // 结束了
    function isOver($path) {
        return true;
    }

    function available($path, $item){
        return true;
    }

    function push($path, $item) {
        return true;
    }

    function pop($path) {
        return true;
    }
}