<?php 

namespace Shaoxia\Controller;

use Shaoxia\Algorithm\Questions\Floordrop;
use Shaoxia\Algorithm\Questions\Nqueen;
use Shaoxia\Algorithm\Questions\Soduku;
use Shaoxia\Algorithm\Sort\Bubble;
use Shaoxia\Algorithm\Sort\Insertion;
use Shaoxia\Algorithm\Sort\Merge;
use Shaoxia\Algorithm\Sort\Quick;
use Shaoxia\Algorithm\Sort\Selection;
use Shaoxia\Boot\Request;

class Algorithm 
{
    protected $types = [
        Bubble::class,
        Insertion::class,
        Merge::class,
        Quick::class,
        Selection::class,
    ];

    // 排序
    public function sort(Request $r)
    {
        $arr = $r->get('arr', []);
        if (empty($arr)) {
            $arr = [];
            $rand = $r->get('rand', 1000);
            do {
                $arr[] = rand(1, 10000);
            } while (--$rand);
        }
        $res = [];
        foreach ($this->types as $type) {
            $algo = new $type;
            $s = microtime(true);
            $sorted = $algo->sort($arr);
            $e = microtime(true);
            $res[] = [
                'desc' => $algo->desc(),
                'res'  => implode(',', $sorted),
                'sec'  => $e-$s
            ];
        }
        return ['arr' => implode(',',$arr), 'res' => $res];
        
    }

    // N皇后
    public function nqueen($n) {
        $nqueen = new Nqueen($n);
        $nqueen->solve();
    }

    // 数独填充
    public function sudoku(Request $r) {
        $sudoke = new Soduku();
        $init = $r->get('init', null);
        if ($init) { // 指定初始化
            $ks = array_filter(explode(';', $init));
            foreach ($ks as $k) {
                $p = explode(',', $k);
                $sudoke->init(...$p);
            }
        } else { // 随机初始化
            $rand = $r->get('rand', 10);
            $sudoke->rand($rand);
            $sudoke->showBegin($rand);
        }
        $sudoke->solve();
    }

    // 楼层掉落
    public function floordrop($e, $f) {
        $c = new Floordrop();
        $res = $c->drop($e, $f);
        $c->step($e, $f);
        return $res;
    }
}