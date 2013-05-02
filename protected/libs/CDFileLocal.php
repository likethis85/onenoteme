<?php
class CDFileLocal extends CComponent
{
    public $uploader;
    public $pathPrefix;
    public $localDomains;
    
    /**
     * 抓取超时时间(秒)
     * @var integer
     */
    public $timeout = 60;
    
    private $_referer;
    
    public function __construct(CDBaseUploader $uploader = null, $pathPrefix = '')
    {
        $this->uploader = $uploader;
        $this->pathPrefix = $pathPrefix;
    }
    
    public function fetchReplacedHtml($html)
    {
        $rows = $this->fetchBatchByHtml($html);
        if (empty($rows)) return false;
        
        foreach ($rows as $index => $row) {
            if (empty($row))
                unset($rows[$index]);
            else {
                $thumb = new CDImageThumb($row['url']);
                $html = str_replace($row['oldurl'], $thumb->middleImageUrl(), $html);
            }
        }
        
        return array($html, $rows);
    }
    
    public function referer($url)
    {
        if (filter_var($url, FILTER_VALIDATE_URL) !== false)
            $this->_referer = $url;
        return $this;
    }
    
    public function setLocalDomains(array $domains = array())
    {
        $domains[] = $_SERVER['HTTP_HOST'];
        $this->localDomains = array_unique($domains);
        return $this;
    }
    
    /**
     * 批量获取远程图片，然后本地化
     * @param string $html
     * @return mixed boolean|array data array(path, url), false if error occured
     */
    public function fetchBatchByHtml($html)
    {
        if (empty($html)) return array();
        
        $matches = array();
        $pattern = '/<img.*?src="?(.+?)["\s]{1}?.*?>/is';
        $result = preg_match_all($pattern, $html, $matches);
        if ($result === false) return false;
        
        array_shift($matches);
        $urls = array_unique((array)$matches[0]);
        if (count($urls) == 0) return false;
        
        $data = self::fetchBatch($urls);
        return $data;
    }
    
    public function fetchBatch(array $urls)
    {
        $data = array();
        foreach ($urls as $url) {
            if ($url !== false)
                $data[] = self::fetchOne($url);
        }
        
        return $data;
    }
    
    /**
     * 获取远程图片然后保存到本地
     * @param string $url 文件url
     * @return array|boolean local file data array(path, url), false if error occured
     * @throws Exception if $url is empty
     */
    public function fetchOne($url)
    {
        if (empty($url) || filter_var($url, FILTER_VALIDATE_URL) === false || !CDBase::externalUrl($url, $this->localDomains))
            return false;
    
        try {
            set_time_limit($this->timeout);
            
            $fetch = new CDCurl();
            $fetch->get($url);
            if ($fetch->errno() !== 0) return false;
            
            $rawdata = $fetch->rawdata();
            $extension = CDImage::getImageExtName($rawdata);
            $file = $this->uploader->autoFilename('.jpg', $this->pathPrefix, 'original', true);
            
            $result = $this->uploader->save($rawdata);
            if ($result === false)
                return false;
            else {
                $data = array(
                    'oldurl' => $url,
                    'path' => $this->uploader->getFilename(),
                    'url' => $file['absolute_url'],
                    'width' => $result['width'],
                    'height' => $result['height'],
                    'frames' => $result['frames'],
                );
                return $data;
            }
        }
        catch (Exception $e) {
            return false;
        }
        
    }
    
    public function setWaterMark($image, $position, $data, $color = array(255, 255, 255), $alpha = null, $font = '')
    {
        
    }
    
}




