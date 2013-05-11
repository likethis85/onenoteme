<?php
class CDImage
{
    const MERGE_TOP_LEFT = 1;
    const MERGE_TOP_CENTER = 2;
    const MERGE_TOP_RIGHT = 3;
    const MERGE_RIGHT_MIDDLE = 4;
    const MERGE_BOTTOM_RIGHT = 5;
    const MERGE_BOTTOM_CENTER = 6;
    const MERGE_BOTTOM_LEFT = 7;
    const MERGE_LEFT_MIDDLE = 8;
    const MERGE_CENTER_MIDDLE = 9;
    
    const CANVAS_VERTICAL_TOP = 1;
    const CANVAS_VERTICAL_MIDDLE = 2;
    const CANVAS_VERTICAL_BOTTOM = 3;
    const CANVAS_HORIZONTAL_LEFT = 4;
    const CANVAS_HORIZONTAL_CENTER = 5;
    const CANVAS_HORIZONTAL_RIGHT = 6;
    
    protected $_version = '1.0';
    protected $_author = 'Chris Chen(cdcchen@gmail.com)';
    protected $_site = 'http://www.24beta.com/';
    
    protected  $_image;
    protected $_data;
    protected $_imageType = IMAGETYPE_JPEG;
    
    protected static $_createFunctions = array(
        IMAGETYPE_GIF => 'imagecreatefromgif',
        IMAGETYPE_JPEG => 'imagecreatefromjpeg',
        IMAGETYPE_PNG => 'imagecreatefrompng',
        IMAGETYPE_WBMP => 'imagecreatefromwbmp',
        IMAGETYPE_XBM => 'imagecreatefromxmb',
    );
    
    protected static $_outputFuntions = array(
        IMAGETYPE_GIF => 'imagegif',
        IMAGETYPE_JPEG => 'imagejpeg',
        IMAGETYPE_PNG => 'imagepng',
        IMAGETYPE_WBMP => 'imagewbmp',
        IMAGETYPE_XBM => 'imagexmb',
    );
    
    public $fontpath;
    
    /**
     * 构造函数
     * @param string $data 图像路径或图像数据
     */
    public function __construct($data = null)
    {
        $path = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'fonts' . DIRECTORY_SEPARATOR;
        $this->setFontPath($path);
        
        if (null !== $data)
            $this->load($data);
    }
    
    public function setFontPath($path)
    {
        if (@is_dir($path)) {
            $this->fontpath = $path;
            return true;
        }
        else
            return false;
    }
    
    public function newImage($width, $height, $bgcolor = '#FFFFFF', $alpha = 0, $type = IMAGETYPE_PNG)
    {
        $this->_image = imagecreatetruecolor($width, $height);
        $this->_imageType = $type;
        imagealphablending($this->_image, false);
        imagesavealpha($this->_image, true);
        $color = self::colorAllocateAlpha($this->_image, $bgcolor, $alpha);
        imagefill($this->_image, 0, 0, $color);
        
        return $this;
    }
    
    /**
     * 从文件地址载入图像
     * @param string $data 图像路径或图像数据
     * @return CDImage CDImage对象本身
     */
    public function load($data)
    {
        $this->_data = $data;
        $this->_image = self::loadImage($this->_data);
        if (@is_file($data))
            $info = @getimagesize($data);
        elseif (PHP_VERSION > '5.4.0')
            $info = @getimagesizefromstring($data);
        
        if ($info)
            $this->_imageType = $info[2];
        
        return $this;
    }
    
    /**
     * 从文件地址载入图像
     * @param string $file 图像路径
     * @return resource 图像资源
     */
    public static function loadFromFile($file)
    {
        $info = getimagesize($file);
        $type = $info[2];
        if (!array_key_exists($type, self::$_createFunctions))
            throw new CDImageException('不支持' . $type . '图像格式', 0);
        $func = self::$_createFunctions[$type];
        $image = $func($file);
        
        return $image;
    }
    
    /**
     * 从图像数据流载入图像
     * @param string $filename 图像数据
     * @return resource 图像资源
     */
    public static function loadFromStream($data)
    {
        $im = imagecreatefromstring($data);
        return $im;
    }
    
