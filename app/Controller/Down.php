<?php 

namespace App\Controller;

use App\Model\Tags;
use App\Model\TagValue;
use App\Service\ExcelService;
use Shaoxia\Contracts\Request;

class Down
{
    public function list()
    {
        $html = "<html><head><meta charset='utf-8'></head><body><ul>";
        $res = scandir("./down/");
        foreach($res as $page) {
            if (strlen($page) > 6 && is_dir("./down/".$page))
                $html .= "<li><a href='/down/view?name={$page}'>{$page}</a></li>";
        }
        $html .= "</ul></body><html>";
        return $html;
    }

    public function view($name) 
    {
        $html = "<html><head><meta charset='utf-8' /></head><body><div>";
        $res = scandir("./down/" . $name);
        for ($i = 0; $i < count($res) - 2; $i++) {
            $html .= "<image style='width:100%; margin: 0; padding:0;' src='/down/{$name}/{$i}.webp'/>";
        }
        $html .= "</div></body><html>";
        return $html;
    }
}
