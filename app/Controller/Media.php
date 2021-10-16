<?php 

namespace App\Controller;

use \App\Media\Image\Image;

class Media 
{
    public function image(int $angle = 0)
    {
        $start = microtime(true);
        $image = new Image();
        $longTest1  = "马化腾 坚持阅读【外刊阅读】12天\n正在和742526人一起学习";
        $longTest2 = "Chinese State Councilor and Foreign Minister Wang Yi";
        $longTest3 = "与需求结果不一致，但基本完成需求所需模块和扩展要求。倾斜文字加下划线错误";
        $special   = [
            '12' => [
                'type' => 'color',
                'color' => '#f00',
            ],
            '742526' => [
                'type' => 'color',
                'color' => '#f00',
            ],
            'Councilor' => [
                'type' => 'underline',
                'color' => '#ff0',
                'width' => '4',
            ],
        ];
        $steps = [
            ['whiteboard',750,1700],
            ['addPic','./static/img/pic2.jpg',0,0,750,500],
            ['addLayer',50,430,700,570,20,Image::COLOR_WHITE,['offset_x' => 1, 'offset_y' => 1,'radius' => 3]],
            ['addCriclePic','./static/img/pic1.jpg',130,500,50],
            ['addLongText',$longTest1, Image::px2pound(28), 200, 480, 450, 50, "", "#666", $angle, 'pingfang-slim',$special],
            ['addLongText',$longTest2, 38, 40, 720, 700, 80, " ", "#000", $angle, 'pingfang-standard', $special],
            ['addText',"商业|亚马逊：《霸王硬上弓》", 26, 40 , 900, "#000", $angle ,'pingfang-standard'],
            ['addLayer',40,950,200,956,1,"#aaa"],
            ['addLongText',$longTest3, 26, 40, 1050, 700, 60, "", "#444", $angle,'pingfang-slim', $special],
            ['addGradientLayer',50,1300,700,1600,20,Image::COLOR_RED,Image::COLOR_BLACK],
            ['addText',"长按扫码，关注【xxx】", 26, 80 , 1400, "#fff", $angle ,'pingfang-slim'],
            ['addText',"生成一张分享图片。", 32, 80 , 1466, "#fff", $angle ,'pingfang-standard'],
            ['addLayer',80,1500,410,1536,18,Image::COLOR_WHITE],
            ['addText',"傻逼需求。。。", 20, 120 , 1530, Image::COLOR_RED, $angle ,'pingfang-slim'],
            ['addPic','./static/img/wechat.jpg',460,1340,220,220],

            ['addRotateLayer',300,1500,200,100,45,18,Image::COLOR_RED],
        ];
        $image->make($steps);
        
        $end = microtime(true);
        $filename = 'test-'.time().'.jpg';
        $path = $image->saveImageLocal($filename, 'png');
        return ['path' => $path,'time_cost' => intval(($end - $start) * 1000)." ms", 'mem_cost' => round(memory_get_usage() / 1024,2)." KB"];
    }

    public function create()
    {
        $steps = $_POST['steps'] ?? [];
        $image = new Image();
        $image->make($steps);
        $filename = 'test-'.time().'.jpg';
        $path = $image->saveImageLocal($filename,'jpeg');
        if ($path) {
            echo '<a href="/tmp/'.$filename.'">图片</a>'; //存入本地
        } else {
            echo '图片生成失败'; //存入本地
        }
    }
}
