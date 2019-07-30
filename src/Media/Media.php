<?php 

namespace Shaoxia\Media;

use \Shaoxia\Media\Image\Image;

class Media 
{
    public function image()
    {
        $argv = func_get_args();
        $angle = $argv[0] ?? 0;     // 偏转角度
        $color = $argv[1] ?? '000'; // 字体颜色
        $start = microtime(true);
        $image = new Image();
        $pound = Image::px2pound(36); // px 长度转 pound 长度，用于字体
        $longTest = "PingWest品玩7月30日讯，索尼公司今天发布了截至6月30日的2019财年第一季度财报。\n财报显示，索尼第一财季营收为1.9257万亿日元(约合177.30亿美元)，较上年同期的1.9536万亿日元下降1%；\n归属于索尼股东的净利润为1521亿日元(约合14.00亿美元)，较上年同期的2264亿日元下降33%。";
        $special  = [
            '1.9257' => [
                'type' => 'color',
                'color' => '#f00',
            ],
            'PingWest' => [
                'type' => 'underline',
                'color' => '#f00',
                'width' => '4',
            ],

        ];
        $image->whiteboard(750,2100) // 生成 750*2100 的白板
            ->addPic('./static/img/pic2.jpg',0,0,750,500) // 在 左上角 添加 750*500 的图片
            ->addLayer(47,447,703,1153,33,'#fcfcfc') // 添加一个圆角浮层作为阴影
            ->addLayer(48,448,702,1152,32,'#f8f8f8') // 添加一个圆角浮层作为阴影
            ->addLayer(49,449,701,1151,31,'#eee') // 添加一个圆角浮层作为阴影
            ->addLayer(50,450,700,1150,30) // 添加一个圆角浮层
            ->addText("哈哈",$pound,50,300, $color) // 添加两个字
            ->addLongText($longTest, $pound, 75, 520, 600, 45, "", $color, $angle, $special) // 添加长段文字
            ->addCriclePic('./static/img/pic1.jpg',375,375,100); // 添加圆图
        $end = microtime(true);
        echo $image->saveImageLocal('test-'.time().'.jpg','jpeg'); //存入本地
        $end = microtime(true);
        echo 'time cost:' . ($end - $start) . '<br />';
        echo 'mem  cost:' . memory_get_usage().'<br />';
    }
}
