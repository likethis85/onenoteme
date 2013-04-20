<?php
class CDUploadFile extends CUploadedFile
{
    const FILE_NO_EXIST = -1; // '目录不存在并且无法创建';
    const FILE_NO_WRITABLE = -2; // '目录不可写';
    
    
    /**
     * 返回上传后的文件路径
     * @return string|Array 如果成功则返回路径地址，如果失败则返回错误号和错误信息
     * -1 目录不存在并且无法创建
     * -2 目录不可写
     */
    public static function makeUploadPath($additional = null, $basePath = null, $upyun = false)
    {
        $relativeUrl = (($additional === null) ? '' : $additional . '/') . date('Y/m/d/', $_SERVER['REQUEST_TIME']);

        if ($upyun) {
            return array(
                'path' => $relativeUrl,
                'url' => $relativeUrl,
            );
        }
        
        $relativePath = (($additional === null) ? '' : $additional . DS) . date(addslashes(sprintf('Y%sm%sd%s', DS, DS, DS)), $_SERVER['REQUEST_TIME']);
        if (empty($basePath))
            $basePath = app()->getComponent('localUploader')->basePath;
        $path = $basePath . $relativePath;
    
        if ((file_exists($path) || mkdir($path, 0755, true)) && is_writable($path))
            return array(
                'path' => realpath($path) . DS,
                'url' => $relativeUrl,
            );
        else
            throw new Exception('path not exist or not writable', 0);
    }
    
    /**
     * 生成文件名
     * @param string $filename 软件名
     * @return string 转化之后的名称
     */
    public static function makeUploadFileName($extension='', $prefix = '')
    {
        $extension = strtolower($extension);
        $filename = date('YmdHis_', $_SERVER['REQUEST_TIME'])
            . uniqid()
            . ($extension ? $extension : '');
    
        if (strlen($prefix) > 0)
            $filename = $prefix . '_' . $filename;
    
        return $filename;
    }
    
    
    public static function makeUploadFilePath($extension, $additional = null, $basePath = null)
    {
        $path = self::makeUploadPath($additional, $basePath);
        $file = self::makeUploadFileName($extension);
    
        $data = array(
            'path' => $path['path'] . $file,
            'url' => $path['url'] . $file,
        );
    
        return $data;
    }
    
    
    public static function uploadImage(CUploadedFile $upload, $additional = null, $compress = true, $deleteTempFile = true)
    {
        if (!$compress) {
            $result = self::uploadFile($upload, $additional, $deleteTempFile);
            return $result;
        }
    
        $path = self::makeUploadPath($additional, $basePath = null);
        $file = self::makeUploadFileName(null);
        $filename = $path['path'] . $file;
        $im = new CDImage();
        $im->load($upload->tempName);
        $result = $im->save($filename);
        $newFilename = $im->filename();
        unset($im);
        if ($result === false)
            return false;
        else {
            $filename = array(
                'path' => $path['path'] . $newFilename,
                'url' => $path['url'] . $newFilename
            );
            return $filename;
        }
    }
    
    public static function uploadFile(CUploadedFile $upload, $additional = null, $deleteTempFile = true)
    {
        $filename = self::makeUploadFilePath($upload->extensionName, $additional, $basePath = null);
        $result = $upload->saveAs($filename['path'], $deleteTempFile);
        if ($result)
            return $filename;
        else
            return false;
    }
    

    public static function saveRemoteImages($url, $thumbWidth, $thumbHeight, $cropFromTop = false, $cropFromLeft = false, $referer = '')
    {
        $url = strip_tags(trim($url));
        $images = array();
        if (empty($url) || filter_var($url, FILTER_VALIDATE_URL) === false)
            return $images;
    
        $upyunEnabled = (bool)param('upyun_enabled');
        if ($upyunEnabled)
            $images = self::saveRemoteImagesToUpyun($url, $thumbWidth, $thumbHeight, $cropFromTop, $cropFromLeft, $referer = '');
        else
            $images = self::saveRemoteImagesToLocal($url, $thumbWidth, $thumbHeight, $cropFromTop, $cropFromLeft, $referer = '');
    
        return $images;
    }
    
