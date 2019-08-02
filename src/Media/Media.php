<?php 

namespace Shaoxia\Media;

use \Shaoxia\Media\Image\Image;

class Media 
{
    public function image(int $angle = 0)
    {
        $argv  = func_get_args();
        $start = microtime(true);
        $image = new Image();
        $pound = Image::px2pound(28); // px 长度转 pound 长度，用于字体
        $longTest  = "陈腾飞 坚持阅读【外刊阅读】12天\n正在和742526人一起学习";
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
            ['addLayerShadow',50,430,700,570,20,[1,1]],
            ['addPic','./static/img/pic2.jpg',0,0,750,500],
            ['addLayer',50,430,700,570,20,Image::COLOR_WHITE],
            ['addCriclePic','./static/img/pic1.jpg',130,500,50],
            ['addLongText',$longTest, Image::px2pound(28), 200, 480, 450, 50, "", "#666", $angle, $special,'pingfang-slim'],
            ['addLongText',$longTest2, 38, 40, 720, 700, 80, " ", "#000", $angle, $special],
            ['addText',"商业|亚马逊：《霸王硬上弓》", 26, 40 , 900, "#000", $angle ,'pingfang-standard'],
            ['addLayer',40,950,200,956,1,"#aaa"],
            ['addLongText',$longTest3, 26, 40, 1050, 700, 60, "", "#444", $angle, $special,'pingfang-slim'],
            ['addGradientLayer',50,1300,700,1600,20,Image::COLOR_RED,Image::COLOR_BLACK],
            ['addText',"长按扫码，关注【xxx】", 26, 80 , 1400, "#fff", $angle ,'pingfang-slim'],
            ['addText',"生成一张分享图片。", 32, 80 , 1466, "#fff", $angle ,'pingfang-standard'],
            ['addLayer',80,1500,410,1546,23,Image::COLOR_WHITE],
            ['addText',"傻逼需求。。。", 26, 120 , 1535, Image::COLOR_RED, $angle ,'pingfang-slim'],
            ['addPic','./static/img/wechat.jpg',460,1340,220,220],
        ];
        $image->make($steps);
        // $image->whiteboard(750,1700) // 生成 750*1700 的白板
        //     ->addLayerShadow(50,430,700,570,20,[1,1]) // 添加一个圆角浮层,带阴影
        //     ->addPic('./static/img/pic2.jpg',0,0,750,500) // 在 左上角 添加 750*500 的图片
        //     ->addLayer(50,430,700,570,20,Image::COLOR_WHITE) // 添加一个圆角浮层,带阴影
        //     ->addCriclePic('./static/img/pic1.jpg',130,500,50) // 添加圆图
        //     ->addLongText($longTest, Image::px2pound(28), 200, 480, 450, 50, "", "#666", $angle, $special,'pingfang-slim') // 添加长段文字
        //     ->addLongText($longTest2, 38, 40, 720, 700, 80, " ", "#000", $angle, $special)
        //     ->addText("商业|亚马逊：《霸王硬上弓》", 26, 40 , 900, "#000", $angle ,'pingfang-standard')
        //     ->addLayer(40,950,200,956,1,"#aaa")
        //     ->addLongText($longTest3, 26, 40, 1050, 700, 60, "", "#444", $angle, $special,'pingfang-slim')
        //     ->addGradientLayer(50,1300,700,1600,20,Image::COLOR_RED,Image::COLOR_BLACK) // 添加一个圆角浮层,带阴影
        //     ->addText("长按扫码，关注【xxx】", 26, 80 , 1400, "#fff", $angle ,'pingfang-slim')
        //     ->addText("生成一张分享图片。", 32, 80 , 1466, "#fff", $angle ,'pingfang-standard')
        //     ->addLayer(80,1500,410,1546,23,Image::COLOR_WHITE)
        //     ->addText("傻逼需求。。。", 26, 120 , 1535, Image::COLOR_RED, $angle ,'pingfang-slim')
        //     ->addPic('./static/img/wechat.jpg',460,1340,220,220);
        $end = microtime(true);
        $filename = 'test-'.time().'.jpg';
        $path = $image->saveImageLocal($filename,'jpeg');
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
