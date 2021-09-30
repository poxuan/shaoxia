<?php

class KMP {
    private $dp;
    private $pat;

    public function KMP($pat) {
        $this->pat = $pat;
        $M = strlen($pat);
        // dp[状态][字符] = 下个状态
        $dp = [];
        // base case
        $dp[0][$pat[0]] = 1;
        // 影子状态 X 初始为 0
        $X = 0;
        // 构建状态转移图（稍改的更紧凑了）
        for ($j = 1; $j < $M; $j++) {
            for ($c = 0; $c < 256; $c++)
                $dp[$j][$c] = $dp[$X][$c];
            $dp[$j][$pat[$j]] = $j + 1;
            // 更新影子状态
            $X = $dp[$X][$pat[$j]];
        }
        $this->dp = $dp;
    }

    public function search($txt) {
        $M = strlen($this->pat);
        $N = strlen($txt);
        // pat 的初始态为 0
        $j = 0;
        for ($i = 0; $i < $N; $i++) {
            // 计算 pat 的下一个状态
            $j = $this->dp[$j][$txt[$i]];
            // 到达终止态，返回结果
            if ($j == $M) return $i - $M + 1;
        }
        // 没到达终止态，匹配失败
        return -1;
    }
}