<?php

namespace Shaoxia\Media\Image;

use Shaoxia\Media\Traits\ImageFont;
use Shaoxia\Media\Traits\ImageSave;
use Shaoxia\Media\Traits\ImageSpecial;

/**
 * Class File
 * @package Common\Util
 */
class Image
{
    use ImageSave,ImageSpecial,ImageFont;
    // 源文件路径
    private $src;
    // 图片句柄
    private $image;
    // 图片二进制
    private $content;
    // 图片信息
    private $imageinfo;
    // 比例
    private $percent = 1;
    // 背景色
    private $background = '#fff';

    // 方位,可叠加使用
    const DIRE_CENTER = 15;
    const DIRE_LEFT   = 1;
    const DIRE_RIGHT  = 2;
    const DIRE_UP     = 4;
    const DIRE_DOWN   = 8;

    //颜色
    const COLOR_BLACK = '#000';
    const COLOR_WHITE = '#fff';
    const COLOR_RED   = '#f00';
    const COLOR_GREEN = '#0f0';
    const COLOR_BLUE  = '#00f';

    /**
     * px宽度转为pound 宽度
     *
     * @param string|int $px
     * @return void
     * @author chentengfei
     * @since
     */
    public static function px2pound($px)
    {
        $config = [
            4 => 1,
            6 => 3,
            8 => 4,
            10 => 6,
            12 => 8,
            14 => 9,
            16 => 11,
            18 => 13,
            19 => 14,
            20 => 14.5,
            21 => 15,
            22 => 16,
            23 => 17,
            24 => 17.5,
            25 => 18,
            26 => 19,
            28 => 21,
            30 => 23,
            32 => 24,
            34 => 26,
            36 => 28,
            38 => 29,
            40 => 31,
        ];
        if (isset($config[$px])) {
            return $config[$px];
        } elseif ($px < 4) {
            return 1;
        } elseif ($px > 40) {
            return ($px * 4 / 5);
        } else {
            return ($config[$px + 1] + $config[$px - 1]) / 2;
        }
    }

    /**
     * 给图片添加文字
     * 
     * @param string $text 文字内容
     * @param int    $size 字号,磅值，可通过 px2pound 方法转换得出
     * @param int    $x    起始位置横坐标
     * @param int    $y    起始位置纵坐标
     * @param array|string  $rgb  颜色RGB数组或16进制
     * @param int    $angle 角度
     * @param string $font 字体文件位置
     */
    public function addText($text, $size, $x, $y, $rgb = self::COLOR_BLACK, $angle = 0, $font = '')
    {
        $color = $this->getColor($rgb);
        $fontfile = $this->getFont($font);
        // 如果支持type 2（opentype, otf后缀） 的话，优先用type 2 （type 2 是 type 1(truetype ttf 后缀) 的超集）
        if (function_exists('imagefttext')) {
            imagefttext($this->image, $size, $angle, $x, $y, $color, $fontfile, $text);
        } else { // 使用默认字体生成
            imagettftext($this->image, $size, $angle, $x, $y, $color, $fontfile, $text);
        }
        return $this;
    }

    /**
     * 在图片上添加图片
     * 
     * @param string $path 图片路径
     * @param int    $x    起始位置横坐标
     * @param int    $y    起始位置纵坐标
     * @param int    $dst_width     目标图片宽度,不设为原图
     * @param int    $dst_height    目标图片高度,不设是按宽度等比例
     * @param int    $pct  合并程度
     */
    public function addPic($path, $x, $y, $dst_width = 0, $dst_height = 0, $pct = 100)
    {
        list($width, $height, $type, $attr) = getimagesize($path);
        $imageinfo = array(
            'width' => $width,
            'height' => $height,
            'type' => image_type_to_extension($type, false),
            'attr' => $attr,
        );
        !$dst_width && $dst_width = $width;
        !$dst_height && $dst_height = intval($height * $dst_width / $width);
        $content = file_get_contents($path);
        $image2 = imagecreatefromstring($content);
        $image_thump = imagecreatetruecolor($dst_width, $dst_height);
        //将原图复制带图片载体上面，并且按照一定比例压缩,极大的保持了清晰度
        imagecopyresampled($image_thump, $image2, 0, 0, 0, 0, $dst_width, $dst_height, $imageinfo['width'], $imageinfo['height']);
        imagedestroy($image2);
        imagecopymerge($this->image, $image_thump, $x, $y, 0, 0, $dst_width, $dst_height, $pct);
        return $this;
    }

