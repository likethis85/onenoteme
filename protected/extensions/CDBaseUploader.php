<?php
defined('DS') || define('DS', DIRECTORY_SEPARATOR);

class CDUploaderException extends Exception
{
}

interface ICDUploader
{
    public function init();
    public function autoFilename($extension = '', $pathPrefix = '', $filePrefix = '', $autoMkDir = false);
    public function setFilename($filename);
    public function save($file, $filename);
    public function delete($path);
    public function getPathByUrl($url);
}

class CDBaseUploader extends CApplicationComponent
{
    /**
     * 文件存储基本路径
     * @var string
     */
    public $basePath = '';
    
    /**
     * 存储文件链接基础url
     * @var string
     */
    public $baseUrl = '';
    
    /**
     * 保存文件的路径
     * @var string
     */
    protected $_filename;
    
    /**
     * 保存文件的url
     * @var string
     */
    protected $_filenurl;
    
    public static function datePlaceholders($key = null)
    {
        $places = array(
            '{year}' => date('Y'),
            '{month}' => date('m'),
            '{day}' => date('d'),
            '{hour}' => date('HY'),
            '{minute}' => date('i'),
            '{second}' => date('s'),
            '{week}' => date('W'),
            '{wday}' => date('w'),
        );
        
        if (empty($key))
            return $places;
        elseif (array_key_exists($key, $places)) {
            return $places[$key];
        }
    }
    
    public function init()
    {
        if (empty($this->basePath) || empty($this->baseUrl))
            throw new CDUploaderException('basePath and baseUrl is required.');
        
        $this->basePath = str_replace(array('/', '\\'), DS, $this->basePath);
        $this->basePath = realpath($this->basePath);
        if (empty($this->basePath))
            throw new CDUploaderException('basePath is ivalid.');
        
        $this->basePath = rtrim($this->basePath, DS) . DS;
        $this->baseUrl = rtrim($this->baseUrl, '/') . '/';
    }
    
    public function revert()
    {
        $this->setFilename(null);
        return $this;
    }
    
    public function setFilename($filename)
    {
        if (empty($filename))
            throw new CDUploaderException('$filename is required.');
        
        $this->_filename = $filename;
        return $this;
    }
    
    public function getFilename()
    {
        return $this->_filename;
    }
    
    /**
     * 生成文件保存目录
     * @param string $pathFormat 文件路径，用于如果用到时间参数，参考date方法
     * @param string $prefix 前缀目录
     * @param string $autoMkDir 是否自动生成目录
     * @return multitype:array |boolean
     */
    public function makePath($pathFormat = '', $pathPrefix = '', $autoMkDir = false)
    {
        if ($pathPrefix) {
            $prefixPath = str_replace(array('/', '\\'), DS, $pathPrefix);
            $prefixUrl = str_replace(array('/', '\\'), '/', $pathPrefix);
            
            $prefixPath = trim($prefixPath, DS) . DS;
            $prefixUrl = trim($prefixUrl, '/') . '/';
        }
        else
               $prefixPath = $prefixUrl = '';
        
        if ($pathFormat) {
            $pathFormat = str_replace(array('/', '\\'), DS, $pathFormat);
            $pathFormat = trim($pathFormat, DS) . DS;
            $placeholders = self::datePlaceholders();
            $pathFormat = str_replace(array_keys($placeholders), array_values($placeholders), $pathFormat);
        }
        $urlFormat = str_replace(array('/', '\\'), '/', $pathFormat);
        
        $relativePath = $prefixPath .$pathFormat;
        $relativeUrl = $prefixUrl . $urlFormat;
        $absolutePath = $this->basePath . $relativePath;
        if (!file_exists($absolutePath) && $autoMkDir && !mkdir($absolutePath, 0755, true) ||
            file_exists($absolutePath) && !is_dir($absolutePath))
            return false;

        $absolutePath = realpath($this->basePath . $relativePath);
        
        $absolutePath = rtrim($absolutePath, DS) . DS;
        
        $data = array(
            'relative_path' => $relativePath,
            'relative_url' => $relativeUrl,
            'absolute_path' => $absolutePath,
            'absolute_url' => $this->baseUrl . $relativeUrl,
        );
        
        return $data;
    }
    
    /**
     * 自动生成文件名
     * @param string $extension 扩展名
     * @param string $prefix 文件名前缀
     * @return string 文件名称
     */
    public function makeFileName($extension = '', $filePrefix = '')
    {
        $extension = strtolower($extension);
        $filename = date('YmdHis_', time()) . uniqid() . $extension;
    
        if (strlen($filePrefix) > 0)
            $filename = $filePrefix . '_' . $filename;
    
        return $filename;
    }
    
    public function makeFilePath($pathFormat = '', $extension = '', $pathPrefix = '', $filePrefix = '', $autoMkDir = false)
    {
        $paths = $this->makePath($pathFormat, $pathPrefix, $autoMkDir);
        $filename = $this->makeFileName($extension, $filePrefix);
        
        foreach ($paths as $key => $value)
            $paths[$key] = $paths[$key] . $filename;
        
        return $paths;
    }
    
    public function autoFilePath($extension = '', $pathPrefix = '', $filePrefix = '', $autoMkDir = false)
    {
        $pathFormat = '{year}/{month}/{day}';
        $filepath = $this->makeFilePath($pathFormat, $extension, $pathPrefix, $filePrefix, $autoMkDir);
        return $filepath;
    }
}
