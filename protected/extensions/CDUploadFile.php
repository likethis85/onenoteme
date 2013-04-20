<?php
class CDUploadFile extends CUploadedFile
{
    private $_upyunEnabled = false;
    
    public function saveFileAs($file, $isImageFile = false, $deleteTempFile = true, $opts = null)
    {
        if ($this->_error == UPLOAD_ERR_OK) {
            $uploader = $this->_upyunEnabled ? upyunUploader($isImageFile) : app()->getComponent('localUploader', false);
            $content = file_get_contents($this->_tempName);
            $uploader->save($content, $file, $opts);
            if ($deleteTempFile)
                @unlink($this->_tempName);
            
            return $uploader->getFileUrl();
        }
        else
            return false;
    }
    
    public function saveImageAs($file, $deleteTempFile = true, $opts = null)
    {
        return $this->saveFileAs($file, true, $deleteTempFile, $opts);
    }
    
    public function setUpyunEnabled($enabled = true)
    {
        $this->_upyunEnabled = $enabled;
    }
    
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
                'path' => '/' . $relativeUrl,
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
    
    
    public static function makeUploadFilePath($extension, $additional = null, $basePath = null, $upyun = false)
    {
        $path = self::makeUploadPath($additional, $basePath, $upyun);
        $file = self::makeUploadFileName($extension);
    
        $data = array(
            'path' => $path['path'] . $file,
            'url' => $path['url'] . $file,
        );
    
        return $data;
    }
    
    
    /********************************************/
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
    
    public static function saveFile($additional = null, $deleteTempFile = true)
    {
        $filename = self::makeUploadFilePath($upload->extensionName, $additional, $basePath = null);
        $result = $this->saveAs($filename['path'], $deleteTempFile);
        if ($result)
            return $filename;
        else
            return false;
    }
    
    /********************************************/
    

    public static function saveImage($file, $referer = '', $thumbWidth = 0, $thumbHeight = 0, $cropFromTop = false, $cropFromLeft = false, $opts = array())
    {
        $file = strip_tags(trim($file));
        $images = array();
        if (empty($file) || (!file_exists($file) && filter_var($file, FILTER_VALIDATE_URL) === false))
            return $images;
        
        // 获取文件内容
        if (file_exists($file) && is_readable($file)) {
            $data = file_get_contents($file);
        }
        else {
            $curl = new CDCurl();
            $curl->referer($referer)->get($file);
            $errno = $curl->errno();
            if ($errno != 0)
                throw new Exception($curl->error(), $errno);
        
            $data = $curl->rawdata();
            $curl->close();
        }
        
        $im = new CDImage();
        $im->load($data);
        
        /* 检查是否有额外的参数选项，主要是去除头尾的版本水印及LOGO
         * 如果是动画，不作相关处理
         */
        if (!$im->isAnimateGif() && !empty($opts)) {
            $defaultOptions = array(
                'padding_top' => 0,
                'padding_bottom' => 0,
            );
            if (is_array($opts)) {
                foreach ($opts as $key => $value) {
                    if (!array_key_exists($key, $defaultOptions))
                        throw new CDException($key . ' is invalid.');
                }
            }
            else
                throw new CDException('$opts must be an array.');
            
            $top = (int)$opts['padding_top'];
            $bottom = (int)$opts['padding_bottom'];
            if ((array_key_exists('padding_top', $opts) && $top > 0) ||
                (array_key_exists('padding_bottom', $opts) && $bottom > 0)) {
                $width = $im->width();
                $height = $im->height() - $top - $bottom;
                $im->cropByFrame($width, $height, 0, $top);
                $im->setCurrentRawData();
            }
        
        }
    
        
        if (upyunEnabled())
            $images = self::saveImageToUpyun($im, $thumbWidth, $thumbHeight, $cropFromTop, $cropFromLeft);
        else
            $images = self::saveImageToLocal($im, $thumbWidth, $thumbHeight, $cropFromTop, $cropFromLeft);
    
        return $images;
    }
    
    public static function saveImageToUpyun(CDImage $im, $thumbWidth = 0, $thumbHeight = 0, $cropFromTop = false, $cropFromLeft = false)
    {
        $images = $thumbnail = $middle = $original = array();
        set_time_limit(0);

        $isGifAnimate = $im->isAnimateGif();
        $extension = $im->getExtName();

        // 生成缩略图并且保存到云存储中
        if ($thumbWidth > 0 && $thumbHeight > 0) {
            if ($im->width()/$im->height() > $thumbWidth/$thumbHeight)
                $im->resizeToHeight($thumbHeight);
            else
                $im->resizeToWidth($thumbWidth);
            $im->crop($thumbWidth, $thumbHeight, $cropFromTop, $cropFromLeft);
        
            $path = self::makeUploadPath('pics', null, true);
            $urlpath = '/' . trim($path['url'], '/') . '/';
            $file = self::makeUploadFileName($extension);
            $thumbnailFilename = $urlpath . 'thumbnail_' . $file;
            $originalFilename = $urlpath . 'original_' . $file;
            $uploader = upyunUploader(true);
            try {
                $result = array();
                $thumbnailData = $im->outputRaw();
                $result = $uploader->save($thumbnailData, $thumbnailFilename);
                $thumbnail['width'] = (int)$result['x-upyun-width'];
                $thumbnail['height'] = (int)$result['x-upyun-height'];
                $thumbnailPath = $uploader->filename;
                $thumbnail['url'] = $uploader->getFileUrl();
                $im->revert();
            }
            catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        }
    
        try {
            $result = array();
    
            if ($isGifAnimate)
                $originalData = $im->rawData();
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
    
            $result = $uploader->save($originalData, $originalFilename);
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
    public static function saveImageToLocal(CDImage $im, $thumbWidth = 0, $thumbHeight = 0, $cropFromTop = false, $cropFromLeft = false)
    {
        $images = $thumbnail = $middle = $original = array();
        set_time_limit(0);
    
        $isGifAnimate = $im->isAnimateGif();
        $extension = $im->getExtName();
    
        if ($thumbWidth > 0 && $thumbHeight > 0) {
            $path = self::makeUploadPath('pics');
            $file = self::makeUploadFileName();
            $thumbnailFile = 'thumbnail_' . $file;
            $thumbnailFileName = $path['path'] . $thumbnailFile;
            if ($isGifAnimate) $file .= '.gif';
            $middleFileName = $path['path'] . 'bmiddle_' . $file;
            $bigFile = 'original_' . $file;
            $bigFileName = $path['path'] . $bigFile;
        
            if ($im->width()/$im->height() > $thumbWidth/$thumbHeight)
                $im->resizeToHeight($thumbHeight);
            else
                $im->resizeToWidth($thumbWidth);
            $im->crop($thumbWidth, $thumbHeight, $cropFromTop, $cropFromLeft)
                ->saveAsJpeg($thumbnailFileName);
            $thumbnail['width'] = $im->width();
            $thumbnail['height'] = $im->height();
            $thumbnail['url'] = fbu($path['url'] . $im->filename());
            
            $im->revert();
        }
    
        if ($isGifAnimate) {
            $gifFile = 'gif_' . $file;
            $gifFileName = $path['path'] . $gifFile;
            $result = @file_put_contents($gifFileName, $im->outputRaw());
            if ($result) {
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