    /**
     * 在图片上添加圆形图片
     * 
     * @param string $path 图片路径
     * @param int    $x    圆心X
     * @param int    $y    圆心Y
     * @param int    $r    半径
     * @param int    $pct  合并程度
     */
    public function addCriclePic($path, $x, $y, $r, $pct = 100)
    {
        list($width, $height, $type, $attr) = getimagesize($path);
        $imageinfo = array(
            'width' => $width,
            'height' => $height,
            'type' => image_type_to_extension($type, false),
            'attr' => $attr,
        );
        $dst_width = 2 * $r;
        $dst_height = 2 * $r;
        $content = file_get_contents($path);
        $image2 = imagecreatefromstring($content);
        $image_thump = imagecreatetruecolor($dst_width, $dst_height);
        //将原图复制带图片载体上面，并且按照一定比例压缩,极大的保持了清晰度
        imagecopyresampled($image_thump, $image2, 0, 0, 0, 0, $dst_width, $dst_height, $imageinfo['width'], $imageinfo['height']);
        imagedestroy($image2);
        // 画一个圆图
        for ($i = 0; $i < $dst_height; $i++) {
            $p = sqrt(2 * $r * $i - $i * $i);
            if ($p) {
                $sx = round($r - $p);
                $dx = $x - $r + $sx;
                $dy = $y - $r + $i;
                imagecopymerge($this->image, $image_thump, $dx, $dy, $sx, $i, 2 * $p, 1, $pct);
            }
        }
        return $this;
    }

    /**
     * 添加水印
     * 
     * @param int $position 方位
     * @param int $pct 覆盖度,0-100
     * @param string $waterImage 水印图(一个尺寸不大的png图片)
     * @param int $margin 外边距
     */
    public function addWatar($position = 9, $pct = 50, $waterImage = '', $margin = 5)
    {
        list($waterWidth, $waterHeight) = getimagesize($waterImage);
        $content = file_get_contents($waterImage);
        $image2 = imagecreatefromstring($content);
        $image_thump = imagecreatetruecolor($waterWidth, $waterHeight);
        // 图片比水印小则不加
        if ($waterWidth + 2 * $margin > $this->imageinfo['width'] || $waterHeight + 2 * $margin > $this->imageinfo['width']) {
            return $this;
        }
        switch ($position) {
            case 1:
                $x = $y = $margin;
                break;
            case 2:
                $x = ($this->imageinfo['width'] - $waterWidth) / 2;
                $y = 5;
                break;
            case 3:
                $x = $this->imageinfo['width'] - $waterWidth - $margin;
                $y = 5;
                break;
            case 4:
                $x = 5;
                $y = ($this->imageinfo['height'] - $waterHeight) / 2;
                break;
            case 5:
                $x = ($this->imageinfo['width'] - $waterWidth) / 2;
                $y = ($this->imageinfo['height'] - $waterHeight) / 2;
                break;
            case 6:
                $x = $this->imageinfo['width'] - $waterWidth - $margin;
                $y = ($this->imageinfo['height'] - $waterHeight) / 2;
                break;
            case 7:
                $x = $margin;
                $y = $this->imageinfo['height'] - $waterHeight - $margin;
                break;
            case 8:
                $x = ($this->imageinfo['width'] - $waterWidth) / 2;
                $y = $this->imageinfo['height'] - $waterHeight - $margin;
                break;
            case 9:
                $x = $this->imageinfo['width'] - $waterWidth - $margin;
                $y = $this->imageinfo['height'] - $waterHeight - $margin;
                break;
        }
        imagecopyresampled($image_thump, $image2, 0, 0, 0, 0, $waterWidth, $waterHeight, $waterWidth, $waterHeight);
        imagedestroy($image2);
        imagecopymerge($this->image, $image_thump, $x, $y, 0, 0, $waterWidth, $waterHeight, $pct);
        return $this;
    }

