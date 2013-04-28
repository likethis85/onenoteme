<?php
class CDUploadedFile extends CUploadedFile
{
    private $_upyunEnabled = false;
    
    public function saveFileAs($file, $isImageFile = false, $deleteTempFile = true, $opts = null)
    {
        if ($this->_error == UPLOAD_ERR_OK) {
            $uploader = $this->_upyunEnabled ? upyunUploader($isImageFile) : app()->getComponent('localUploader', false);
            $content = file_get_contents($this->_tempName);
            $infos = $uploader->save($content, $file, $opts);
            if ($deleteTempFile)
                @unlink($this->_tempName);
            
            $infos['url'] = $uploader->getFileUrl();
            return $infos;
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
    
    public static function saveImage($upyunEnabled, $file, $pathPrefix = '', $referer = '', $opts = array(), $watermark = false)
    {
        $image = array();
        if (empty($file))
            return $image;
        
        // 获取文件内容
        if (file_exists($file) && is_readable($file)) {
            $data = file_get_contents($file);
        }
        elseif (filter_var($file, FILTER_VALIDATE_URL)) {
            $curl = new CDCurl();
            $curl->referer($referer)->get($file);
            $errno = $curl->errno();
            if ($errno != 0)
                throw new Exception($curl->error(), $errno);
        
            $data = $curl->rawdata();
            $curl->close();
        }
        else
            $data = $file;
        
        $im = new CDImage();
        $im->load($data);
        
        /* 检查是否有额外的参数选项，主要是去除头尾的版本水印及LOGO
         * 如果是动画，不作相关处理
         */
        if (!$im->isAnimateGif()) {
            if (!empty($opts)) {
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
            
            if ($watermark) {
                $text = '挖段子网';
                $font = Yii::getPathOfAlias('application.fonts') . DS . 'msyh.ttf';
                $color = array(200, 200, 200);
                 
                if ($im->width() > IMAGE_WATER_SIZE) {
                    $im->text($text, $font, 24, CDImage::MERGE_BOTTOM_LEFT, $color);
                    $im->text('http://www.waduanzi.com', $font, 12, CDImage::MERGE_BOTTOM_RIGHT, $color);
                    $im->setCurrentRawData();
                }
            }
        }
        
        if ($upyunEnabled)
            $image = self::saveImageToUpyun($im, $pathPrefix);
        else
            $image = self::saveImageToLocal($im, $pathPrefix);
    
        unset($im, $data);
        
        return $image;
    }
    
    public static function saveImageToUpyun(CDImage $im, $pathPrefix = null)
    {
        $original = array();
        set_time_limit(0);

        try {
            $uploader = upyunUploader(true);
            $paths = $uploader->autoFilename($im->getExtName(), $pathPrefix, 'original');
            
            $infos = $uploader->save($im->rawData());
            
            if (is_array($infos)) {
                $original = $infos;
                $original['url'] = $paths['absolute_url'];
            }
        }
        catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    
        unset($im);
    
        return $original;
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
    public static function saveImageToLocal(CDImage $im, $pathPrefix = null)
    {
        $original = array();
        set_time_limit(0);
    
        $uploader = localuploader();
        $paths = $uploader->autoFilename($im->getExtName(), $pathPrefix, 'original');
        $saveFilePath = $paths['absolute_path'];
        $saveFileUrl = $paths['absolute_url'];
        
        $isGifAnimate = $im->isAnimateGif();
        if ($isGifAnimate) {
            $originalData = $im->rawData();
            $result = @file_put_contents($saveFilePath, $im->rawData());
            if ($result) {
                $original['url'] = $saveFileUrl;
                $original['frames'] = 2;
            }
        }
        else {
            $im->saveAsJpeg($saveFilePath, 100);
            $original['url'] = $saveFileUrl;
            $original['frames'] = 1;
        }
        $original['mime'] = $im->getMimeType();
        $original['width'] = $im->width();
        $original['height'] = $im->height();
        
        unset($im);
    
        return $original;
    }
}


