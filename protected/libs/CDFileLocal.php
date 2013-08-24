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
    private $_watermarks = array();
    
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
            
            if ($this->_watermarks)
                $rawdata = $this->applyWaterMark($rawdata);
            
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
    
    public function applyWaterMark($data)
    {
        if ($this->_watermarks) {
            $im = new CDImage();
            $im->load($data);
            foreach ($this->_watermarks as $water) {
                if ($im->width() > $water->minWidth() && $im->height() > $water->minHeight())
                    $water->apply($im, 5);
            }
            
            $data = $im->outputRaw();
            $im = null;
        }
        return $data;
    }
    
    public function addWaterMark($type, $position, $data, $font = '', $size = 18, $color = array(255, 255, 255), $borderColor = array(0, 0, 0), $minWidth = 0, $minHeight = 0)
    {
        $water = new CDWaterMark($type);
        $water->minWidth((int)$minWidth);
        $water->minHeight((int)$minHeight);
        
        if ($water->isImage()) {
            $water->position($position)
                ->setImage($data);
        }
        elseif ($water->isText()) {
            $water->position($position)
            ->setText($data)
            ->color($color)
            ->font($font)
            ->fontsize($size)
            ->borderColor($borderColor);
        }
        
        $this->_watermarks[] = $water;
    }
    
    public function clearWaterMark()
    {
        $this->_watermarks = array();
    }
    
}




