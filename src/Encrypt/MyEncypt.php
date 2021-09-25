<?php

namespace Shaoxia\Encrypt;


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

    public function dh_test($length)
    {
        $dh = new DataHide();
        $data = [];
        for ($i=0; $i<$length; $i++) {
            $data[] = $dh->randerStr(5);
        }
        $str = $dh->base64_encode(json_encode($data));
        $hide = $dh->hideStr($str);
        $show = $dh->showStr($hide);
        $res = json_decode($dh->base64_decode($show));
        return ['str' => $str, 'hide' => $hide, 'show' => $show, 'data' => $res, 'res' => $res];
    }
}