    /**
     * @param string $data 图像路径或图像数据
     * @return resource 图像资源
     */
    public static function loadImage($data)
    {
        if (@is_file($data)) {
            $image = self::loadFromFile($data);
        }
        else
            $image = self::loadFromStream($data);
        
        return $image;
    }
    
    /**
     * 返回最原始的文件内容
     * @return string
     */
    public function rawData()
    {
        return $this->_data;
    }
    
    /**
     * 设置原始数据，主要用于处理图片后作为原始数据，以用于后续所有操作
     * @param string 原始文件数据
     * @return CDImage
     */
    public function setRawData($data)
    {
        $this->_data = $data;
        return $this;
    }
    
    /**
     * 将当前处理进度的数据作为原始数据
     * @return CDImage
     */
    public function setCurrentRawData()
    {
        $this->setRawData($this->outputRaw());
        return $this;
    }
    
    /**
     * 返回图像宽度
     * @return integer 图像的宽度
     */
    public function width()
    {
        return imagesx($this->_image);
    }
    
    /**
     * 返回图像高度
     * @return integer 图像高度
     */
    public function height()
    {
        return imagesy($this->_image);
    }
    
    /**
     * 将图片数据还原为初始值
     * @return CDImage CDImage对象本身
     */
    public function revert()
    {
        $this->_image = $this->loadImage($this->_data);
        return $this;
    }
    
    public function getType()
    {
        return $this->_imageType;
    }
    
    public function getMimeType()
    {
        return image_type_to_mime_type($this->_imageType);
    }
    
    public function getExtName($include_dot = true)
    {
        return image_type_to_extension($this->_imageType, $include_dot);
    }
    
    
    public function convertType($type)
    {
        if (!array_key_exists($type, self::$_createFunctions))
            throw new CDImageException('不支持此类型', 0);
        $this->_imageType = $type;
    }
    
    /**
     * 保存图像到一个文件中
     * @param string $filename 图片文件路径，不带扩展名
     * @param integer $mode 图像文件的权限
     * @return CDImage CDImage对象本身
     */
    public function save($filename, $mode = null)
    {
//         $filename .= $this->getExtName();
        $func = self::$_outputFuntions[$this->_imageType];
        self::saveAlpha($this->_image);
        if (!$func($this->_image, $filename))
            return false;
        
        if ($mode !== null) {
            chmod($filename, $mode);
        }
        return $this;
    }
    
    /**
     * 将图像保存为gif类型
     * @param string $filename 图片文件路径，不带扩展名
     * @param integer $mode 图像文件的权限
     * @return CDImage CDImage对象本身
     */
    public function saveAsGif($filename, $mode = null)
    {
//         $filename .= image_type_to_extension(IMAGETYPE_GIF);
        if (!imagegif($this->_image, $filename))
            return false;
        
        if ($mode !== null) {
            chmod($filename, $mode);
        }
        return $this;
    }
    
    /**
     * 将图像保存为jpeg类型
     * @param string $filename 图片文件路径，不带扩展名
     * @param integer $quality 图像质量，取值为0-100
     * @param integer $mode 图像文件的权限
     * @return CDImage CDImage对象本身
     */
    public function saveAsJpeg($filename, $quality = 75, $mode = null)
    {
//         $filename .= image_type_to_extension(IMAGETYPE_JPEG);
        if (!imagejpeg($this->_image, $filename, $quality))
            return false;
        
        if ($mode !== null) {
            chmod($filename, $mode);
        }
        return $this;
    }

	/**
     * 将图像保存为png类型
     * @param string $filename 图片文件路径，不带扩展名
     * @param integer $quality 图像质量，取值为0-9
     * @param integer $filters PNG图像过滤器，取值参考imagepng函数
     * @param integer $mode 图像文件的权限
     * @return CDImage CDImage对象本身
     */
    public function saveAsPng($filename, $quality = 9, $filters = 0, $mode = null)
    {
//         $filename .= image_type_to_extension(IMAGETYPE_PNG);
        self::saveAlpha($this->_image);
        if (!imagepng($this->_image, $filename, $quality, $filters))
            return false;
        
        if ($mode !== null) {
            chmod($filename, $mode);
        }
        return $this;
    }
    
