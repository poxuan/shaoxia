<?php

namespace Module\Algorithm\Sort;


class Selection implements Base {
    public function desc(){
        return "选择排序";
    }
    public function sort($array) {
        $len = count($array);
        for ($i=0;$i < $len - 1;++$i) {
            $index = $i;
            for ($j = $i;$j < $len;++$j) {
                if ($array[$j] < $array[$index]) {
                    $index = $j;
                }
            }
            $this->swap($array, $i, $index);
        }
        return $array;
    }

    public function swap(&$array, $i, $j) {
        $t = $array[$i];
        $array[$i] = $array[$j];
        $array[$j] = $t;
    }
}