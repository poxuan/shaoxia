<?php

namespace Shaoxia\Controller;

use Shaoxia\Encrypt\DataHide;

class MyEncypt
{
    
    public function dh_hide($str = "")
    {
        $dh = new DataHide();
        return $dh->hideStr($str);
    }

    public function dh_show($str = "")
    {
        $dh = new DataHide();
        return $dh->showStr($str);
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
            $hide = $dh->hideStr($data);
            $show = $dh->showStr($hide);
        }
        return ['hide' => $hide, 'show' => $show, 'data' => $data];
    }

    public function dh_try($str, $raw = '')
    {
        $s = microtime(true);
        for($i=1;$i<0xffffff;$i++) {
            $dh = new DataHide($i);
            $res = $dh->showStr($str);
            if ($res) {
                if (empty($raw) || $raw == $res)
                    echo "$i:$res \n";
            }
        }
        $e = microtime(true);
        echo "time:" . ($e-$s);
    }
}