<?php

namespace App\Controller;

use Shaoxia\Contracts\Request;
use App\Encrypt\DataHide;

class MyEncypt
{

    public function __construct(Request $r)
    {
        
    }
    
    public function dh_hide($str = "",$type='s2c')
    {
        $dh = new DataHide();
        return $dh->$type($str);
    }

    public function dh_show($str = "",$type='c2s')
    {
        $dh = new DataHide();
        return $dh->$type($str);
    }

    // 比较
    public function dh_comp($str = "",$times=100)
    {
        
        $s = microtime(true);
        $dh = new DataHide();
        $arr = [];
        for( $i=0; $i< $times; $i++ ) {
            $arr[] = $dh->s2r($str)."\n";
        }
        $e = microtime(true);
        echo count(array_unique($arr));
        $s2 = microtime(true);
        for($i=0; $i< $times; $i++ ) {
            md5($str)."\n";
        }
        $e2 = microtime(true);
        return [
            's2c' => $e- $s,
            'md5' => $e2- $s2,
        ];
    }

    public function dh_test($length,$type='array')
    {
        $dh = new DataHide();
        if ($type == 'array') {
            $data = [];
            for ($i=0; $i<$length; $i++) {
                $data[] = $dh->randerStr(5);
            }
            $hide = $dh->hideData($data);
            $show = $dh->showData($hide);
        } else {
            $data = $dh->randerStr($length);
            $hide = $dh->s2r($data);
            $show = $dh->r2s($hide);
        }
        return ['hide' => $hide, 'show' => $show, 'data' => $data];
    }

    public function dh_try_decode($str, Request $r)
    {
        $s = microtime(true);
        $raw = $r->get('raw');
        $start = $r->get('start', 0x3f000000);
        $end = $r->get('end', $start + 0xff);
        for($i=$start;$i<$end;$i++) {
            $dh = new DataHide($i);
            $res = $dh->c2s($str);
            if ($res) {
                if (empty($raw) || $raw == $res)
                    echo "$i:$res \n";
            }
        }
        $e = microtime(true);
        echo "time:" . ($e-$s);
    }
}