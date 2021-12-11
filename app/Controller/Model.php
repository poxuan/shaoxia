<?php 

namespace App\Controller;

use \App\Media\Image\Image;
use App\Model\Abc;

class Model
{
    public function abc()
    {   
        return Abc::query()->where('id','>',1)->toSql();
    }
}
