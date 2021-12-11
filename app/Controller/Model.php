<?php 

namespace App\Controller;

use \App\Media\Image\Image;
use App\Model\Abc;

class Model
{
    public function abc()
    {   
        $res = Abc::query()->where('id','>',1)->get();
        return $res;
    }
}
