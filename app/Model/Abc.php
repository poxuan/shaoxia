<?php 

namespace App\Model;

use Shaoxia\Component\Model;

class Abc extends Model
{
    // 表名
    // protected $table = 'abc';

    // 设置表的别名
    protected $alias = "";

    protected $hidden = ['status'];
}