<?php 

namespace App\Model;

use Shaoxia\Component\Model;

class Abc extends Model
{
    protected $table = 'abc';

    protected $hidden = ['status'];
}