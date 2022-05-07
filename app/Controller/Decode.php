<?php 

namespace App\Controller;

use Shaoxia\Contracts\Request;
use Module\Decode\Js\Js0x;
use Module\Decode\Js\JsCommon;

class Decode 
{
    public function js0x(Request $r)
    {
        $content = file_get_contents($r->get("path"));
        $js0x = new Js0x($content);
        $fileName = './tmp/js0x-'.time().'.js';
        @file_put_contents($fileName, $js0x->resolve());
        return ['savePath' => $fileName];
    }

    public function jsc(Request $r)
    {
        $content = file_get_contents($r->get("path"));
        $js0x = new JsCommon($content);
        $fileName = './tmp/jsc-'.time().'.js';
        @file_put_contents($fileName, $js0x->resolve());
        return ['savePath' => $fileName];
    }
}
