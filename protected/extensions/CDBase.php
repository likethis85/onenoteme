<?php
class CDBase
{
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

    public static function checkEmail($value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    public static function checkMobilePhone($value)
    {
        $pattern = '/^1[3458]\d{9}$/';
        return preg_match($pattern, $value);
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
    
    public static function mediatypes()
    {
        return array(MEDIA_TYPE_TEXT, MEDIA_TYPE_IMAGE, MEDIA_TYPE_UNKOWN);
    }
    
    public static function mediaTypeLabels($typeID = null)
    {
        $labels = array(
            MEDIA_TYPE_TEXT => '文字',
            MEDIA_TYPE_IMAGE => '图文',
            MEDIA_TYPE_VIDEO => '视频',
            MEDIA_TYPE_UNKOWN => '未知',
        );
    
        return $typeID === null ? $labels : $labels[$typeID];
    }
    
    public static function channels()
    {
        return array(CHANNEL_FUNNY);
    }
    
    public static function channelLabels($channelID = null)
    {
        $labels = array(
            CHANNEL_FUNNY => '挖笑话',
        );
    
        return $channelID === null ? $labels : $labels[$channelID];
    }

    public static function localDomains()
    {
        return array(
            'wabao.me',
            'waduanzi.com',
            'waduanzi.cn',
        );
    }
    
    public static function externalUrl($url, $domains = array())
    {
        if (empty($domains))
            $domains = self::localDomains();
        
        $domains[] = $_SERVER['HTTP_HOST'];
        $domains = array_unique($domains);
        foreach ($domains as $domain) {
            if (stripos($url, $domain) !== false)
                return false;
        }
        return true;
    }

    public static function convertPunctuation($text)
    {
        if (empty($text)) return '';
        
        $en_US = array(',', '.', ';', ':', '?', '!', '-',);
        $zh_CN = array('，', '。', '；', '：', '？', '！', '－');
        return str_replace($en_US, $zh_CN, $text);
        
    }


    // 马甲账号
    public static function randomVestAuthor()
    {
        $accounts = array(
            64 => '超邪恶',
            65 => '中高艺',
            66 => '笑料百科',
            67 => '邪恶漫画大湿',
            68 => '糗事万科',
            69 => '画画吃',
            70 => '叉叉小明',
            71 => '段子大湿',
            72 => '二逼青年世界',
            73 => '萌漫画',
            74 => '邪恶微漫画',
        );
    
        $userID = mt_rand(64, 74);
    
        return array($userID, $accounts[$userID]);
    }


}




