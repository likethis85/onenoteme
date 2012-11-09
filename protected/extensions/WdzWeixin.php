<?php
class WdzWeixin extends CDWeixin
{
    public function processRequest($data)
    {
        $text = 'hello wordl!!!!';
        $xml = $this->outputText($data->ToUserName, $data->FromUserName, $text);
        header('Content-Type: application/xml');
        echo $xml;
        file_put_contents(app()->runtimePath . '/wx.txt', $xml);
    }
    
    public function errorHandler($errno, $error, $file = '', $line = 0)
    {
        $log = sprintf('%s - %s - %s - %s', $errno, $error, $file, $line);
        file_put_contents(app()->runtimePath . '/wx1.txt', $log);
    }
    
    public function errorException(Exception $exception)
    {
        $log = sprintf('%s - %s - %s - %s', $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
        file_put_contents(app()->runtimePath . '/wx2.txt', $log);
    }
}