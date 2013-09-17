<?php
class CDVideoKit
{
    private $_clientID;
    private $_vid;
    
    /**
     * 视频对象
     * @var CDYoukuVideo
     */
    private $_video;
    
    public function __construct($vid, $client_id = null)
    {
        $this->_video = new CDYoukuVideo($vid, $client_id);
    }
    
    public function getDesktopPlayerHTML($width = 600, $height = 400, $autoplay = false)
    {
        return $this->_video->getDesktopVideoHTML($width, $height, $autoplay);
    }
    
    public function getMobilePlayerHTML($width = 600, $height = 400, $autoplay = false)
    {
        return $this->_video->getMobileVideoHTML($width, $height, $autoplay);
    }
}

interface ICDVideo
{
    public function getFlashUrl();
    public function getSourceUrl();
    public function getIframeUrl();
    public function getHtml5Url();
    public function getDesktopVideoHTML($width = 600, $height = 400);
    public function getMobileVideoHTML($width = 280, $height = 180);
}

class CDYoukuVideo implements ICDVideo
{
    private $_vid;
    private $_clientID;
    
    public function __construct($vid, $client_id)
    {
        if ($vid)
            $this->_vid = $vid;
        else
            throw new Exception('video id is required.');

        $this->_clientID = $client_id;
    }

    public function getFlashUrl()
    {
        return sprintf('http://player.youku.com/player.php/sid/%s/v.swf', $this->_vid);
    }
    
    public function getSourceUrl()
    {
        return sprintf('http://v.youku.com/v_show/id_%s.html', $this->_vid);
    }
    
    public function getIframeUrl()
    {
        return sprintf('http://player.youku.com/embed/%s', $this->_vid);
    }
    
    public function getHtml5Url()
    {
        return null;
    }
    
    public function getDesktopVideoHTML($width = 600, $height = 400, $autoplay = false)
    {
        $elementID = 'youkuplayer' . $this->_vid;
        $html = '<div id="%s"></div><script type="text/javascript">';
        $html .= "player = new YKU.Player('%s',{client_id: '%s',vid:'%s',width:%d,height:%d,autoplay:%s});";
        $html .= '</script>';

        return sprintf($html, $elementID, $elementID, $this->_clientID, $this->_vid, $width, $height, $autoplay?'true':'false');
    }

    public function getMobileVideoHTML($width = 280, $height = 180, $autoplay = false)
    {

    }
}


