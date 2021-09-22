<?php 

namespace Shaoxia\Storage;

use Shaoxia\Boot\Request;
use Shaoxia\Common\FileDB;

class Storage 
{
    public function db_test(Request $r)
    {
        try {
            $db = new FileDB(['db_path' => TMP_PATH]);
            $db->open('user2');
            
            // $db->insert([
            //     'name' => 'ctf1',
            //     'mobile' => 10111111,
            // ]);
            $db->insert([
                'name' => 'ctf2',
                'mobile' => 10333333,
            ]);
            $db->dumpNodeMap();
        } catch(\Throwable $t) {
            echo $t->getMessage();
        }
    }

}
