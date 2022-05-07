<?php

namespace Module\Media\Traits;

/**
 * 特殊处理
 * @package Common\Util
 */
trait ImageSpecial
{
    /**
     * 特殊字符加颜色
     *
     * @param string|int $word
     * @param string|int $params
     * @param string|int $cur_x
     * @param string|int $cur_y
     * @param string|int $size
     * @param string|int $angle
     * @param string|int $color
     * @param string|int $fontfile
     * @param string|int $box
     * @return void
     * @author chentengfei
     * @since
     */
    public function specialColor($word, $params, $cur_x, $cur_y, $size, $angle, $color, $fontfile)
    {
        $color2 = $this->getColor($params['color']);
        imagefttext($this->image, $size, $angle, $cur_x, $cur_y, $color2, $fontfile, $word);
    }

    /**
     * 特殊字符换字体
     *
     * @param string|int $word
     * @param string|int $params
     * @param string|int $cur_x
     * @param string|int $cur_y
     * @param string|int $size
     * @param string|int $angle
     * @param string|int $color
     * @param string|int $fontfile
     * @param string|int $box
     * @return void
     * @author chentengfei
     * @since
     */
    public function specialFont($word, $params, $cur_x, $cur_y, $size, $angle, $color, $fontfile)
    {
        $fontfile2 = $this->getFont($params['font']);
        imagefttext($this->image, $size, $angle, $cur_x, $cur_y, $color, $fontfile2, $word);
    }

    /**
     * 特殊字符加下划线
     *
     * @param string|int $word
     * @param string|int $params
     * @param string|int $cur_x
     * @param string|int $cur_y
     * @param string|int $size
     * @param string|int $angle
     * @param string|int $color
     * @param string|int $fontfile
     * @param string|int $box
     * @return void
     * @author chentengfei
     * @since
     */
    public function specialUnderline($word, $params, $cur_x, $cur_y, $size, $angle, $color, $fontfile)
    {
        $box = imageftbbox($size, $angle, $fontfile, $word);
        $box_width = sqrt(pow($box[2] + 1, 2) + pow($box[3] + 1, 2));
        $width = $params['width'] ?? 1;
        $sx = $cur_x + $width * sin(deg2rad($angle));
        $sy = $cur_y - $width * cos(deg2rad($angle));
        $ex = $cur_x + $box_width * cos(deg2rad($angle)) + $width * sin(deg2rad($angle));
        $ey = $cur_y - $box_width * sin(deg2rad($angle)) + $width * cos(deg2rad($angle));
        // var_dump($sx,$sy,$ex,$ey);
        $this->addLayer($sx, $sy, $ex, $ey, $width / 2, $params['color'] ?? '#fff');
        imagefttext($this->image, $size, $angle, $cur_x, $cur_y, $color, $fontfile, $word);
    }
}