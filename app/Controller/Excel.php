<?php 

namespace App\Controller;

use App\Model\Abc;
use App\Model\Tags;
use App\Service\ExcelService;

class Excel
{
    public function import(string $file)
    {   
        $excel = new ExcelService("Xls", 1000);
        $data = $excel->getAllSheets($file, 2);
        foreach($data as $k => $item) {
            $line = $k + 1;
            foreach($item as $item) {
                Tags::query()->create([
                    "Name" => $item[1],
                    "Tag" => $item[2],
                    "line" => $line
                ]);            
            }
        }
        return "";
    }
}
