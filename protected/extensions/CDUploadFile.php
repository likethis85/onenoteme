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
    

    public static function saveImage($upyunEnabled, $file, $referer = '', $opts = array())
    {
        $file = strip_tags(trim($file));
        $image = array();
        if (empty($file) || (!file_exists($file) && filter_var($file, FILTER_VALIDATE_URL) === false))
            return $image;
        
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
        
        if ($upyunEnabled)
            $image = self::saveImageToUpyun($im);
        else
            $image = self::saveImageToLocal($im);
    
        unset($im, $data);
        
        return $image;
    }
    
    public static function saveImageToUpyun(CDImage $im)
    {
        $original = array();
        set_time_limit(0);

        $isGifAnimate = $im->isAnimateGif();
        $uploader = upyunUploader(true);
        $path = self::makeUploadPath('pics', null, true);
        $urlpath = rtrim($path['path'], '/') . '/';
        $file = self::makeUploadFileName($im->getExtName());
        $filename = $urlpath . 'original_' . $file;
        
        try {
            $result = array();
    
            if ($isGifAnimate)
                $originalData = $im->rawData();
            else {
                // 水印，如果不加水印，这段没有必要加
                $text = '挖段子网';
                $font = Yii::getPathOfAlias('application.fonts') . DS . 'msyh.ttf';
                $color = array(200, 200, 200);
                if ($im->width() > IMAGE_WATER_SIZE) {
                    $im->text($text, $font, 24, CDImage::MERGE_BOTTOM_LEFT, $color);
                    $im->text('http://www.waduanzi.com', $font, 12, CDImage::MERGE_BOTTOM_RIGHT, $color);
                }
                $originalData = $im->outputRaw();
            }
    
            $result = $uploader->save($originalData, $filename);
            $original['width'] = (int)$result['x-upyun-width'];
            $original['height'] = (int)$result['x-upyun-height'];
            $original['url'] = $uploader->getFileUrl();
            $original['frames'] = $result['x-upyun-frames'];
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
    public static function saveImageToLocal(CDImage $im)
    {
        $original = array();
        set_time_limit(0);
    
        $isGifAnimate = $im->isAnimateGif();
        $savepath = self::makeUploadPath('pics');
        $filename = self::makeUploadFileName($im->getExtName());
        $originalFilename = 'original_' . $filename;
        $originalFilepath = $savepath['path'] . $originalFilename;
    
        if ($isGifAnimate) {
            $originalData = $im->rawData();
            $result = @file_put_contents($originalFilepath, $im->rawData());
            if ($result) {
                $original['url'] = localbu($savepath['url'] . $originalFilename);
                $original['frames'] = 2;
            }
        }
        else {
            $text = '挖段子网';
            $font = Yii::getPathOfAlias('application.fonts') . DS . 'msyh.ttf';
            $color = array(200, 200, 200);
             
            if ($im->width() > IMAGE_WATER_SIZE) {
                $im->text($text, $font, 24, CDImage::MERGE_BOTTOM_LEFT, $color);
                $im->text('http://www.waduanzi.com', $font, 12, CDImage::MERGE_BOTTOM_RIGHT, $color);
            }
            $im->saveAsJpeg($originalFilepath, 100);
            $original['url'] = localbu($savepath['url'] . $originalFilename);
            $original['frames'] = 1;
        }
        $original['width'] = $im->width();
        $original['height'] = $im->height();
        
        unset($im);
    
        return $original;
    }
}


