<?php

namespace Module\Algorithm\Sort;


class Insertion implements Base {

    public function desc(){
        return "插入排序";
    }

    public function sort($array) {
        $len = count($array);
        for ($i=1;$i<$len;++$i) {
            $n = $array[$i];
            for ($j = $i-1;$j >= 0 && $array[$j] > $n;--$j) {
                $array[$j + 1] = $array[$j]; 
            }
            $array[$j + 1] = $n;
        }
        return $array;
    }
}