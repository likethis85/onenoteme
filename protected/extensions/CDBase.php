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
        if ($_SERVER['HTTP_CLIENT_IP'])
            $ip = $_SERVER['HTTP_CLIENT_IP'];
	 	elseif ($_SERVER['HTTP_X_FORWARDED_FOR'])
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	 	else
            $ip = $_SERVER['REMOTE_ADDR'];

        return $ip;
    }
    
    /**
     * 返回上传后的文件路径
     * @return string|Array 如果成功则返回路径地址，如果失败则返回错误号和错误信息
     * -1 目录不存在并且无法创建
     * -2 目录不可写
     */
    public static function makeUploadPath($additional = null, $basePath = null)
    {
        $relativeUrl = (($additional === null) ? '' : $additional . '/') . date('Y/m/d/', $_SERVER['REQUEST_TIME']);
        $relativePath = (($additional === null) ? '' : $additional . DS) . date(addslashes(sprintf('Y%sm%sd%s', DS, DS, DS)), $_SERVER['REQUEST_TIME']);

        if (empty($basePath))
            $basePath = param('uploadBasePath');
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
            . ($extension ? '.' . $extension : '');
        
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
    
    public static function filterText($text)
    {
        static $keywords = null;
        if ($keywords === null) {
            $filename = dp('filter_keywords.php');
            if (file_exists($filename) && is_readable($filename)) {
                $keywords = require($filename);
            }
            else
                return $text;
        }
        //         var_dump($keywords);exit;
        if (empty($keywords)) return $text;
    
        try {
            $patterns = array_keys($keywords);
            foreach ($patterns as $index => $pattern) {
                $patterns[$index] = '/' . $pattern . '/is';
            }
    
            $replacement = array_values($keywords);
            foreach ($replacement as $index => $word)
                $replacement[$index] = empty($word) ? param('filterKeywordReplacement') : $word;
    
            $result = preg_replace($patterns, $replacement, $text);
            $newText = ($result === null) ? $text : $result;
        }
        catch (Exception $e) {
            $newText = $text;
        }
    
        return $newText;
    }
    
    public static function mergeHttpUrl($baseurl, $relativeUrl)
    {
        $baseurl = trim($baseurl, ' \'\"');
        $relativeUrl = trim($relativeUrl, ' \'\"');
    
        // $baseurl and $relativeUrl is null
        if (empty($baseurl) || empty($relativeUrl))
            return false;
    
        if (filter_var($relativeUrl, FILTER_VALIDATE_URL) !== false && stripos($relativeUrl, 'http://') === 0)
            return $relativeUrl;
    
        // $baseurl is not a valid url
        $result = filter_var($baseurl, FILTER_VALIDATE_URL);
        if ($result === false) return false;
    
        // $baseurl is not a valid http protocol url
        $pos = stripos($baseurl, 'http://');
        if ($pos !== 0) return false;
    
        $parts = parse_url($baseurl);
        unset($parts['query'], $parts['fragment']);
        $pos = stripos($relativeUrl, '/');
        if ($pos === 0)
            $parts['path'] = $relativeUrl;
        else
            $parts['path'] = dirname($parts['path']) . '/' . ltrim($relativeUrl, './');
    
        $url = function_exists('http_build_url') ? http_build_url($url, $parts) : self::httpBuildUrl($parts);
    
        return $url;
    }
    

    public static function userIsMobileBrower()
    {
        $browers = array('iPhone', 'Android', 'hpwOS', 'Windows Phone OS', 'BlackBerry');
        $agent = $_SERVER['HTTP_USER_AGENT'];
        foreach ($browers as $brower) {
            $pos = stripos($agent, $brower);
            if ($pos !== false) return true;
        }
    
        return false;
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
            set_time_limit(0);
            
            $curl = new CDCurl();
            $curl->get($url);
            $errno = $curl->errno();
            if ($errno != 0)
                throw new Exception($curl->error(), $errno);
            
            $data = $curl->rawdata();
            $curl->close();
            
            $isGifAnimate = CDImage::isGifAnimate($data, true);
            
            $path = CDBase::makeUploadPath('pics');
            $info = parse_url($url);
            $file = CDBase::makeUploadFileName();
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
                file_put_contents(app()->runtimePath . DS . 'test1.txt', $gifFileName);
                file_put_contents(app()->runtimePath . DS . 'test2.txt', $gifUrl);
            }
            else {
                $im->revert();
                if ($im->width() > IMAGE_BMIDDLE_MAX_WIDTH)
                    $im->resizeToWidth(IMAGE_BMIDDLE_MAX_WIDTH);
                
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
            $images = array($thumbnail, $middle, $original);
        }
        
        return $images;
    }


    public static function siteHomeUrl()
    {
        return aurl('site/index');
    }
    
    public static function memberHomeUrl()
    {
        return aurl('member/default/index');
    }

    public static function wapHomeUrl()
    {
        return aurl('wap/index');
    }

    public static function adminHomeUrl()
    {
        return aurl('admin/default/index');
    }


    public static function mobileHomeUrl()
    {
        return aurl('mobile/default/index');
    }

    public static function loginUrl()
    {
        return aurl('site/login');
    }

    public static function logoutUrl()
    {
        return aurl('site/logout');
    }

    public static function singupUrl()
    {
        return aurl('site/signup');
    }

    public static function userHomeUrl($uid)
    {
        return aurl('user/index', array('id'=>(int)$uid));
    }
}




