<?php 

namespace Shaoxia\Storage;

use Shaoxia\Boot\Request;
use Shaoxia\Common\BtreeDB;
use Shaoxia\Common\CuteDB;
use Shaoxia\Common\JsonDB;

class Filedb 
{
    public function bdb_insert(Request $r)
    {
        try {
            $s = microtime(true);
            $db = new BtreeDB(STORAGE_PATH);
            $db->open('buser');
            $i = 100000;
            do {
                $db->insert([
                    'name' => 'ctf_'. rand(1,10000),
                    'mobile' => 185 . str_pad(rand(1,99999999), 8, 0 ,STR_PAD_LEFT),
                ]);
            } while($i --);
            $e = microtime(true);
            var_dump($e-$s);
            // 存储 1.47 秒
        } catch(\Throwable $t) {
            echo $t->getMessage();
        }
    }

    public function cdb_insert(Request $r)
    {
        try {
            $s = microtime(true);
            $db = new CuteDB();
            $db->open(STORAGE_PATH .'cuser');
            $i = 1;
            do {
                $db->set($i, serialize([
                    'id' => $i,
                    'name' => 'ctf_'. rand(1,10000),
                    'mobile' => 185 . str_pad(rand(1,99999999), 8, 0 ,STR_PAD_LEFT),
                ]));
                $i++;
            } while($i <= 100000);
            $e = microtime(true);
            var_dump($e-$s);
            // 存储 3.45 秒
        } catch(\Throwable $t) {
            echo $t->getMessage();
        }
    }

    public function jdb_insert(Request $r)
    {
        try {
            $s = microtime(true);
            $db = new JsonDB('juser', STORAGE_PATH);
            $i = 1;
            do {
                $db->add([[
                    'id' => $i,
                    'name' => 'ctf_'. rand(1,10000),
                    'mobile' => 185 . str_pad(rand(1,99999999), 8, 0 ,STR_PAD_LEFT),
                ]]);
                $i++;
            } while($i <= 100000);
            $e = microtime(true);
            var_dump($e-$s);
            // 存储 7.86 秒
        } catch(\Throwable $t) {
            echo $t->getMessage();
        }
    }

    public function bdb_find(Request $r)
    {
        try {
            $s = microtime(true);
            $db = new BtreeDB(STORAGE_PATH);
            $db->open('buser');
            $e = microtime(true);
            var_dump($db->where('id', 'between', [20,1002])->where('name','like','999')->orWhere('mobile','like','999')->select('id,name as nickname,mobile as phone'));
            $e = microtime(true);
            var_dump($e-$s);
            // 读取 0.958 秒
        } catch(\Throwable $t) {
            echo $t->getMessage();
        }
    }

    public function cdb_find(Request $r)
    {
        try {
            $s = microtime(true);
            $db = new CuteDB();
            $db->open(STORAGE_PATH .'cuser');
            var_dump($db->get(rand(1,10000)));
            $e = microtime(true);
            var_dump($e-$s);
            // 读取 0.001 秒
        } catch(\Throwable $t) {
            echo $t->getMessage();
        }
    }

    public function jdb_find(Request $r)
    {
        try {
            $s = microtime(true);
            $db = new JsonDB('juser', STORAGE_PATH);
            var_dump($db->find(['id' => rand(1,10000)]));
            $e = microtime(true);
            var_dump($e-$s);
            // 读取 0.013 秒
        } catch(\Throwable $t) {
            echo $t->getMessage();
        }
    }
}
