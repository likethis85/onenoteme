<?php
class CDVideoKit
{
    const PLATFORM_YOUKU = 'youku.com';
    const PLATFORM_56 = '56.com';
    
    private $_vid;
    private $_platform;
    private $_keyMap = array();
    
    /**
     * 视频对象
     * @var CDYoukuVideo
     */
    private $_video;
    
    public function __construct()
    {
    }
    
    public function getVideo()
    {
        return $this->_video;
    }
    
    public function setVideoID($vid, $platform)
    {
        if ($vid)
            $this->_vid = $vid;
        else
            throw new Exception('video id is required.');
        
        if (array_key_exists($platform, self::videoClassMap())) {
            $this->_platform = $platform;
            $className = self::videoClassMap($this->_platform);
            $this->_video = new $className($this->_vid, $this->_clientID);
        }
        else
            throw new Exception('platform is invalid.');
    }
    
    public function setVideoUrl($url)
    {
        $this->_platform = self::parseVideoPlatform($url);
        if ($this->_platform === false)
            throw new Exception($url . ', this platform is supported.');
        
        $this->_vid = self::parseVideoID($url, $this->_platform);
        if ($this->_vid === false)
            throw new Exception('An error occurred when parse video id.');
        
        if (array_key_exists($this->_platform, $this->_keyMap))
            $clientID = $this->_keyMap[$this->_platform];
        $className = self::videoClassMap($this->_platform);
        
        if (class_exists($className, true))
            $this->_video = new $className($this->_vid, $clientID);
        else
            throw new Exception($className . ' is not exists.');
    }

    public function setAppKeysMap(array $map)
    {
        $this->_keyMap = $map;
    }
    
    public function getMobileSourceUrl()
    {
        $url = $this->_video->getIframeUrl();
        if (empty($url))
            $url = $this->_video->getSourceUrl();
        
        return $url;
    }
    
    public function getDestopSourceUrl()
    {
        $url = $this->_video->getSourceUrl();
        if (empty($url))
            $url = $this->_video->getIframeUrl();
        
        return $url;
    }
    
    public function getDesktopPlayerHTML($width = 600, $height = 400, $autoplay = false)
    {
        return $this->_video->getDesktopPlayerHTML($width, $height, $autoplay);
    }
    
    public function getMobilePlayerHTML($width = 600, $height = 400, $autoplay = false)
    {
        return $this->_video->getMobilePlayerHTML($width, $height, $autoplay);
    }
    
    public static function parseVideoPlatform($url)
    {
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            $host = parse_url($url, PHP_URL_HOST);
            $maps = self::videoClassMap();
            foreach ($maps as $key => $class) {
                if (stripos($host, $key) !== false)
                    return $key;
            }
    
            return false;
        }
        else
            throw new Exception($url . ' is a invalid url');
    }
    
    public static function parseVideoID($url, $platform)
    {
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            $pattern = self::patternMap($platform);
            if (empty($pattern))
                throw new Exception($platform . ' is not supported');
            
            $result = preg_match($pattern, $url, $matches);
            return $result ? $matches[1] : false;
        }
        else
            throw new Exception($url . ' is a invalid url');
    }
    
    protected static function videoClassMap($key = null)
    {
        $maps = array(
                self::PLATFORM_YOUKU => 'CDYoukuVideo',
                self::PLATFORM_56 => 'CD56Video',
        );
    
        return ($key === null) ? $maps : $maps[$key];
    }
    
    protected static function patternMap($key = null)
    {
        $maps = array(
            self::PLATFORM_YOUKU => '/id_([\w\d]+?)\.html/i',
            self::PLATFORM_56 => '/v_([\w\d]+?)\.html/i',
        );
        
        return ($key === null) ? $maps : $maps[$key];
    }
}

interface ICDVideo
{
    public function __construct($vid, $client_id);
    public function getFlashUrl($open = true);
    public function getSourceUrl($open = true);
    public function getIframeUrl($open = true);
    public function getHtml5Url($open = true);
    public function getDesktopPlayerHTML($width = 600, $height = 400);
    public function getMobilePlayerHTML($width = 280, $height = 180);
}


/**
 * 视频播放基础组件
 * @author chendong
 *
 */
abstract class CDVideoBase
{
    protected $_vid;
    protected $_clientID;
    
    public function __construct($vid, $client_id)
    {
        if ($vid)
            $this->_vid = $vid;
        else
            throw new Exception('video id is required.');
    
        $this->_clientID = $client_id;
    }

