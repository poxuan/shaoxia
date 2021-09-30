<?php

namespace Shaoxia\Algorithm\Questions;

/**
 * 楼层掉落问题
 */
class Floordrop
{
    private $dp = [];
    public $step = [];

    public function drop(int $eggs, int $floors, $step = 1) {
        if ($eggs == 1) {
            return $floors;
        }
        if ($floors == 0) {
            return 0;
        }
        if (isset($this->dp[$floors][$eggs])) {
            return $this->dp[$floors][$eggs];
        }

        $res = $floors + 1;
        $fall = [];
        for ($i = 1; $i <= $floors; $i++) {
            $res2 = max(
                $this->drop($eggs - 1, $i - 1, $step + 1), // 碎了往下找
                $this->drop($eggs, $floors - $i, $step + 1) // 没碎向上找
            ) + 1;
            if($res > $res2) {
                $fall = [$i];
            }elseif($res == $res2) {
                $fall[] = $i;
            }
            $res = min($res, $res2);            
        }
        $this->step[$floors][$eggs] = [ // 剩余
            'fs' => $fall,   // 可选层最优数组
            'l' => $res,     // 剩余步数
        ];
        $this->dp[$floors][$eggs] = $res;
        return $res;
    }

    // 输出决策树
    public function step($eggs, $floors, $step = 1, $base = 0, $type = 0) {
        if ($floors == 0) {
            $this->output($step, $base, $base, $base, $type + 2);
            return;
        }
        if ($eggs == 1 && $floors > 0) {
            $f = 1;
        } else {
            $t = $this->step[$floors][$eggs] ?? '';
            if (empty($t) || empty($floors)) {
                return ;
            }
            $fs = $t['fs'];
            $f = $fs[array_rand($fs)];
        }
        $this->output($step, $base, $base+ $floors, $base + $f, $type);
        $this->step($eggs - 1, $f - 1, $step + 1, $base, 1);
        $this->step($eggs, $floors - $f, $step + 1, $base + $f, 2);
    }

    public function output($step, $s, $e, $o, $type) {
        $res = "";
        for($i = 1; $i< $step;$i++) {
            $res .= "    "; 
        }
        if ($type >= 3) {
            $res .= $type == 3 ? '碎了' : '没碎';
            echo $res .= " 得出结果 $s\n";
        } else {
            $res .= $type == 1 ? '碎了' : ($type == 2 ? '没碎' : '开始');
            $res .= " 第{$step}步";
            $res .= " 区间 [$s,$e] 在 $o 处 抛下\n";
            echo $res;
        }
    }
}