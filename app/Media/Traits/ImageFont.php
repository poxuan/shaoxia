<?php

namespace App\Media\Traits;

/**
 * 字体处理
 * @package Common\Util
 */
trait ImageFont
{
    private $fontpath = './static/fonts/';
    private $fontdefault = 'pingfang-standard';

    /**
     * 获取目前支持的字体列表
     *
     * @return void
     * @author chentengfei
     * @since
     */
    public function getFontList()
    {
        $list = [];
        $files = scandir($this->fontpath);
        foreach ($files as $file) {
            $ext = substr($file, -4);
            if (($ext == '.ttf' || $ext == '.otf')) {
                $name = substr($file, 0, strlen($file) - 4);
                $list[$name] = [
                    'name' => $name,
                    'path' => $this->fontpath . $file,
                ];
            }
        }
        return $list;
    }

    /**
     * 按名称获取字体文件
     *
     * @param string $name
     * @return void
     * @author chentengfei
     * @since
     */
    public function getFont($name = '')
    {
        if (file_exists($this->fontpath . $name . '.otf')) {
            return \realpath($this->fontpath . $name . '.otf');
        } elseif (file_exists($this->fontpath . $name . '.ttf')) {
            return \realpath($this->fontpath . $name . '.ttf');
        } else {
            return \realpath($this->fontpath . $this->fontdefault . '.ttf');
        }
    }

    /**
     * 自定义分割
     *
     * @param string $name
     * @return array
     * @author chentengfei
     * @since
     */
    public function myexplode(string $glue, string $str) {
        if ($glue !== '') {
            return explode($glue, $str);
        }
        $word = [];
        $len = $lastLen = 1;
        $lastPos = 0;
        for ($i = 0; $i < strlen($str); $i += $len) {
            if (ord($str[$i]) >= 0xFC) {
                $len = 6;
            } elseif (ord($str[$i]) >= 0xF8) {
                $len = 5;
            } elseif (ord($str[$i]) >= 0xF0) {
                $len = 4;
            } elseif (ord($str[$i]) >= 0xE0) {
                $len = 3;
            } elseif (ord($str[$i]) >= 0xC0) {
                $len = 2;
            } else {
                $len = 1;
            }
            if ($len > 1 || $lastLen > 1) {
                $word[] = substr($str, $lastPos, $i - $lastPos);
                $lastPos = $i;
            } elseif (in_array($str[$i], [' ', "\t", "\r", "\n"])) {
                $word[] = substr($str, $lastPos, $i - $lastPos);
                $word[] = "\n";
                $lastPos = $i + 1;
                $lastPos = $i + 1;
            }
            $lastLen = $len;
        }
        $word[] = substr($str, $lastPos);
        return array_filter($word, function ($item) {return $item !== '';});
    }

}