    /**
     * 生成白板底图
     *
     * @param string|int $width
     * @param string|int $height
     * @param string|int $rgb
     * @return void
     * @author chentengfei
     * @since
     */
    public function whiteboard($width, $height, $rgb = self::COLOR_WHITE)
    {
        if (is_string($rgb)) // 颜色字符转数组
        {
            $rgb = $this->hex2rgb($rgb);
        }
        $this->background = $rgb;
        $image = imagecreatetruecolor($width, $height);
        $color = imagecolorallocatealpha($image, $rgb[0], $rgb[1], $rgb[2], $rgb[3] ?? 0);
        imagefill($image, 0, 0, $color);
        $this->image = $image;
        $this->imageinfo = [
            'width' => $width,
            'height' => $height,
            'type' => 'png',
        ];
        return $this;
    }

    /**
     * 生成空白底图
     *
     * @param string|int $width
     * @param string|int $height
     * @param string|int $rgb
     * @return void
     * @author chentengfei
     * @since
     */
    public function vacancy($width, $height)
    {
        $image = imagecreatetruecolor($width, $height);
        $this->image = $image;
        $this->imageinfo = [
            'width' => $width,
            'height' => $height,
            'type' => 'png',
        ];
        return $this;
    }

    /**
     * 旋转这张图
     *
     * @param string|int $width
     * @param string|int $height
     * @param string|int $rgb
     * @return void
     * @author chentengfei
     * @since
     */
    public function rotate($angle, $rgba = [0,0,0,127])
    {
        $pngTransparency = imagecolorallocatealpha($this->image, $rgba[0], $rgba[1], $rgba[2], $rgba[3]);
        $this->image = imagerotate($this->image, $angle, $pngTransparency);
        $this->imageinfo = [
            'width' => imagesx($this->image),
            'height' => imagesy($this->image),
            'type' => 'png',
        ];
        return $this;
    }

    /**
     * 旋转这张图
     *
     * @param string|int $width
     * @param string|int $height
     * @param string|int $rgb
     * @return void
     * @author chentengfei
     * @since
     */
    public function addImage($image, $x, $y, $w, $h, $pct = 100)
    {
        
        $image2 = $image->image;
        $image_thump = imagecreatetruecolor($w, $h);
        //将原图复制带图片载体上面，并且按照一定比例压缩,极大的保持了清晰度
        imagecopyresampled($image_thump, $image2, 0, 0, 0, 0, $w, $h, imagesx($image2), imagesy($image2));
        imagedestroy($image2);
        imagecopymerge($this->image, $image_thump, $x, $y, 0, 0, $w, $h, $pct);
        return $this;
    }



    /**
     * 添加圆角矩形浮层
     *
     * @param int $sx 起点X
     * @param int $sy 起点Y
     * @param int $ex 起点X
     * @param int $ey 起点Y
     * @param int $redius 圆角
     * @param string|array $rgb  起始颜色
     * @param array $shadow  阴影
     */
    public function addLayer($sx, $sy, $ex, $ey, $redius = 0, $rgb = self::COLOR_WHITE, $shadow = [])
    {
        if ($shadow) {
            $this->addLayerShadow($sx, $sy, $ex, $ey, $redius, $shadow);
        }
        $width = $ex - $sx;
        $height = $ey - $sy;
        $color = $this->getColor($rgb);
        imagefilledrectangle($this->image, $sx + $redius, $sy, $sx + ($width - $redius), $sy + $redius, $color); //矩形一
        imagefilledrectangle($this->image, $sx, $sy + $redius, $sx + $width, $sy + ($height - ($redius * 1)), $color); //矩形二
        imagefilledrectangle($this->image, $sx + $redius, $sy + ($height - ($redius * 1)), $sx + ($width - ($redius * 1)), $sy + $height, $color); //矩形三
        imagefilledarc($this->image, $sx + $redius, $sy + $redius, $redius * 2, $redius * 2, 180, 270, $color, IMG_ARC_PIE); //四分之一圆 - 左上
        imagefilledarc($this->image, $sx + ($width - $redius), $sy + $redius, $redius * 2, $redius * 2, 270, 360, $color, IMG_ARC_PIE); //四分之一圆 - 右上
        imagefilledarc($this->image, $sx + $redius, $sy + ($height - $redius), $redius * 2, $redius * 2, 90, 180, $color, IMG_ARC_PIE); //四分之一圆 - 左下
        imagefilledarc($this->image, $sx + ($width - $redius), $sy + ($height - $redius), $redius * 2, $redius * 2, 0, 90, $color, IMG_ARC_PIE); //四分之一圆 - 右下

        return $this;
    }

