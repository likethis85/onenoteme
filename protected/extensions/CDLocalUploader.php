<?php
class CDLocalUploader extends CDBaseUploader implements ICDUploader
{
    public function init()
    {
        parent::init();
        
        if (empty($this->basePath) || empty($this->baseUrl))
            throw new CDException('basePath and baseUrl is required.');
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
    public function autoFilename($extension = '', $pathPrefix = '', $filePrefix = '', $autoMkDir = true)
    {
        $files = $this->autoFilePath($extension, $pathPrefix, $filePrefix, $autoMkDir);
        $filename = $files['absolute_path'];
        $this->setFilename($filename);
        return $files;
    }
    
    public function setFilename($filename)
    {
        $path = dirname($filename);
        if (!is_dir($path) && !mkdir($path, 0755, true)) {
            throw new CDUploaderException($path .' is not exists or is not writable');
        }
            
        return parent::setFilename($filename);
    }
    
    /**
     * 将文件保存到阿里去中
     * @see ICDUploader::save()
     * @return array | null 返回图片的信息
     */
    public function save($file, $filename = '', $opts = null, $deleteTempFile = true)
    {
        if (empty($file))
            throw new CDUploaderException('file data is requried.');
        
        if (!empty($filename))
            $this->setFilename($filename);
        if (empty($this->_filename))
            throw new CDUploaderException('write filename is not set.');
        
        // 如果参数是一个文件
        if (is_file($file)) {
            clearstatcache();
            if (is_uploaded_file($file))
                $result = @move_uploaded_file($file, $this->_filename);
            else {
                $result = copy($file, $this->_filename);
                if ($deleteTempFile && is_writable($file)) @unlink($file);
            }
            
            if (!$result) return false;
            
            $size = @getimagesize($file);
            if ($size === false)
                $infos = $result;
            else
                $infos = array(
                    'width' => $size[0],
                    'height' => $size[1],
                    'mime' => $size['mime'],
                    'frames' => CDImage::frameCount($file),
                );
            
            return $infos;
        }
        // 如果是用fopen 打开的handle，加此判断主要是为了与CDUpyunUploader兼容
        elseif (is_resource($file)) {
            fseek($file, 0, SEEK_END);
            $length = ftell($file);
            fseek($file, 0);
            $content = fread($file, $length);
            fclose($file);
        }
        else
            $content = $file;
        
        $result = file_put_contents($this->_filename, $content);
        if (!$result) return false;
        
        $size = getimagesize($this->_filename, $info);
        if ($size === false)
            $infos = true;
        else
            $infos = array(
                'width' => $size[0],
                'height' => $size[1],
                'mime' => $size['mime'],
                'frames' => CDImage::frameCount($file),
            );
        
        return $infos;
    }

    public function delete($path)
    {
        $path = $this->getPathByUrl($path);
        return @unlink($path);
    }
    

    public function getPathByUrl($url)
    {
        if (filter_var($url, FILTER_VALIDATE_URL))
            $path = str_replace($this->baseUrl, '', $url);

        $path = str_replace(array('/', '\\'), DS, $path);
        $path = $this->basePath . ltrim($path, DS);
        return $path;
    }
}