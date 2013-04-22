<?php
class CDUpyunUploader extends CDBaseUploader  implements ICDUploader
{
    public $endpoint = null;
    public $username;
    public $password;
    public $bucket;
    public $autoMkdir = true;
    public $isImageBucket = true;
    public $timeout = 30;
    
    /**
     * 又拍云api接口地址
     * @var UpYun
     */
    private $_client;
    
    public function init()
    {
        parent::init();
        
        if (empty($this->bucket))
            throw new CDUploaderException('bucket is required');
    
        if (!class_exists('UpYun', false)) {
            $upyunClassFile = Yii::getPathOfAlias('application.libs') . DS . 'upyun.class.php';
            require($upyunClassFile);
        }
        $this->_client = new UpYun($this->bucket, $this->username, $this->password, $this->endpoint, $this->timeout);
    }
    
    /**
     * 自动生成文件名
     * @param string $extension 文件扩展名
     * @param string $pathPrefix 路径前缀
     * @param string $filePrefix 文件名前缀
     * @param boolean $autoMkDir 是否自动生成目录，此参数并未用到此参数，值总为false
     * @see ICDUploader::autoFilename()
     * @return array 自动生成的文件路径及url，包括4个元素，键值分别为relative_path, absolute_path, relative_url, absolute_url
     */
    public function autoFilename($extension = '', $pathPrefix = '', $filePrefix = '', $autoMkDir = false)
    {
        $files = $this->autoFilePath($extension, $pathPrefix, $filePrefix, false);
        $filename = '/' . $files['relative_url'];
        $this->setFilename($filename);
        return $files;
    }
    
    /**
     * 将文件保存到阿里去中
     * @see ICDUploader::save()
     * @return array | null 如果是图片bucket，则返回图片的信息，如果是文件bucket，则不返回内容
     */
    public function save($file, $filename = '', $opts = null)
    {
        if (empty($file))
            throw new CDUploaderException('file data is requried.');
        
        if (!empty($filename))
            $this->_filename = $filename;
        if (empty($this->_filename))
            throw new CDUploaderException('write filename is not set.');
        
        if (is_file($file))
            $file = fopen($file, 'rb');
        
        return $this->_client->writeFile($this->_filename, $file, $this->autoMkdir, $opts);
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
}