    protected function getIframeHTML($width = 600, $height = 400)
    {
        $html = '';
        if ($this->getIframeUrl())
            $html = sprintf('<iframe width="%d" height="%d" src="%s" frameborder="0" allowfullscreen></iframe>', $width, $height, $this->getIframeUrl());
    
        return $html;
    }
    
    protected function getFlashHTML($width = 600, $height = 400)
    {
        $html = '';
        if ($this->getFlashUrl())
            $html = sprintf('<embed src="%s"  type="application/x-shockwave-flash" width="%d" height="%d" allowFullScreen="true" allowNetworking="all" allowScriptAccess="always"></embed>', $this->getFlashUrl(), $width, $height);
    
        return $html;
    }
    
    protected function getHtml5VideoHTML($width = 280, $height = 180)
    {
        $html = '';
        if ($this->getHtml5Url())
            $html = sprintf('<video src="%s" controls="controls" width="%d" height="%d">您的浏览器不支持HTML5视频播放。</video>', $this->getHtml5Url(), $width, $height);
    
        return $html;
    }

    public function getDesktopPlayerHTML($width = 600, $height = 400, $autoplay = false)
    {
        if (empty($this->_clientID)) {
            $html = $this->getFlashHTML($width, $height);
            return empty($html) ? $this->getIframeHTML($width, $height) : $html;
        }
        else
            return $this->getDesktopOpenPlayerHTML($width, $height, $autoplay);
    }

    public function getMobilePlayerHTML($width = 280, $height = 180, $autoplay = false)
    {
        if (empty($this->_clientID)) {
            $html = $this->getIframeHTML($width, $height);
            return empty($html) ? $this->getHtml5VideoHTML($width, $height) : $html;
        }
        else
            return $this->getMobileOpenPlayerHTML($width, $height, $autoplay);
    }
    
    abstract protected function getDesktopOpenPlayerHTML($width, $height, $autoplay = false);
    abstract protected function getMobileOpenPlayerHTML($width, $height, $autoplay = false);
}


/**
 * 优酷视频
 * @author chendong
 *
 */
class CDYoukuVideo extends CDVideoBase implements ICDVideo
{
    public function getFlashUrl($open = true)
    {
        return sprintf('http://player.youku.com/player.php/sid/%s/v.swf', $this->_vid);
    }
    
    public function getSourceUrl($open = true)
    {
        return sprintf('http://v.youku.com/v_show/id_%s.html', $this->_vid);
    }
    
    public function getIframeUrl($open = true)
    {
        return sprintf('http://player.youku.com/embed/%s', $this->_vid);
    }
    
    public function getHtml5Url($open = true)
    {
        return null;
    }
    
    protected function getDesktopOpenPlayerHTML($width, $height, $autoplay = false)
    {
        $elementID = 'youkuplayer-' . $this->_vid;
        $html = '<div id="%s"></div><script type="text/javascript">';
        $html .= "var player = new YKU.Player('%s',{client_id: '%s',vid:'%s',width:%d,height:%d,autoplay:%s});";
        $html .= '</script>';
        
        return sprintf($html, $elementID, $elementID, $this->_clientID, $this->_vid, $width, $height, $autoplay?'true':'false');
    }
    
    protected function getMobileOpenPlayerHTML($width, $height, $autoplay = false)
    {
        return $this->getDesktopPlayerHTML($width, $height, $autoplay);
    }

}


/**
 * 56网视频
 * @author chendong
 *
 */
class CD56Video extends CDVideoBase implements ICDVideo
{
    public function getFlashUrl($open = true)
    {
        $format = ($open && $this->_clientID) ? 'http://player.56.com/3000003067/open_%s.swf' : 'http://player.56.com/v_%s.swf';
        return sprintf($format, $this->_vid) . ($open ? '/1030_r239612568.swf' : '');
    }

    public function getSourceUrl($open = true)
    {
        return sprintf('http://www.56.com/u/v_%s.html/1030_r239612568.html', $this->_vid);
    }

    public function getIframeUrl($open = true)
    {
        return sprintf('http://www.56.com/iframe/%s', $this->_vid);
    }

    public function getHtml5Url($open = true)
    {
        return null;
    }

    protected function getDesktopOpenPlayerHTML($width, $height, $autoplay = false)
    {
        $html = $this->getFlashHTML($width, $height);
        return empty($html) ? $this->getIframeHTML($width, $height) : $html;
    }
    
    protected function getMobileOpenPlayerHTML($width, $height, $autoplay = false)
    {
        $html = $this->getIframeHTML($width, $height);
        return empty($html) ? $this->getHtml5VideoHTML($width, $height) : $html;
    }
}


