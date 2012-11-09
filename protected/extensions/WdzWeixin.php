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
    }
    
    public function errorHandler($errno, $error, $file = '', $line = 0)
    {
        // 错误处理
    }
    
    public function errorException(Exception $exception)
    {
        // 异常处理
    }
}