    /**
     * 将图像保存为wbmp类型
     * @param string $filename 图片文件路径，不带扩展名
     * @param integer $foreground 前景色，取值为imagecolorallocate()的返回的颜色标识符
     * @param integer $mode 图像文件的权限
     * @return CDImage CDImage对象本身
     */
    public function saveAsWbmp($filename, $foreground  = 0, $mode = null)
    {
//         $filename .= image_type_to_extension(IMAGETYPE_WBMP);
        if (!imagewbmp($this->_image, $filename, $foreground))
            return false;
        
        if ($mode !== null) {
            chmod($filename, $mode);
        }
        return $this;
    }
    
    /**
     * 将图像保存为xbm类型
     * @param string $filename 图片文件路径，不带扩展名
     * @param integer $foreground 前景色，取值为imagecolorallocate()的返回的颜色标识符
     * @param integer $mode 图像文件的权限
     * @return CDImage CDImage对象本身
     */
    public function saveAsXbm($filename, $foreground  = 0, $mode = null)
    {
//         $filename .= image_type_to_extension(IMAGETYPE_XBM);
        if (!imagexbm($this->_image, $filename, $foreground))
            return false;
        
        if ($mode !== null) {
            chmod($filename, $mode);
        }
        return $this;
    }

    /**
     * 按照图像原来的类型输出图像数据
     */
    public function output()
    {
        $contentType = image_type_to_mime_type($this->_imageType);
        header('Content-Type: ' . $contentType);
        $func = self::$_outputFuntions[$this->_imageType];
        $func($this->_image);
    }
    
    /**
     * 以gif类型输出图像数据
     */
    public function outputGif()
    {
        header('Content-Type: ' . image_type_to_mime_type(IMAGETYPE_GIF));
        imagegif($this->_image);
    }
    
    /**
     * 以jpge类型输出图像数据
     */
    public function outputJpeg($quality = 75)
    {
        header('Content-Type: ' . image_type_to_mime_type(IMAGETYPE_JPEG));
        imagejpeg($this->_image, null, $quality);
    }

    /**
     * 以png类型输出图像数据
     * @param integer $quality 图像质量，取值为0-9
     * @param integer $filters PNG图像过滤器，取值参考imagepng函数
     */
    public function outputPng($quality = 9, $filters = 0)
    {
        header('Content-Type: ' . image_type_to_mime_type(IMAGETYPE_PNG));
        imagepng($this->_image, null, $quality, $filters);
    }
    
    /**
     * 以wbmp类型输出图像数据
     */
    public function outputWbmp($foreground  = 0)
    {
        header('Content-Type: ' . image_type_to_mime_type(IMAGETYPE_WBMP));
        imagewbmp($this->_image, null, $foreground);
    }
    
    /**
     * 以xbm类型输出图像数据
     */
    public function outputXbm($foreground  = 0)
    {
        header('Content-Type: ' . image_type_to_mime_type(IMAGETYPE_XBM));
        imagewxbm($this->_image, null, $foreground);
    }
    
