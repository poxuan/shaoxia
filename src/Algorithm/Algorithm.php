<?php 

namespace Shaoxia\Algorithm;

use Shaoxia\Boot\Request;

class Algorithm 
{
    public function sort(Request $r)
    {
        $type = 'Shaoxia\Algorithm\Sort\\'.$r->get('type', 'Bubble');
        if (!class_exists($type)) {
            throw new \Exception('sort type not exist!');
        }
        $arr = $r->get('arr', '');
        if (empty($arr)) {
            $rand = $r->get('rand', 1000);
            do {
                $arr[] = rand(1, 10000);
            } while (--$rand);
        }
        $loop = $r->get('loop', 1);
        $s = microtime(true);
        do {
        $res = (new $type)->sort( is_array($arr) ? $arr : explode(',', $arr));
        } while(--$loop);
        $e = microtime(true);
        return ['res' => implode(',',$res),'sec' => $e-$s];
        
    }
}
