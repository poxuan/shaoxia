<?php

namespace Shaoxia\Algorithm\Sort;

class Bubble implements Base {
    public function sort($array) {
        $len = count($array);
        for ($i=0;$i<$len - 1;$i++) {
            for ($j = 0;$j < $len - $i - 1;$j++) {
                if ($array[$j] > $array[$j+1]) {
                    $t = $array[$j];
                    $array[$j] =  $array[$j+1];
                    $array[$j+1] =  $t;
                }
            }
        }
        return $array;
    }
}