    /**
     * 添加阴影
     *
     * @param int $sx
     * @param int $sy
     * @param int $ex
     * @param int $ey
     * @param int $redius
     * @param array $shadow
     * @return void
     * @author chentengfei
     * @since
     */
    public function addLayerShadow($sx, $sy, $ex, $ey, $redius, $shadow)
    {
        $x = $shadow['offset_x'] ?? 0;
        $y = $shadow['offset_y'] ?? 0;
        $blur = $shadow['radius'] ?? 5;
        $color = $shadow['color'] ?? "#888";
        $this->addLayer($sx + $x, $sy + $y, $ex + $x, $ey + $y, $redius, $color);
        for ($i = $blur; $i > 0; $i--) {
            $radio = ($blur - $i) / ($blur + 1);
            $c = $this->getGradientColor($color, $this->background, $radio * $radio);
            $this->addLayer($sx + $x - $i, $sy + $y - $i, $ex + $x + $i, $ey + $y + $i, $redius + $i, $c);
        }
        return $this;
    }

    /**
     * 添加渐变圆角矩形浮层
     *
     * @param int $sx 起点X
     * @param int $sy 起点Y
     * @param int $ex 起点X
     * @param int $ey 起点Y
     * @param int $redius 圆角
     * @param string|array $rgb1  起始颜色
     * @param string|array $rgb2  目标颜色
     * @param int|array $direction 方向或目标点
     */
    public function addGradientLayer($sx, $sy, $ex, $ey, $redius = 0, $rgb1 = self::COLOR_WHITE, $rgb2 = self::COLOR_BLACK, $direction = self::DIRE_RIGHT, $shadow = [])
    {
        if ($shadow) {
            $this->addLayerShadow($sx, $sy, $ex, $ey, $redius, $shadow);
        }
        $colors = $this->getGradientPointColor($sx, $sy, $ex, $ey, $direction, $rgb1, $rgb2);
        for ($i = $sx; $i < $ex; $i++) {
            for ($j = $sy; $j < $ey; $j++) {
                $in = $this->inLayer($sx, $sy, $ex, $ey, $redius, $i, $j);
                if ($in) {
                    $color = $this->getColor($colors[$i][$j]);
                    imagesetpixel($this->image, $i, $j, $color);
                }
            }
        }
        return $this;
    }

    /**
     * 获取渐进色
     *
     * @param string|array $from
     * @param string|array $to
     * @param integer $ratio
     * @param bool $withAlpha
     * @param 
     * @return void
     * @author chentengfei
     * @since
     */
    public function getGradientColor($from, $to, $ratio = 1, $withAlpha = true)
    {
        if (is_string($from)) // 颜色字符转数组
        {
            $from = $this->hex2rgb($from);
        }

        if (is_string($to)) {
            $to = $this->hex2rgb($to);
        }

        $r = round(abs($to[0] - abs($ratio * ($from[0] - $to[0]))));
        $g = round(abs($to[1] - abs($ratio * ($from[1] - $to[1]))));
        $b = round(abs($to[2] - abs($ratio * ($from[2] - $to[2]))));
        if (isset($from[3]) && isset($to[3])) {
            $alpha = round(abs($to[3] - abs($ratio * ($from[3] - $to[3]))));
        } elseif ($withAlpha) {
            $alpha = round((1-$ratio) * 127);
        } else {
            $alpha = 0;
        }
        return [$r, $g, $b, $alpha];
    }

