<?php
class CDBase
{
    const FILE_NO_EXIST = -1; // '目录不存在并且无法创建';
    const FILE_NO_WRITABLE = -2; // '目录不可写';
    const VERSION = '1.6';

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
        static $ip = null;
        if ($ip !== null) return $ip;
        
        if ($_SERVER['HTTP_CLIENT_IP'])
            $ip = $_SERVER['HTTP_CLIENT_IP'];
	 	elseif ($_SERVER['HTTP_X_FORWARDED_FOR'])
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	 	else
            $ip = $_SERVER['REMOTE_ADDR'];

        return $ip;
    }
    
    /**
     * 获取客户端唯一cookie
     * @return Ambigous <NULL, CHttpCookie> 如果没有设置cookie返回null
     */
    public static function getClientID()
    {
        $id = null;
        $cookie = request()->cookies->itemAt(CD_CLIENT_ID);
        if ($cookie instanceof CHttpCookie)
            $id = $cookie->value;
        
        return $id;
    }
    
    public static function setClientID()
    {
        $value = md5(self::getClientIp() . uniqid());
        $cookie = new CHttpCookie(CD_CLIENT_ID, $value);
        $cookie->path = GLOBAL_COOKIE_PATH;
        $cookie->domain = GLOBAL_COOKIE_DOMAIN;
        $cookie->expire = $_SERVER['REQUEST_TIME'] + 3600*24*30;
        $cookie->httpOnly = true;
        request()->cookies->add(CD_CLIENT_ID, $cookie);
        return $value;
    }
    
    /**
     * 获取最后一次访问的时间及IP地址
     * @return array 第一个元素为时间，第二个元素为IP
     */
    public static function getClientLastVisit()
    {
        $value = '';
        $values = array();
        $cookie = request()->cookies->itemAt(CD_LAST_VISIT);
        if ($cookie instanceof CHttpCookie && !empty($cookie->value)) {
            $value = $cookie->value;
        
            $values = explode(',', $value);
            array_walk($values, 'intval');
            $values[1] = empty($values[1]) ? '' : (int)$values[1];
        }
        return $values;
    }
    
    public static function setClientLastVisit()
    {
        $values[] = $_SERVER['REQUEST_TIME'];
        $values[] = ip2long(self::getClientIp());
        $value = join(',', $values);
        $cookie = new CHttpCookie(CD_LAST_VISIT, $value);
        $cookie->path = GLOBAL_COOKIE_PATH;
        $cookie->domain = GLOBAL_COOKIE_DOMAIN;
        $cookie->expire = $_SERVER['REQUEST_TIME'] + 3600*24*30;
        $cookie->httpOnly = true;
        request()->cookies->add(CD_LAST_VISIT, $cookie);
        return $values;
    }
    
    /**
     * 判断是否是搜索引擎索引请求
     * @return boolean
     */
    public static function requestFromSearchEngine()
    {
        $agents = array('baiduspider', 'googlebot', 'slurp', 'iaskspider', 'yodaobot', 'msnbot', 'sogou web spider', 'sogou push spider', 'ia_archiver', 'spider');
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        foreach ($agents as $agent)
            if (stripos($userAgent, $agent) !== null)
                return true;
        
        return false;
    }
    
    /**
     * 判断是否是从搜索引擎索搜索结果点击过来的请求
     * @return boolean
     */
    public static function referrerFromSearch()
    {
        if (empty($_SERVER['HTTP_REFERER'])) return false;
        
        $searchs = array('baidu', 'google.', 'soso.com', 'sogou.com', 'youdao.com', 'bing.com', 'so.com', 'so.360.com');
        $referrer = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
        foreach ($searchs as $search)
            if (stripos($referrer, $search) !== null)
                return true;
        
        return false;
    }
    
    /**
     * 判断请求是否来自站外链接
     * @return boolean
     */
    public static function referrerFromOutLink()
    {
        if (empty($_SERVER['HTTP_REFERER'])) return false;
        
        $referrer = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
        if (stripos($referrer, SITE_DOMAIN) === null)
            return true;
        
        return false;
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
    

    public static function isMobileDevice()
    {
        $browers = array('iPhone', 'iPod', 'Android', 'hpwOS', 'Windows Phone OS', 'BlackBerry');
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
        if (empty($url) || filter_var($url, FILTER_VALIDATE_URL) === false)
            return $images;
        
        $upyunEnabled = (bool)param('upyun_enabled');
        if ($upyunEnabled)
            $images = self::saveRemoteImagesToUpyun($url, $thumbWidth, $thumbHeight, $cropFromTop, $cropFromLeft);
        else
            $images = self::saveRemoteImagesToLocal($url, $thumbWidth, $thumbHeight, $cropFromTop, $cropFromLeft);
        
        return $images;
    }
    
    public static function saveRemoteImagesToUpyun($url, $thumbWidth, $thumbHeight, $cropFromTop = false, $cropFromLeft = false)
    {
        $images = array();
        set_time_limit(0);
        
        $curl = new CDCurl();
        $curl->referer($url)->get($url);
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
        $path = CDBase::makeUploadPath('pics');
        $urlpath = '/' . trim($path['url'], '/') . '/';
        $file = CDBase::makeUploadFileName($extension);
        $thumbnailFilename = $urlpath . 'thumbnail_' . $file;
        $originalFilename = $urlpath . 'original_' . $file;
        $uploader = app()->getComponent('upyunimg');
        try {
            $result = array();
            $thumbnailData = $im->outputRaw();
            $uploader->setFilename($thumbnailFilename);
            $result = $uploader->upload($thumbnailData);
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
            $result = $uploader->upload($originalData);
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
    public static function saveRemoteImagesToLocal($url, $thumbWidth, $thumbHeight, $cropFromTop = false, $cropFromLeft = false)
    {
        $images = array();
        set_time_limit(0);
        
        $curl = new CDCurl();
        $curl->referer($url)->get($url);
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

    public static function channels()
    {
        return array(CHANNEL_DUANZI, CHANNEL_LENGTU, CHANNEL_GIRL, CHANNEL_VIDEO, CHANNEL_GHOSTSTORY);
    }
    
    public static function channelLabels($channelID = null)
    {
        $labels = array(
            CHANNEL_DUANZI => '挖笑话',
            CHANNEL_LENGTU => '挖趣图',
            CHANNEL_GIRL => '挖女神',
            CHANNEL_VIDEO => '挖短片',
            CHANNEL_GHOSTSTORY => '挖鬼故事',
        );
        
        return $channelID === null ? $labels : $labels[$channelID];
    }
    
    public static function fallStyleUrl(CController $controller)
    {
    	return aurl($controller->route, array_merge($controller->actionParams, array('s'=>POST_LIST_STYLE_WATERFALL)));
    }
    
    
    public static function gridStyleUrl($controller)
    {
    	return aurl($controller->route, array_merge($controller->actionParams, array('s'=>POST_LIST_STYLE_GRID)));
    }

    
    public static function lineStyleUrl($controller)
    {
    	return aurl($controller->route, array_merge($controller->actionParams, array('s'=>POST_LIST_STYLE_LINE)));
    }
}




