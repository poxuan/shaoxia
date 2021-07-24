<?php 

namespace Shaoxia\Algorithm;

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
}