    /**
     * 计算渐进终点
     *
     * @param string|int $sx
     * @param string|int $sy
     * @param string|int $ex
     * @param string|int $ey
     * @param string|int $direction
     * @return array
     * @author chentengfei
     * @since
     */
    public function getGradientEndPoint($sx, $sy, $ex, $ey, $direction)
    {
        $aim_x = $aim_y = 0;
        if (!is_array($direction)) {
            if ($direction & self::DIRE_LEFT) {
                $aim_x = $sx;
            }
            if ($direction & self::DIRE_RIGHT) {
                $aim_x = $aim_x ? ($ex + $sx) / 2 : $ex;
            }
            if ($direction & self::DIRE_UP) {
                $aim_y = $sy;
            }
            if ($direction & self::DIRE_DOWN) {
                $aim_y = $aim_y ? ($ey + $sy) / 2 : $ey;
            }
        } else {
            $aim_x = $direction[0];
            $aim_y = $direction[1];
        }
        return [$aim_x, $aim_y];
    }

    /**
     * 获取渐进点颜色
     *
     * @param string|int $sx
     * @param string|int $sy
     * @param string|int $ex
     * @param string|int $ey
     * @param string|int $direction
     * @param string|int $rgb1
     * @param string|int $rgb2
     * @return void
     * @author chentengfei
     * @since
     */
    public function getGradientPointColor($sx, $sy, $ex, $ey, $direction, $rgb1, $rgb2)
    {
        $points = [];
        list($to_x, $to_y) = $this->getGradientEndPoint($sx, $sy, $ex, $ey, $direction);
        if (is_string($rgb1)) // 颜色字符转数组
        {
            $rgb1 = $this->hex2rgb($rgb1);
        }

        if (is_string($rgb2)) {
            $rgb2 = $this->hex2rgb($rgb2);
        }

        for ($i = $sx; $i < $ex; $i++) {
            for ($j = $sy; $j < $ey; $j++) {
                if ($to_y == 0 && $to_x == 0) {
                    $points[$i][$j] = $rgb1;
                } elseif ($to_y == 0) {
                    if ($i == $to_x) {
                        $points[$i][$j] = $rgb2;
                    } else {
                        $ratio = $i > $to_x ? ($i - $to_x) / ($ex - $to_x) : ($to_x - $i) / ($to_x - $sx);
                        $points[$i][$j] = $this->getGradientColor($rgb1, $rgb2, $ratio, false);
                    }
                } elseif ($to_x == 0) {
                    if ($j == $to_y) {
                        $points[$i][$j] = $rgb2;
                    } else {
                        $ratio = $j > $to_y ? ($i - $to_y) / ($ey - $to_y) : ($to_y - $i) / ($to_y - $sy);
                        $points[$i][$j] = $this->getGradientColor($rgb1, $rgb2, $ratio, false);
                    }
                } else {
                    $x = abs($i - $to_x);
                    $y = abs($j - $to_y);
                    $hx = max($ex - $to_x, $to_x - $sx);
                    $hy = max($ey - $to_y, $to_y - $sy);
                    $ratio = sqrt(($x * $x + $y * $y) / ($hx * $hx + $hy * $hy));
                    $points[$i][$j] = $this->getGradientColor($rgb1, $rgb2, $ratio, false);
                }
            }
        }
        return $points;
    }

    /**
     * 检查一个点是否在浮层上
     *
     * @param int $sx
     * @param int $sy
     * @param int $ex
     * @param int $ey
     * @param int $redius
     * @param int $i
     * @param int $j
     * @return void
     * @author chentengfei
     * @since
     */
    public function inLayer($sx, $sy, $ex, $ey, $redius, $i, $j)
    {
        // 内点；圆角的四个圆心
        $xl = $sx + $redius;
        $xr = $ex - $redius;
        $yt = $sy + $redius;
        $yb = $ey - $redius;
        $r2 = $redius * $redius;
        $dis = 0;
        if ($i < $xl && $j < $yt) { // 左上角圆角区
            $x = $xl - $i;
            $y = $yt - $j;
            $dis = $x * $x + $y * $y - $r2;
        } elseif ($i < $xl && $j > $yb) { // 左下角圆角区
            $x = $xl - $i;
            $y = $j - $yb;
            $dis = $x * $x + $y * $y - $r2;
        } elseif ($i > $xr && $j < $yt) { // 右上角圆角区
            $x = $i - $xr;
            $y = $yt - $j;
            $dis = $x * $x + $y * $y - $r2;
        } elseif ($i > $xr && $j > $yb) { // 右下角圆角区
            $x = $i - $xr;
            $y = $j - $yb;
            $dis = $x * $x + $y * $y - $r2;
        }
        if ($dis > 0) {
            return false;
        }
        return true;
    }

