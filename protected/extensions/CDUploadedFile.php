<?php
class CDUploadedFile
{
    public static function saveImage($upyunEnabled, $file, $pathPrefix = '', $referer = '', $opts = array())
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
        
        self::processImage($im, $opts);
        
        if ($upyunEnabled)
            $image = self::saveImageToUpyun($im, $pathPrefix);
        else
            $image = self::saveImageToLocal($im, $pathPrefix);
    
        $im = $data = null;
        
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
    
        $im = null;
    
        return $original;
    }
    
    /**
     * 将远程图片保存在本地服务器
     * @param resource $im
     * @param string $pathPrefix
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
        
        $im = null;
    
        return $original;
    }
    
    public static function processImage($im, $opts)
    {
        /* 检查是否有额外的参数选项，主要是去除头尾的版本水印及LOGO
         * 如果是动画，不作相关处理
        */
        if (!$im->isAnimateGif()) {
            if (!empty($opts)) {
                $defaultOptions = array(
                    'padding_top' => 0,
                    'padding_bottom' => 0,
                    'water_position' => 0,
                );
                if (is_array($opts)) {
                    foreach ($opts as $key => $value) {
                        if (!array_key_exists($key, $defaultOptions))
                            throw new CDException($key . ' is invalid.');
                    }
                }
                else
                    throw new CDException('$opts must be an array.');
        
                // 裁剪图片
                $top = (int)$opts['padding_top'];
                $bottom = (int)$opts['padding_bottom'];
                if ((array_key_exists('padding_top', $opts) && $top > 0) ||
                (array_key_exists('padding_bottom', $opts) && $bottom > 0)) {
                    $width = $im->width();
                    $height = $im->height() - $top - $bottom;
                    $im->cropByFrame($width, $height, 0, $top);
                    $im->setCurrentRawData();
                }
        
                // 添加水印
                $waterPosition = (int)$opts['water_position'];
                if (array_key_exists('water_position', $opts) && $waterPosition > 0) {
                    $imWidth = $im->width();
                    if ($imWidth > IMAGE_WATER_SITENAME_SIZE) {
                        $cnfont = Yii::getPathOfAlias('application.fonts') . DS . 'Hiragino_Sans_GB_W6.otf';
                        $water = new CDWaterMark(CDWaterMark::TYPE_TEXT);
                        $water->position($waterPosition)
                            ->color('#F0F0F0')
                            ->borderColor('#333333')
                            ->font($cnfont)
                            ->fontsize(22)
                            ->setText('挖段子网')
                            ->applyText($im, 5, 10);
        
                        $im->setCurrentRawData();
                    }
                    elseif ($imWidth > IMAGE_WATER_URL_SIZE) {
                        $enfont = Yii::getPathOfAlias('application.fonts') . DS . 'HelveticaNeueLTPro-Hv.otf';
                        $water = new CDWaterMark(CDWaterMark::TYPE_TEXT);
                        $water->position($waterPosition)
                            ->color('#F0F0F0')
                            ->borderColor('#333333')
                            ->font($enfont)
                            ->fontsize(12)
                            ->setText('waduanzi.com')
                            ->applyText($im, 5, 10);
        
                        $im->setCurrentRawData();
                    }
                }
            }
        }
        
        return $im;
    }
}


