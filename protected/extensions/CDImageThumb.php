<?php
class CDImageThumb
{
    private $_url;
    private $_width;
    private $_height;
    private $_spearator = '!';
    
    public function __construct($url, $width = 0, $height = 0, $separator = '!')
    {
        if (empty($url))
            throw new CDException('$url is required.');
        
        $this->_url = $url;
        $this->_width = (int)$width;
        $this->_height = (int)$height;
        $this->setSeparator($separator);
    }
    
    public function setSeparator($str)
    {
        $this->_spearator = $str;
    }
    
    public function getSeparator()
    {
        return $this->_spearator;
    }
    
    public function heightByWidth($width)
    {
        $height = 0;
        $width = (int)$width;
        if ($this->_width > 0 && $this->_height > 0 && $width > 0) {
            if ($width > $this->_width)
                $height = $this->_height;
            else
                $height = $this->_height * $width / $this->_width;
        }
//         var_dump($height);exit;
        return (int)$height;
    }
    
    public function widthByHeight($height)
    {
        $width = 0;
        $height = (int)$height;
        if ($this->_width > 0 && $this->_height > 0 && $height > 0) {
            if ($height > $this->_height)
                $width = $this->_width;
            else
                $width = $this->_width * $height / $this->_height;
        }
        
        return (int)$width;
    }
    
    public function urlByVersion($version)
    {
        $versions = self::imageVersions();
        $url = '';
        if (empty($version) || !in_array($version, $versions))
            throw new CDException('$version is not in the specified values');
        else
            $url = $this->_url . $this->getSeparator() . $version;

        return $url;
    }
    
    public function thumbImageUrl()
    {
        return $this->urlByVersion(UPYUN_IMAGE_CUSTOM_THUMB);
    }
    
    public function fixThumbImageUrl()
    {
        return $this->urlByVersion(UPYUN_IMAGE_CUSTOM_FIXTHUBM);
    }
    
    public function squareThumbImageUrl()
    {
        return $this->urlByVersion(UPYUN_IMAGE_CUSTOM_SQUARETHUBM);
    }
    
    public function smallImageUrl()
    {
        return $this->urlByVersion(UPYUN_IMAGE_CUSTOM_SMALL);
    }
    
    public function middleImageUrl()
    {
        return $this->urlByVersion(UPYUN_IMAGE_CUSTOM_MIDDLE);
    }
    
    public function largeImageUrl()
    {
        return $this->urlByVersion(UPYUN_IMAGE_CUSTOM_LARGE);
    }
    
    public function miniAvatarUrl()
    {
        return $this->urlByVersion(UPYUN_AVATAR_CUSTOM_MINI);
    }
    
    public function smallAvatarUrl()
    {
        return $this->urlByVersion(UPYUN_AVATAR_CUSTOM_SMALL);
    }
    
    public function largeAvatarUrl()
    {
        return $this->urlByVersion(UPYUN_AVATAR_CUSTOM_LARGE);
    }
    
    
    
    private static function imageVersions()
    {
        return array(
            // image version
            UPYUN_IMAGE_CUSTOM_THUMB,
            UPYUN_IMAGE_CUSTOM_SEPARATOR,
            UPYUN_IMAGE_CUSTOM_MIDDLE,
            UPYUN_IMAGE_CUSTOM_LARGE,
            UPYUN_IMAGE_CUSTOM_THUMB,
            UPYUN_IMAGE_CUSTOM_FIXTHUBM,
            UPYUN_IMAGE_CUSTOM_SQUARETHUBM,
            // avatar version
            UPYUN_AVATAR_CUSTOM_MINI,
            UPYUN_AVATAR_CUSTOM_SMALL,
            UPYUN_AVATAR_CUSTOM_LARGE,
        );
    }
    
    
}