    /**
     * 添加长文本
     *
     * @param string $text 长文本
     * @param float  $size 字体大小（磅）
     * @param int    $x 开始位置X
     * @param int    $y 开始位置Y
     * @param int    $width 文字框宽度
     * @param int|string $lineHeight 行高
     * @param string $glue 单词分隔符
     * @param array|string $rgb 颜色
     * @param string $font 字体
     * @param array  $special 特殊处理字符
     */
    public function addLongText($text, $size, $x, $y, $width, $lineHeight = 'auto', $glue = ' ', $rgb = self::COLOR_BLACK, $angle = 0, $font = '',$special = [])
    {
        $color = $this->getColor($rgb);
        $fontfile = $this->getFont($font);
        $words = myexplode($glue, $text);
        $cur_x = $x;
        $cur_y = $y;
        $line = 0;
        if ('auto' == $lineHeight) {
            $lineHeight = intval(1.5 * $size);
        }
        $cur_width = 0;
        foreach ($words as $word) {
            $box = imageftbbox($size, $angle, $fontfile, $word . $glue);
            $box_width = sqrt(pow($box[2] + 1, 2) + pow($box[3] + 1, 2));
            if ($cur_width + $box_width < $width && $word != "\n") { //不换行
                $this->addWord($word, $cur_x, $cur_y, $size, $angle, $color, $fontfile, $special);
                $cur_x += $box_width * cos(deg2rad($angle));
                $cur_y -= $box_width * sin(deg2rad($angle));
                $cur_width += $box_width;
            } else { //换行
                $line++;
                $cur_x = $x + $lineHeight * $line * sin(deg2rad($angle));
                $cur_y = $y + $lineHeight * $line * cos(deg2rad($angle));
                $this->addWord($word, $cur_x, $cur_y, $size, $angle, $color, $fontfile, $special);
                $cur_x += $box_width * cos(deg2rad($angle));
                $cur_y -= $box_width * sin(deg2rad($angle));
                $cur_width = $box_width;
            }
        }
        return $this;
    }

    public function addWord($word, $cur_x, $cur_y, $size, $angle, $color, $fontfile, $special)
    {
        if (isset($special[$word])) {
            $specialType = $special[$word]['type'] ?? '';
            $func = 'special' . $specialType;
            if (is_callable([$this, $func])) {
                $this->$func($word, $special[$word], $cur_x, $cur_y, $size, $angle, $color, $fontfile);
            } else {
                imagefttext($this->image, $size, $angle, $cur_x, $cur_y, $color, $fontfile, $word);
            }
        } else {
            imagefttext($this->image, $size, $angle, $cur_x, $cur_y, $color, $fontfile, $word);
        }
    }

    /**
     * 按比例生成底图（文件体）
     *
     * @param string|int $content
     * @param integer $percent
     * @return void
     * @author chentengfei
     * @since
     */
    public function compress($content, $percent = 1)
    {
        $this->content = $content;
        $this->percent = $percent;
        $this->_openImage();
        return $this;
    }

    /**
     * 按比例生成底图（文件路径）
     *
     * @param string|int $file
     * @param integer $percent
     * @return void
     * @author chentengfei
     * @since
     */
    public function compressFile($file, $percent = 1)
    {
        $this->src = $file;
        $this->content = @file_get_contents($file);
        $this->percent = $percent;
        $this->_openImage();
        return $this;
    }

    /**
     * 按固定尺寸生成底图（文件体）
     *
     * @param string|int $content
     * @param string|int $width
     * @param integer $height
     * @return void
     * @author chentengfei
     * @since
     */
    public function thump($content, $width, $height = 0)
    {
        $this->content = $content;
        $this->_openImage($width, $height);
        return $this;
    }

