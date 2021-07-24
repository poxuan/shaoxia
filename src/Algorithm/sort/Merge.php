<?php

namespace Shaoxia\Algorithm\Sort;


class Merge implements Base {

    public function desc(){
        return "归并排序";
    }

    public function sort($array) {
        $len = count($array);
        $this->merge($array,0,$len - 1);
        return $array;
    }

    public function merge(&$array, $l, $h) {
        if ($l >= $h) {
            return;
        }
        $m = intval(($h + $l) / 2);
        $this->merge($array, $l, $m);
        $this->merge($array, $m+1, $h);
        $this->_merge($array, $l, $m, $h);
    }

    public function _merge(&$array, $l, $m, $h) {
        $p = [];
        $i = $k = $l;
        $j = $m+1;
        for(; $k <= $h; ++$k) {
            if ($i <= $m && ($j > $h || $array[$j] >= $array[$i])) {
                $p[$k] = $array[$i];
                ++$i;
            } else {
                $p[$k] = $array[$j];
                ++$j;
            }
        }
        for($i = $l ;$i <= $h; ++$i) {
            $array[$i] = $p[$i];
        }
    }

    public function swap(&$array, $i, $j) {
        $t = $array[$i];
        $array[$i] = $array[$j];
        $array[$j] = $t;
    }
}