<?php

function dynamic() {
// # 初始化 base case
// dp[0][0][...] = base
// # 进行状态转移
// for 状态1 in 状态1的所有取值：
//     for 状态2 in 状态2的所有取值：
//         for ...
//             dp[状态1][状态2][...] = 求最值(选择1，选择2...)
}




// 广度优先搜索
function bfs($start, $end) {
    $q = [];
    $visited = [];
    array_push($q, $start);
    array_push($visited, $start);
    $step = 0;
    while($q) {
        $size = count($q);
        for ($i = 0; $i<$size; $i++) {
            $cur = array_pop($q);
            if ($cur == $end) {
                return $step;
            }

            foreach($cur->firends() as $x) {
                if (!in_array($x, $visited)) {
                    array_push($q, $x);
                    array_push($visited, $x);
                }
            }
        }
        $step ++;
    }
}

// 二分查找
function binarySerrch($arr, $target) {
    $left = 0; $right = count($arr) - 1; // count($arr);

    while ($left <= $right) { // <
        $mid = $left + intval(($right - $left) / 2);
        if ($arr[$mid] == $target) {

        } else if ($arr[$mid] < $target) {
            $left = $mid + 1;
        } else if ($arr[$mid] > $target) {
            $right = $mid - 1;
        }
    }

    return -1;
}


function slideWindow($s, $n) {
    $left = $right = 0;

    while($right < strlen($s)) {
        
    }
}