    /**
     * 按固定尺寸生成底图（文件路径）
     *
     * @param string|int $file
     * @param string|int $width
     * @param integer $height
     * @return void
     * @author chentengfei
     * @since
     */
    public function thumpFile($file, $width, $height = 0)
    {
        $this->src = $file;
        $this->content = @file_get_contents($file);
        $this->_openImage($width, $height);
        return $this;
    }

    /**
     * 生成image句柄
     *
     * @param integer $thump_width
     * @param integer $thump_height
     * @return void
     * @author chentengfei
     * @since
     */
    private function _openImage($thump_width = 0, $thump_height = 0)
    {
        list($width, $height, $type, $attr) = getimagesizefromstring($this->content);
        $this->imageinfo = array(
            'width' => $width,
            'height' => $height,
            'type' => image_type_to_extension($type, false),
            'attr' => $attr,
        );
        $this->image = imagecreatefromstring($this->content);
        $this->_thumpImage($thump_width, $thump_height);
    }

    /**
     * 生成image句柄
     *
     * @param integer $width
     * @param integer $height
     * @return void
     * @author chentengfei
     * @since
     */
    private function _thumpImage($width = 0, $height = 0)
    {
        if ($width > 0 && $height == 0) {
            $height = intval($this->imageinfo['width'] / $width * $this->imageinfo['height']);
        }
        $new_width = $width ?: $this->imageinfo['width'] * $this->percent;
        $new_height = $height ?: $this->imageinfo['height'] * $this->percent;
        $image_thump = imagecreatetruecolor($new_width, $new_height);
        //将原图复制带图片载体上面，并且按照一定比例压缩
        imagecopyresampled($image_thump, $this->image, 0, 0, 0, 0, $new_width, $new_height, $this->imageinfo['width'], $this->imageinfo['height']);
        imagedestroy($this->image);
        $this->image = $image_thump;
    }

    /**
     * 检查有没有图片句柄
     *
     * @return void
     * @author chentengfei
     * @since
     */
    public function check()
    {
        return $this->image ? true : false;
    }

    
    /**
     * 16进制颜色转数组
     *
     * @param string|int $hexColor
     * @return void
     * @author chentengfei
     * @since
     */
    public function hex2rgb($hexColor)
    {
        $color = str_replace('#', '', $hexColor);
        if (strlen($color) == 8) { // 8位
            $rgb = array(
                hexdec(substr($color, 0, 2)),
                hexdec(substr($color, 2, 2)),
                hexdec(substr($color, 4, 2)),
                hexdec(substr($color, 6, 2)),
            );
        } elseif (strlen($color) > 3) {
            $rgb = array(
                hexdec(substr($color, 0, 2)),
                hexdec(substr($color, 2, 2)),
                hexdec(substr($color, 4, 2)),
            );
        } else {
            $r = substr($color, 0, 1) . substr($color, 0, 1);
            $g = substr($color, 1, 1) . substr($color, 1, 1);
            $b = substr($color, 2, 1) . substr($color, 2, 1);
            $rgb = array(
                hexdec($r),
                hexdec($g),
                hexdec($b),
            );
        }
        return $rgb;
    }

    /**
     * 获取一个颜色
     *
     * @param string|int $rgb
     * @return mixed
     * @author chentengfei
     * @since
     */
    public function getColor($rgb)
    {
        if (is_string($rgb)) { // 颜色如果是16进制的话，先转成数组
            $rgb = $this->hex2rgb($rgb);
        }
        return imagecolorallocatealpha($this->image, $rgb[0], $rgb[1], $rgb[2], $rgb[3] ?? 0);
    }

    /**
     * 以数组方式调用
     *
     * @param array $steps
     * @return void
     * @author chentengfei
     * @since
     */
    public function make($steps)
    {
        foreach($steps as $step) {
            $func = array_shift($step);
            if (method_exists($this,$func)) {
                \call_user_func_array([$this,$func],$step);
            }
        }
    }



    /**
     * 析构，释放图片句柄
     *
     * @author chentengfei
     * @since
     */
    public function __destruct()
    {
        
    }
}