    public static function saveRemoteImagesToUpyun($url, $thumbWidth, $thumbHeight, $cropFromTop = false, $cropFromLeft = false, $referer = '')
    {
        $images = array();
        set_time_limit(0);
    
        $curl = new CDCurl();
        $curl->referer($referer)->get($url);
        $errno = $curl->errno();
        if ($errno != 0)
            throw new Exception($curl->error(), $errno);
    
        $data = $curl->rawdata();
        $curl->close();
    
        $im = new CDImage();
        $im->load($data);
    
        $isGifAnimate = CDImage::isGifAnimate($data, true);
         
        if ($im->width()/$im->height() > $thumbWidth/$thumbHeight)
            $im->resizeToHeight($thumbHeight);
        else
            $im->resizeToWidth($thumbWidth);
        $im->crop($thumbWidth, $thumbHeight, $cropFromTop, $cropFromLeft);
    
        $extension = CDImage::getImageExtName($data);
        $path = self::makeUploadPath('pics',null, true);
        $urlpath = '/' . trim($path['url'], '/') . '/';
        $file = self::makeUploadFileName($extension);
        $thumbnailFilename = $urlpath . 'thumbnail_' . $file;
        $originalFilename = $urlpath . 'original_' . $file;
        $uploader = upyunUploader(true);
        try {
            $result = array();
            $thumbnailData = $im->outputRaw();
            $uploader->setFilename($thumbnailFilename);
            $result = $uploader->save($thumbnailData);
            $thumbnail['width'] = (int)$result['x-upyun-width'];
            $thumbnail['height'] = (int)$result['x-upyun-height'];
            $thumbnailPath = $uploader->filename;
            $thumbnail['url'] = $uploader->getFileUrl();
        }
        catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    
        try {
            $result = array();
            $im->revert();
    
            if ($isGifAnimate)
                $originalData = $data;
            else {
                $text = '挖段子网';
                $font = Yii::getPathOfAlias('application.fonts') . DS . 'msyh.ttf';
                $color = array(200, 200, 200);
                if ($im->width() > IMAGE_WATER_SIZE) {
                    $im->text($text, $font, 24, CDImage::MERGE_BOTTOM_LEFT, $color);
                    $im->text('http://www.waduanzi.com', $font, 12, CDImage::MERGE_BOTTOM_RIGHT, $color);
                }
                $originalData = $im->outputRaw();
            }
    
            $uploader->setFilename($originalFilename);
            $result = $uploader->save($originalData);
            $original['width'] = (int)$result['x-upyun-width'];
            $original['height'] = (int)$result['x-upyun-height'];
            $original['url'] = $uploader->getFileUrl();
        }
        catch (Exception $e) {
            $uploader->delete($thumbnailPath);
            throw new Exception($e->getMessage());
        }
    
        $im->revert();
        if ($im->width() > IMAGE_MIDDLE_WIDTH)
            $im->resizeToWidth(IMAGE_MIDDLE_WIDTH);
    
        $middle['url'] = $original['url'] . UPYUN_IMAGE_CUSTOM_SEPARATOR . UPYUN_IMAGE_CUSTOM_MIDDLE;
        $middle['width'] = $im->width();
        $middle['height'] = $im->height();
    
        unset($data, $curl, $im);
        $images = array($thumbnail, $middle, $original, $uploader->animatedGifImage());
    
        return $images;
    }
    
    /**
     * 将远程图片保存在本地服务器
     * @param string $url
     * @param integer $thumbWidth
     * @param integer $thumbHeight
     * @param boolean $cropFromTop
     * @param boolean $cropFromLeft
     * @throws Exception
     * @return multitype:array boolean Ambigous <array, boolean>
     */
    public static function saveRemoteImagesToLocal($url, $thumbWidth, $thumbHeight, $cropFromTop = false, $cropFromLeft = false, $referer = '')
    {
        $images = array();
        set_time_limit(0);
    
        $curl = new CDCurl();
        $curl->referer($referer)->get($url);
        $errno = $curl->errno();
        if ($errno != 0)
            throw new Exception($curl->error(), $errno);
    
        $data = $curl->rawdata();
        $curl->close();
    
        $isGifAnimate = CDImage::isGifAnimate($data, true);
    
        $path = self::makeUploadPath('pics');
        $info = parse_url($url);
        $file = self::makeUploadFileName();
        $thumbnailFile = 'thumbnail_' . $file;
        $thumbnailFileName = $path['path'] . $thumbnailFile;
        if ($isGifAnimate) $file .= '.gif';
        $middleFileName = $path['path'] . 'bmiddle_' . $file;
        $bigFile = 'original_' . $file;
        $bigFileName = $path['path'] . $bigFile;
    
        $im = new CDImage();
        $im->load($data);
    
        if ($im->width()/$im->height() > $thumbWidth/$thumbHeight)
            $im->resizeToHeight($thumbHeight);
        else
            $im->resizeToWidth($thumbWidth);
        $im->crop($thumbWidth, $thumbHeight, $cropFromTop, $cropFromLeft)
        ->saveAsJpeg($thumbnailFileName);
        $thumbnail['width'] = $im->width();
        $thumbnail['height'] = $im->height();
        $thumbnail['url'] = fbu($path['url'] . $im->filename());
    
        if ($isGifAnimate) {
            $gifFile = 'gif_' . $file;
            $gifFileName = $path['path'] . $gifFile;
            $result = @file_put_contents($gifFileName, $data);
            if ($result) {
                $im->revert();
                $width = $im->width();
                $height = $im->height();
                $gifUrl = fbu($path['url'] . $gifFile);
                $middle['url'] = $gifUrl;
                $middle['width'] = $width;
                $middle['height'] = $height;
                $original['url'] = $gifUrl;
                $original['width'] = $width;
                $original['height'] = $height;
            }
        }
        else {
            $im->revert();
            if ($im->width() > IMAGE_MIDDLE_WIDTH)
                $im->resizeToWidth(IMAGE_MIDDLE_WIDTH);
    
            $text = '挖段子网';
            $font = Yii::getPathOfAlias('application.fonts') . DS . 'msyh.ttf';
            $color = array(200, 200, 200);
            if ($im->width() > IMAGE_WATER_SIZE) {
                $im->text($text, $font, 24, CDImage::MERGE_BOTTOM_LEFT, $color);
                $im->text('http://www.waduanzi.com', $font, 12, CDImage::MERGE_BOTTOM_RIGHT, $color);
            }
    
            $im->saveAsJpeg($middleFileName, 75);
            $middle['url'] = fbu($path['url'] . $im->filename());
            $middle['width'] = $im->width();
            $middle['height'] = $im->height();
             
            $im->revert();
            if ($im->width() > IMAGE_WATER_SIZE) {
                $im->text($text, $font, 24, CDImage::MERGE_BOTTOM_LEFT, $color);
                $im->text('http://www.waduanzi.com', $font, 12, CDImage::MERGE_BOTTOM_RIGHT, $color);
            }
            $im->saveAsJpeg($bigFileName, 100);
            $original['url'] = fbu($path['url'] . $im->filename());
            $original['width'] = $im->width();
            $original['height'] = $im->height();
        }
        unset($data, $curl);
        $images = array($thumbnail, $middle, $original, $isGifAnimate);
    
        return $images;
    }
}


