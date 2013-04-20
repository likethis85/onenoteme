<?php
class CDLocalUploader extends CDBaseUploader
{
    public $basePath;
    public $baseUrl;
    
    public function init()
    {
        if (empty($this->basePath) || empty($this->baseUrl))
            throw new CDException('basePath and baseUrl is required.');
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
    
    public function save($file, $filename = '', $opts = null)
    {
        
    }
}