<?php 

namespace App\Controller;

use App\Model\Tags;
use App\Model\TagValue;
use App\Service\ExcelService;
use Shaoxia\Contracts\Request;

class Tag
{
    public function get()
    {   
        $res = Tags::query()->group("Line")->select("Line, group_concat(Tag) as Tags")->get()->toArray();
        
        foreach ($res as $item) {
            $data = file_get_contents("http://127.0.0.1:30925/getRealTimeValue?tagNames=" . $item["Tags"]);
            $json = json_decode($data, true);
            if ($json['data']) {
                TagValue::query()->create([
                    "Line" => $item["Line"],
                    "Value" => json_encode($json['data']),
                    "Timestamp" => time(),
                ]);
            }
        }
        return $res;
    }

    public function export($line, Request $request) {
        $start = $request->get("start", 0);
        $end = $request->get("end", 0);
        $res = TagValue::query()->where("Line", $line)
        ->when($start > 0, function($q) use ($start) {
            $q->where("Timestamp", ">=", $start);
        })
        ->when($end > 0, function($q) use ($end) {
            $q->where("Timestamp", "<=", $end);
        })->get()->toArray();
        $data = [];
        foreach($res as $item) {
            $d = json_decode($item["Value"], true);
            $d["Date"] = date("Y-m-d H:i:00", $d["Timestamp"]);
            $data[] = $d;
        }
        $columns = Tags::query()->where("Line", $line)->get()->toArray();
        $columns = array_column($columns, "Name", "Tag");
        $columns = array_merge(["Date" => "时间"], $columns);
        $excel = new ExcelService("Xls", 1000);
        var_dump($columns);
        $file = "Export_" . time() . ".xls";

        $excel->export($file, $data, $columns, ExcelService::OUT_FILE);
        return $file;
    }
}
