<?php
class CDUpyunUploader extends CDBaseUploader
{
    public $endpoint = null;
    public $username;
    public $password;
    public $bucket;
    public $baseUrl;
    public $autoMkdir = true;
    public $isImageBucket = true;
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
        $this->setFilename(null);
        $path = empty($prefixPath) ? '' : ('/' . trim($prefixPath, '/'));
        $path .= date('/Y/m/d/');
        
        $filename = date('YmdHis_') . uniqid() . $extension;
        
        if ($prefix)
            $filename = $prefix . '_' . $filename;
        
        $this->setFilename($path . $filename);
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
    
    public function getFileUrl()
    {
        return rtrim($this->baseUrl, '/') . '/' . ltrim($this->_filename, '/');
    }
    
    public function save($file, $opts = null)
    {
        if (empty($file))
            throw new Exception('file path is requried.');
        
        $filename = '/' . ltrim($this->_filename, '/');
        return $this->_client->writeFile($filename, $file, $this->autoMkdir, $opts);
    }
    
    public function delete($path)
    {
        return $this->_client->deleteFile($path);
    }
    
    public function revert()
    {
        $this->setFilename(null);
        return $this;
    }
    
    public function imageInfo($key)
    {
        $value = null;
        if ($this->isImageBucket)
            $value = $this->_client->getWritedFileInfo($key);
        
        return $value;
    }
    
    public function animatedGifImage()
    {
        $frames = $this->imageInfo('x-upyun-frames');
        return intval($frames) > 1;
    }
}


