<?php

namespace Shaoxia\Controller;

use Shaoxia\Boot\Request;
use Shaoxia\Encrypt\DataHide;

class MyEncypt
{

    public function __construct(Request $r)
    {
        
    }
    
    public function dh_hide($str = "")
    {
        $dh = new DataHide();
        return $dh->s2c($str);
    }

    public function dh_show($str = "")
    {
        $dh = new DataHide();
        return $dh->c2s($str);
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
            $hide = $dh->c2fs($data);
            $show = $dh->fc2s($hide);
        }
        return ['hide' => $hide, 'show' => $show, 'data' => $data];
    }

    public function dh_try($str, Request $r)
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