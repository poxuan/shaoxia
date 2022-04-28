<?php 

namespace App\Controller;

use App\Model\Abc;

class Model
{
    public function abc()
    {   
        // 单条件查询
        $res1 = Abc::query()->where('id',1)->get();
        $res2 = Abc::query()->where('id','>',1)->get();
        $res3 = Abc::query()->where('id','between',[2, 3])->get();
        $res4 = Abc::query()->where('id','in', [1,2,5])->get();
        $res5 = Abc::query()->where('id','<=',1)->get();
        $res6 = Abc::query()->where('id','>=',1)->get();
        $res7 = Abc::query()->where('id','<>',1)->get();
        $res8 = Abc::query()->where('id','!=',1)->get();
        $res9 = Abc::query()->where('name', 'like', '张%')->get();
        $res9 = Abc::query()->where('name', 'not like', '张%')->get();
        $res10 = Abc::query()->where('name', 'is not', 'null')->get();
        $res11 = Abc::query()->where('name', 'is', 'null')->get();
        // 组合条件查询
        $res12 = Abc::query()->where('id','between',[2, 3])->orWhere('name', 'like', '张%')->first();
        $res13 = Abc::query()->where([['id','between',[2, 3]],['name', 'like', '张%']])->toSql();
        $res14 = Abc::query()->where(['id'=> ['in',[2, 3]],'name'=> ['like', '张%']])->toSql();
        $res15 = Abc::query()->where(['id'=> 3,'name'=> ['like', '张%']])->get();
        return $res12;
    }
}
