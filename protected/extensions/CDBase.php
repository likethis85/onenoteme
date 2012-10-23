<?php
class CDBase
{
    const FILE_NO_EXIST = -1; // '目录不存在并且无法创建';
    const FILE_NO_WRITABLE = -2; // '目录不可写';
    const VERSION = '1.0';
    

    public static function encryptPassword($password)
    {
        $pwd = '';
        if (!empty($password))
            $pwd = md5($password);
        
        return $pwd;
    }
    
    /**
     * 获取客户端IP地址
     * @return string 客户端IP地址
     */
    public static function getClientIp()
    {
        if ($_SERVER['HTTP_CLIENT_IP']) {
	      $ip = $_SERVER['HTTP_CLIENT_IP'];
	 	} elseif ($_SERVER['HTTP_X_FORWARDED_FOR']) {
	      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	 	} else {
	      $ip = $_SERVER['REMOTE_ADDR'];
	 	}

        return $ip;
    }
    
    /**
     * 返回上传后的文件路径
     * @return string|Array 如果成功则返回路径地址，如果失败则返回错误号和错误信息
     * -1 目录不存在并且无法创建
     * -2 目录不可写
     */
    public static function makeUploadPath($additional = null)
    {
        $relativeUrl = (($additional === null) ? '' : $additional . '/')
            . date('Y/m/d/', $_SERVER['REQUEST_TIME']);
        $relativePath = (($additional === null) ? '' : $additional . DS)
            . date(addslashes(sprintf('Y%sm%sd%s', DS, DS, DS)), $_SERVER['REQUEST_TIME']);

        $path = param('uploadBasePath') . $relativePath;

        if (!file_exists($path) && !mkdir($path, 0755, true)) {
            return self::FILE_NO_EXIST;
        } else if (!is_writable($path)) {
            return self::FILE_NO_WRITABLE;
        } else
            return array(
            	'path' => realpath($path) . DS,
                'url' => $relativeUrl,
            );
    }

    /**
     * 生成文件名
     * @param string $filename 软件名
     * @return string 转化之后的名称
     */
    public static function makeUploadFileName($extension)
    {
        $extension = strtolower($extension);
        return date('YmdHis_', $_SERVER['REQUEST_TIME'])
            . uniqid()
            . ($extension ? '.' . $extension : '');
    }
    
    
    public static function jsonp($callback, $data, $exit = true)
    {
        if (empty($callback))
            throw new CException('callback is not allowed empty');
    
        echo $callback . '(' . CJSON::encode($data) . ')';
        if ($exit) exit(0);
    }

    public static function isHttpUrl($url)
    {
        $url = trim($url);
        $pos = stripos($url, 'http://');
        return $pos === 0;
    }


    public static function saveRemoteImages($url, $thumbWidth, $thumbHeight, $cropFromTop = false, $cropFromLeft = false)
    {
        $url = strip_tags(trim($url));
        
        $images = array();
        if (!empty($url)) {
             
            $path = CDBase::makeUploadPath('pics');
            $info = parse_url($url);
            $extensionName = pathinfo($info['path'], PATHINFO_EXTENSION);
            $file = CDBase::makeUploadFileName('');
            $thumbnailFile = 'thumbnail_' . $file;
            $thumbnailFileName = $path['path'] . $thumbnailFile;
            $middleFileName = $path['path'] . 'bmiddle_' . $file;
            $bigFile = 'original_' . $file;
            $bigFileName = $path['path'] . $bigFile;
        
            $curl = new CDCurl();
            $curl->get($url);
            $data = $curl->rawdata();
            $curl->close();
            $im = new CDImage();
            $im->load($data);
            unset($data, $curl);
        
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
            if ($im->width() > IMAGE_BMIDDLE_MAX_WIDTH)
                $im->resizeToWidth(IMAGE_BMIDDLE_MAX_WIDTH);
            $im->saveAsJpeg($middleFileName, 75);
            $middle['url'] = fbu($path['url'] . $im->filename());
            $middle['width'] = $im->width();
            $middle['height'] = $im->height();
             
            $im->revert()->saveAsJpeg($bigFileName, 100);
            $original['url'] = fbu($path['url'] . $im->filename());
            $original['width'] = $im->width();
            $original['height'] = $im->height();
            
            $images = array($thumbnail, $middle, $original);
        }
        
        return $images;
    }
}