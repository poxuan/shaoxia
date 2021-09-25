<?php 

namespace Shaoxia\Decode;

use Shaoxia\Boot\Request;
use Shaoxia\Decode\Js\Js0x;
use Shaoxia\Decode\Js\JsCommon;

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
