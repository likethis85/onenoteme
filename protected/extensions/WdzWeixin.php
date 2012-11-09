<?php
class WdzWeixin extends CDWeixin
{
    public function processRequest($data)
    {
        $text = 'hello wordl!!!!';
        $posts = array(
            array(
                'Title' => '测试标题',
                'Discription' => '测试描述',
                'PicUrl' => 'http://cdn.ifanr.cn/wp-content/uploads/2012/11/evernote.jpg',
                'Url' => 'http://www.weixin800.com/p/24',
            )
        );
        $xml = $this->outputNews($text, $posts);
        header('Content-Type: application/xml');
        echo $xml;
        file_put_contents(app()->runtimePath . '/wx.txt', $xml);
    }
    
    public function errorHandler($errno, $error, $file = '', $line = 0)
    {
        $log = sprintf('%s - %s - %s - %s', $errno, $error, $file, $line);
        file_put_contents(app()->runtimePath . '/wx1.txt', $log);
    }
    
    public function errorException(Exception $e)
    {
        $log = sprintf('%s - %s - %s - %s', $e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine());
        file_put_contents(app()->runtimePath . '/wx2.txt', $log);
    }
}