    public function outputRaw()
    {
        ob_start();
        $func = self::$_outputFuntions[$this->_imageType];
        $func($this->_image);
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
    
    public function outputRawGif()
    {
        ob_start();
        imagegif($this->_image);
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
    
    public function outputRawJpeg($quality = 100)
    {
        ob_start();
        imagejpeg($this->_image, null, $quality);
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
    
    public function outputRawPng($quality = 10, $filters = 0)
    {
        ob_start();
        imagepng($this->_image, null, $quality, $filters);
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
    
    public function outputRawWbmp($foreground  = 0)
    {
        ob_start();
        imagewbmp($this->_image, null, $foreground);
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
    
    public function outputRawXbm($foreground  = 0)
    {
        ob_start();
        imagewxbm($this->_image, null, $foreground);
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
    
    /**
     * 等比例绽放图像
     * @param integer $scale 绽放值，取值为0-100
     * @return CDImage CDImage对象本身
     */
    public function scale($scale)
    {
        $width = $this->width() * $scale/100;
        $height = $this->height() * $scale/100;
        $this->resize($width, $height);
        return $this;
    }
    
    /**
     * 根据设定高度等比例绽放图像
     * @param integer $height 图像高度
     * @return CDImage CDImage对象本身
     */
    public function resizeToHeight($height)
    {
        if ($height >= $this->height())
            return $this;
        $ratio = $height / $this->height();
        $width = $this->width() * $ratio;
        $this->resize($width, $height);
        return $this;
    }

	/**
     * 根据设定宽度等比例绽放图像
     * @param integer $width 图像宽度
     * @return CDImage CDImage对象本身
     */
    public function resizeToWidth($width)
    {
        if ($width >= $this->width())
            return $this;
        $ratio = $width / $this->width();
        $height = $this->height() * $ratio;
        $this->resize($width, $height);
        return $this;
    }
    
    /**
     * 改变图像大小
     * @param integer $width 图像宽度
     * @param integer $height 图像高度
     * @return CDImage CDImage对象本身
     */
    public function resize($width, $height)
    {
        $image = imagecreatetruecolor($width, $height);
        self::saveAlpha($this->_image);
        self::saveAlpha($image);
        imagecopyresampled($image, $this->_image, 0, 0, 0, 0, $width, $height, $this->width(), $this->height());
        $this->_image = $image;
        return $this;
    }
    
    /**
     * 裁剪图像
     * @param integer $width 图像宽度
     * @param integer $height 图像高度
     * @return CDImage CDImage对象本身
     */
    public function crop($width, $height, $fromTop = false, $fromLeft = false)
    {
        $image = imagecreatetruecolor($width, $height);
        $ow = $this->width();
        $oh = $this->height();
        $wm = $ow / $width;
        $hm = $oh / $height;
        $h_height = $height / 2;
        $w_height = $width / 2;
        
        $oscale = $ow / $oh;
        $nscale = $width / $height;
        if ($oscale >= $nscale) {
            $adjusted_width = $ow / $hm;
            $half_width = $adjusted_width / 2;
            $int_width = $half_width - $w_height;
            $dstX = $fromLeft? 0 : -$int_width;
            imagecopyresampled($image, $this->_image, $dstX, 0, 0, 0, $adjusted_width, $height, $ow, $oh);
        }
        else {
            $adjusted_height = $oh / $wm;
            $half_height = $adjusted_height / 2;
            $int_height = $half_height - $h_height;
            $dstY = $fromTop ? 0 : -$int_height;
            imagecopyresampled($image, $this->_image, 0, $dstY, 0, 0, $width, $adjusted_height, $ow, $oh);
        }
        $this->_image = $image;
        return $this;
    }
    
    public function cropByFrame($width, $height, $x = 0, $y = 0)
    {
        if (($x + $width) > $this->width())
            $width = $this->width() - $x;
        if (($y + $height) > $this->height())
            $height = $this->height() - $y;
        
        $image = imagecreatetruecolor($width, $height);
        imagecopyresampled($image, $this->_image, 0, 0, $x, $y, $width, $height, $width, $height);
        
        $this->_image = $image;
        return $this;
    }

    /**
     * 顺时针旋转图片
     * @param integer $degree 取值为0-360
     * @return CDImage CDImage对象本身
     */
    public function rotate($degree = 90)
    {
        $degree = (int)$degree;
        $this->_image = imagerotate($this->_image, $degree, 0);
        return $this;
    }
    
    /**
     * 将图像转换为灰度的
     * @return CDImage CDImage对象本身
     */
    public function gray()
    {
        imagefilter($this->_image, IMG_FILTER_GRAYSCALE);
        return $this;
    }
    
    /**
     * 将图像颜色反转
     * @return CDImage CDImage对象本身
     */
    public function negate()
    {
        imagefilter($this->_image, IMG_FILTER_NEGATE);
        return $this;
    }
    
    /**
     * 调整图像亮度
     * @param integer $bright 亮度值
     * @return CDImage CDImage对象本身
     */
    public function brightness($bright)
    {
        $bright = (int)$bright;
        ($bright > 0) && imagefilter($this->_image, IMG_FILTER_BRIGHTNESS, $bright);
        return $this;
    }
    
    /**
     * 调整图像对比度
     * @param integer $contrast 对比度值
     * @return CDImage CDImage对象本身
     */
    public function contrast($contrast)
    {
        $contrast = (int)$contrast;
        ($contrast > 0) && imagefilter($this->_image, IMG_FILTER_CONTRAST, $contrast);
        return $this;
    }
    
    /**
     * 将图像浮雕化
     * @return CDImage CDImage对象本身
     */
    public function emboss()
    {
        imagefilter($this->_image, IMG_FILTER_EMBOSS,0);
        return $this;
    }
    
    /**
     * 让图像柔滑
     * @param integer $smooth 柔滑度值
     * @return CDImage CDImage对象本身
     */
    public function smooth($smooth)
    {
        $smooth = (int)$smooth;
        ($smooth > 0) && imagefilter($this->_image, IMG_FILTER_SMOOTH, $smooth);
        return $this;
    }

    /**
     * 将图像使用高斯模糊
     * @return CDImage CDImage对象本身
     */
    public function blur()
    {
        imagefilter($this->_image, IMG_FILTER_GAUSSIAN_BLUR);
        return $this;
    }
    
    function mosaic($x1, $y1, $x2, $y2, $deep)
     {
         for ($x=$x1; $x < $x2; $x+=$deep) {
             for ($y=$y1; $y<$y2; $y+=$deep) {
                 $color = imagecolorat($this->_image, $x + round($deep / 2), $y + round($deep / 2));
                 imagefilledrectangle($this->_image, $x, $y, $x + $deep, $y + $deep, $color);
             }
         }
         return $this;
     }
    
    /**
     * 在图像上添加文字
     * @param string $text 添加的文字
     * @param integer $opacity 不透明度，值为0-1
     * @param integer|array $position 文字添加位置
     * @param string $font 字体文件路径
     * @param integer $size 文字大小
     * @param array|integer $color 颜色值
     * @return CDImage CDImage对象本身
     */
    public function text($text, $font, $size, $position = self::MERGE_BOTTOM_RIGHT, $color = array(0, 0, 0), $alpha = 0, $padding = 5, $angle = 0)
    {
        if (is_int($position))
            $pos = $this->textPosition($text, $font, $size, $position, $padding);
        elseif (is_array($position))
            $pos = $position;
        else
            throw new CDImageException('position error.');
        
        $color = self::colorAllocateAlpha($this->_image, $color, $alpha);
        imagettftext($this->_image, $size, $angle, $pos[0], $pos[1], $color, $font, $text);

        return $this;
    }
    
    function textborder ($text, $fontfile, $size, $position = self::MERGE_BOTTOM_RIGHT, $color = array(0, 0, 0), $outer = array(255, 255, 255), $alpha = 0, $padding = 5, $angle = 0)
	{
		if (is_int($position))
            $pos = $this->textPosition($text, $fontfile, $size, $position, $padding);
        elseif (is_array($position))
            $pos = $position;
        else
            throw new CDImageException('position error.');
	    
        $x = (int)$pos[0];
        $y = (int)$pos[1];
	    self::textouter($this->_image, $text, $fontfile, $size, $x, $y, $color, $outer, $alpha, $padding, $angle);
	    return $this;
	}
    
    public function textPosition($text, $font, $size, $position, $padding = 5, $angle = 0)
    {
        if (is_array($position))
            return $position;
        
        if (@is_file($font))
            $points = imagettfbbox($size, $angle, $font, $text);
        else {
            $width = strlen($text) * 9;
            $height = 16;
            // 引处需要注意imagestring跟imagettftext的起始坐标意义是不一样的
            $points = array(0, $height, $width, 0, $width, -$size, 0, -$size);
        }
//         print_r($points);exit;
        $imWidth = $this->width();
        $imHeight = $this->height();
        $textWidth = $points[2] - $points[0];
        $textHeight = $points[1] - $points[7];
        switch ($position) {
            case self::MERGE_TOP_LEFT:
                $x = $points[0] + $padding;
                $y = $textHeight + $padding;
                break;
            case self::MERGE_TOP_CENTER:
                $x = ($imWidth - $textWidth) / 2;
                $y = $textHeight + $padding;
                break;
            case self::MERGE_TOP_RIGHT:
                $x = $imWidth - $textWidth - $padding;
                $y = $textHeight + $padding;
                break;
            case self::MERGE_BOTTOM_LEFT:
                $x = $points[0] + $padding;
                $y = $imHeight - $points[1] - $padding;
                break;
            case self::MERGE_BOTTOM_CENTER:
                $x = ($imWidth - $textWidth) / 2;
                $y = $imHeight - $points[1] - $padding;
                break;
            case self::MERGE_BOTTOM_RIGHT:
                $x = $imWidth - $textWidth - $padding;
                $y = $imHeight - $points[1] - $padding;
                break;
            case self::MERGE_RIGHT_MIDDLE:
                $x = $imWidth - $textWidth - $padding;
                $y = $imHeight/2 + $textHeight/2;
                break;
            case self::MERGE_LEFT_MIDDLE:
                $x = $points[0] + $padding;
                $y = $imHeight/2 + $textHeight/2;
                break;
            case self::MERGE_CENTER_MIDDLE:
                $x = ($imWidth - $textWidth) / 2;
                $y = $imHeight/2 + $textHeight/2;
                break;
            default:
                break;
        }
    
        return array(intval($x), intval($y));
    }
    
    /**
     * 将一个图像合并到画布上
     * @param string $data 图像的二进制数据
     * @param constant $position 合并位置
     * @param integer $opacity 不透明度，取值为0-100
     */
    public function merge($data, $position = self::MERGE_BOTTOM_RIGHT, $opacity = 100)
    {
        if (is_resource($data))
            $src = $data;
        elseif ($data instanceof CDImage)
            $src = $data->getImageInstance();
        else
              $src = self::loadImage($data);
        
        if (!is_resource($src))
            throw new CDImageException('图像数据错误', 0);

        if (is_int($position))
            $pos = self::mergePosition($position, $this->_image, $src);
        elseif (is_array($position))
            $pos = $position;
        else
            throw new CDImageException('position error.');
        
        $w = imagesx($src);
        $h = imagesy($src);
        $image = imagecreatetruecolor($w, $h);
        imagealphablending($this->_image, true);
        imagealphablending($image, true);
        
        imagecopyresampled($image, $this->_image, 0, 0, $pos[0], $pos[1], $w, $h, $w, $h);
        self::saveAlpha($src);
        imagecopy($image, $src, 0, 0, 0, 0, $w, $h);
        imagecopymerge($this->_image, $image, $pos[0], $pos[1], 0, 0, $w, $h, $opacity);
        
        return $this;
    }
    
    public function getImageInstance()
    {
        return $this->_image;
    }
    
    public static function mergePosition($position, $dst, $src, $padding = 0)
    {
        if (is_array($position))
            return $position;
        
        if (is_resource($src)) {
            $srcW = imagesx($src);
            $srcH = imagesy($src);
        }
        elseif ($src instanceof CDImage) {
            $srcW = $src->width();
            $srcH = $src->height();
        }
        else
            return false;
        
        if (is_resource($dst)) {
            $dstW = imagesx($dst);
            $dstH = imagesy($dst);
        }
        elseif ($dst instanceof CDImage) {
            $dstW = $dst->width();
            $dstH = $dst->height();
        }
        else
            return false;
        
        switch ($position) {
            case self::MERGE_TOP_LEFT:
                $x = $y = $padding;
                break;
            case self::MERGE_TOP_CENTER:
                $x = ($dstW - $srcW) / 2;
                $y =  $padding;
                break;
            case self::MERGE_TOP_RIGHT:
                $x = $dstW - $srcW - $padding;
                $y = $padding;
                break;
            case self::MERGE_BOTTOM_LEFT:
                $x = $padding;
                $y = $dstH - $srcH - $padding;
                break;
            case self::MERGE_BOTTOM_CENTER:
                $x = ($dstW - $srcW) / 2;
                $y = $dstH - $srcH - $padding;
                break;
            case self::MERGE_BOTTOM_RIGHT:
                $position = array(0, $dstH-$srcH);
                $x = $dstW - $srcW - $padding;
                $y = $dstH - $srcH - $padding;
                break;
            case self::MERGE_CENTER_MIDDLE:
                $x = ($dstW - $srcW) / 2;
                $y = ($dstH - $srcH) / 2;
                break;
            case self::MERGE_LEFT_MIDDLE:
                $x = $padding;
                $y = ($dstH - $srcH) / 2;
                break;
            case self::MERGE_RIGHT_MIDDLE:
                $x = $dstW - $srcW - $padding;
                $y = ($dstH - $srcH) / 2;
                break;
            default:
                break;
        }
        
        return array(intval($x), intval($y));
    }

    public static function saveAlpha($im)
    {
        imagealphablending($im, false);
        imagesavealpha($im, true);
    }
    
    public function getSite()
    {
        return $this->_site;
    }
    
    public function getVersion()
    {
        return $this->_version;
    }
    
    public function getAuthor()
    {
        return $this->_author;
    }

    public function isAnimateGif()
    {
        return self::isGifAnimate($this->_data, true);
    }
    
    public static function isGifAnimate($file, $isdata = false)
    {
        if ($isdata)
            $data = $file;
        else {
            $fp = fopen($file, 'rb');
            $data = fread($fp, 1024);
            fclose($fp);
        }
        $p = chr(0x21).chr(0xff).chr(0x0b).chr(0x4e).chr(0x45).chr(0x54).chr(0x53).chr(0x43).chr(0x41).chr(0x50).chr(0x45).chr(0x32).chr(0x2e).chr(0x30);
        return (bool)preg_match("~${p}~", $data);
    }
    
    public static function getImageInfo($file)
    {
        $info = null;
        if (@is_file($file))
            $info = getimagesize($file);
        elseif (PHP_VERSION > '5.4.0')
            $info = getimagesizefromstring($file);
        
        return $info;
    }
    
    public static function getImageType($file)
    {
        $info = self::getImageInfo($file);
        $imagetype = IMAGETYPE_UNKNOWN;
        if ($info)
            $imagetype = $info[2];
        
        return $imagetype;
    }
    
    public static function getImageExtName($file, $include_dot = true)
    {
        $imagetype = self::getImageType($file);
        
        $extension = '';
        if ($imagetype != IMAGETYPE_UNKNOWN)
            $extension = image_type_to_extension($imagetype, $include_dot);
        
        return $extension;
    }
    
    public static function frameCount($data)
    {
        if (@is_file($data))
            $data = file_get_contents($data);
        
        $images = explode("\x00\x21\xF9\x04", $data);
        $count = count($images);
        $images = null;
        return $count;
    }
    
    public static function colorAllocateAlpha ($im, $color, $alpha = 0)
	{
	    if (is_int($color))
	        return $color;
		elseif (is_array($color))
			return imagecolorallocatealpha($im, $color[0], $color[1], $color[2], $alpha);
		elseif ($color{0} == '#') {
			$color = substr($color, 1);
			$bg_dec = hexdec($color);
	    	return imagecolorallocatealpha($im,
		    	($bg_dec & 0xFF0000) >> 16,
		    	($bg_dec & 0x00FF00) >> 8,
		    	($bg_dec & 0x0000FF),
		    	$alpha);
		}
		else
			throw new CDImageException('color value is invalid.');
	}
	
	public static function textouter ($im, $text, $fontfile, $size, $x, $y, $color = array(0, 0, 0), $outer = array(255, 255, 255), $alpha = 0, $padding = 5, $angle = 0)
	{
	    $x = (int)$x;
	    $y = (int)$y;
	     
	    $ttf = false;
	    if (@is_file($fontfile)) {
	        $ttf = true;
	        $area = imagettfbbox($size, $angle, $fontfile, $text);
	        $width = $area[2] - $area[0] + 2;
	        $height = $area[1] - $area[5] + 2;
	    }
	    else {
	        $width = strlen($text) * 10;
	        $height = 16;
	    }
	     
	    $im_tmp = imagecreate($width, $height);
	    $white = imagecolorallocatealpha($im_tmp, 255, 255, 255, $alpha);
	    $black = imagecolorallocatealpha($im_tmp, 0, 0, 0, $alpha);
	     
	    $color = self::colorAllocateAlpha($im, $color, $alpha);
	    $outer = self::colorAllocateAlpha($im, $outer, $alpha);
	     
	    if ($ttf) {
	        imagettftext($im_tmp, $size, 0, 0, $height - 2, $black, $fontfile, $text);
	        imagettftext($im, $size, 0, $x, $y, $color, $fontfile, $text);
	        $y = $y - $height + 2;
	    }
	    else {
	        imagestring($im_tmp, $size, 0, 0, $text, $black);
	        imagestring($im, $size, $x, $y, $text, $color);
	    }
	     
	    for ($i = 0; $i < $width; $i ++) {
	        for ($j = 0; $j < $height; $j ++) {
	            $c = imagecolorat($im_tmp, $i, $j);
	            if ($c !== $white) {
	                imagecolorat($im_tmp, $i, $j - 1) != $white || imagesetpixel($im, $x + $i, $y + $j - 1, $outer);
	                imagecolorat($im_tmp, $i, $j + 1) != $white || imagesetpixel($im, $x + $i, $y + $j + 1, $outer);
	                imagecolorat($im_tmp, $i - 1, $j) != $white || imagesetpixel($im, $x + $i - 1, $y + $j, $outer);
	                imagecolorat($im_tmp, $i + 1, $j) != $white || imagesetpixel($im, $x + $i + 1, $y + $j, $outer);
	                 
	                // 取消注释，与Fireworks的发光效果相同
	                imagecolorat($im_tmp, $i - 1, $j - 1) != $white || imagesetpixel($im, $x + $i - 1, $y + $j - 1, $outer);
	                imagecolorat($im_tmp, $i + 1, $j - 1) != $white || imagesetpixel($im, $x + $i + 1, $y + $j - 1, $outer);
	                imagecolorat($im_tmp, $i - 1, $j + 1) != $white || imagesetpixel($im, $x + $i - 1, $y + $j + 1, $outer);
	                imagecolorat($im_tmp, $i + 1, $j + 1) != $white || imagesetpixel($im, $x + $i + 1, $y + $j + 1, $outer);
	            }
	        }
	    }
	     
	    imagedestroy($im_tmp);
	    return $this;
	}
	
	public function resizeCanvas($append, $width = 0, $height = 0, $horizontal = self::CANVAS_HORIZONTAL_LEFT, $vertical = self::CANVAS_VERTICAL_TOP, $color = '#FFFFFF', $alpha = 0)
	{
	    $append = (bool)$append;
	    $width = (int)$width;
	    $height = (int)$height;
	    if (($width === 0 && $height === 0) || !$append && ($width === 0 || $height === 0))
	        throw new CDImageException('width or height value is invalid.');
	
	    $imWidth = $this->width();
	    $imHeight = $this->height();
	    if (!$append && ($width < $imWidth || $height < $imHeight))
	        throw new CDImageException('please call crop method first');
	
	    if ($append) {
	        $width += $imWidth;
	        $height += $imHeight;
	    }
	
	    switch ($horizontal)
	    {
	        case self::CANVAS_HORIZONTAL_CENTER:
	            $dstX = ($width - $imWidth) / 2;
	            break;
	        case self::CANVAS_HORIZONTAL_RIGHT:
	            $dstX = $width - $imWidth;
	            break;
	        case self::CANVAS_HORIZONTAL_LEFT:
	        default:
	            $dstX = 0;
	            break;
	    }
	
	    switch ($vertical)
	    {
	        case self::CANVAS_VERTICAL_MIDDLE:
	            $dstY = ($height - $imHeight) / 2;
	            break;
	        case self::CANVAS_VERTICAL_BOTTOM:
	            $dstY = $height - $imHeight;
	            break;
	        case self::CANVAS_VERTICAL_TOP:
	        default:
	            $dstY = 0;
	            break;
	    }
	
	    $im = imagecreatetruecolor($width, $height);
	    $white = imagecolorallocate($im, 255, 255, 255);
	    imagefill($im, 0, 0, $white);
	    imagealphablending($im, true);
	    $color = self::colorAllocateAlpha($im, $color, $alpha);
	    imagefill($im, 0, 0, $color);
	    imagecopymerge($im, $this->_image, $dstX, $dstY, 0, 0, $imWidth, $imHeight, 100);
	    $this->_image = $im;
	    $im = null;
	}
    
    /**
     * 析构函数
     */
    public function __destruct()
    {
        is_resource($this->_image) && imagedestroy($this->_image);
    }
}

class CDImageException extends Exception
{
}


