<?php

namespace Shaoxia\Media\Traits;

/**
 * 存储
 * @package Common\Util
 */
trait ImageSave
{
    private $savepath = './tmp/';
    /**
     * 本地存储
     * 
     * @param string $dstImgName 存储路径
     * @param string $filetype 保存格式 png|jpeg
     */
    public function saveImageLocal($dstImgName, $filetype = "")
    {
        if (empty($dstImgName) || empty($this->image)) {
            return false;
        }

        $allowImgs = ['.jpg', '.jpeg', '.png', '.bmp', '.wbmp', '.gif']; //如果目标图片名有后缀就用目标图片扩展名 后缀，如果没有，则用源图的扩展名
        $dstExt = strrchr($dstImgName, ".");
        $sourseExt = strrchr($this->src, ".");
        if (!empty($dstExt)) {
            $dstExt = strtolower($dstExt);
        }

        if (!empty($sourseExt)) {
            $sourseExt = strtolower($sourseExt);
        }

        //有指定目标名扩展名
        if (!empty($dstExt) && in_array($dstExt, $allowImgs)) {
            $dstName = $dstImgName;
        } elseif (!empty($sourseExt) && in_array($sourseExt, $allowImgs)) {
            $dstName = $dstImgName . $sourseExt;
        } else {
            $dstName = $dstImgName . ($filetype ?: $this->imageinfo['type']);
        }
        $funcs = "image" . ($filetype ?: $this->imageinfo['type']);
        $funcs($this->image, $this->savepath . $dstName);
        return $this->savepath . $dstName;
    }

    /**
     * 云端存储
     *
     * @param string $dstImgName 存储路径
     * @param string $filetype   保存格式(png,jpeg)
     * @param string $cloud      云名称
     * @return string|boolean    图片位置,失败返回false
     */
    public function saveImageToCloud($dstImgName, $filetype = "", $cloud = "Qiniu")
    {
        if (empty($dstImgName)) {
            return false;
        }

        // 保存到 tmp 临时目录下
        $localName = $this->savepath . uniqid();
        $funcs = "image" . ($filetype ?: $this->imageinfo['type']);
        $res = $funcs($this->image, $localName);
        if (!$res) {
            return false;
        }
        $funcs = 'upload' . ucfirst($cloud);
        return $this->$funcs($dstImgName, $localName);
    }

    /**
     * 存到七牛
     *
     * @param string|int $dstImgName
     * @param string|int $localName
     * @return void
     * @author chentengfei
     * @since
     */
    protected function uploadQiniu($dstImgName, $localName)
    {
        $setting = config('qiniu');
        $auth = new \Qiniu\Auth($setting['accessKey'], $setting['secretKey']);
        $token = $auth->uploadToken($setting['bucket']);
        $UploadManager = new \Qiniu\Storage\UploadManager();
        $res = $UploadManager->putFile($token, $dstImgName, $localName, null, 'application/octet-stream', false);
        @unlink($localName);
        if (!empty($res[0]['key'])) {
            return $setting['domain'] . $res[0]['key'];
        } else {
            return false;
        }
    }
}