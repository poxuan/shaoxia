<?php

namespace App\Algorithm\Sort;


class Quick implements Base {
    public function desc(){
        return "快速排序";
    }
    public function sort($array) {
        $len = count($array);
        $this->quick($array,0,$len - 1);
        return $array;
    }

    public function quick(&$array, $l, $h) {
        if ($l >= $h) {
            return;
        }
        $m = $this->_quick($array, $l, $h);
        // echo implode(',', $array)."\n";
        $this->quick($array, $l, $m-1);
        $this->quick($array, $m+1, $h);
        
    }

    public function _quick(&$array, $l, $h) {
        $p = $array[$l];
        while($l < $h) {
            if ($array[$h] > $p) {
                $h--;
            }
            $array[$l] = $array[$h];
            if($l < $h && $array[$l] <= $p){
                $l++;
            }
            $array[$h] = $array[$l];
        }
        $array[$l] = $p;
        return $l;
    }

    public function swap(&$array, $i, $j) {
        $t = $array[$i];
        $array[$i] = $array[$j];
        $array[$j] = $t;
    }
}