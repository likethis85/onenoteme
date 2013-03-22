<?php
class CDUpyunUploader extends CDUploader
{
    public $endpoint = null;
    public $username;
    public $password;
    public $bucket;
    public $autoMkdir = true;
    public $imageBucket = true;
    public $timeout = 30;
    
    /**
     * 又拍云api接口地址
     * @var UpYun
     */
    private $_client;
    
    /**
     * 保存在又拍云上的文件名
     * @var string
     */
    private $_filename;
    
    
    public function init()
    {
        if (empty($this->bucket))
            throw new CException('bucket is required');
        
        $upyunClassFile = Yii::getPathOfAlias('application.libs') . DS . 'upyun.class.php';
        require($upyunClassFile);
        $this->_client = new UpYun($this->bucket, $this->username, $this->password, $this->endpoint, $this->timeout);
    }
    
    public function autoFilename($prefixPath = '', $extension = '', $prefix = '')
    {
        $path = empty($prefixPath) ? '' : ('/' . trim($prefixPath, '/'));
        $path .= date('/Y/m/d/');
        
        $filename = date('YmdHis_') . uniqid() . $extension;
        
        if ($prefix)
            $filename = $prefix . '_' . $filename;
        
        $this->_filename = $path . $filename;
        return $this;
    }
    
    public function setBucketName($bucket)
    {
        $this->bucket = $bucket;
        return $this;
    }
    
    public function setFilename($filename)
    {
        $this->_filename = $filename;
        return $this;
    }
    
    public function getFilename()
    {
        return $this->_filename;
    }
    
    public function upload($file, $opts = null)
    {
        $filename = '/' . ltrim($this->_filename, '/');
        return $this->_client->writeFile($filename, $file, $this->autoMkdir, $opts);
    }
    
    public function imageInfo($key)
    {
        $value = null;
        if ($this->imageBucket)
            $value = $this->_client->getWritedFileInfo($key);
        
        return $value;
    }
    
    public function animatedGifImage()
    {
        $frames = $this->imageInfo('x-upyun-frames');
        return intval($frames) > 1;
    }
}


