<?php

namespace Shaoxia\Media\Traits;

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
}