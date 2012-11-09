<?php
abstract class CDWeixin
{
    const MSG_TYPE_TEXT = 'text';
    const MSG_TYPE_LOCATION = 'location';
    
    const REPLY_TYPE_TEXT = 'text';
    const REPLY_TYPE_NEWS = 'news';
    
    private $_token;
    public $msgType;
    
    public function __construct($token)
    {
        if (empty($token))
            throw new Exception('Token is required');
        
        if (method_exists($this, 'errorHandler'))
            set_error_handler(array($this, 'errorHandler'));
        
        if (method_exists($this, 'exceptionHandler'))
            set_exception_handler(array($this, 'exceptionHandler'));
        
        $this->_token = $token;
    }
    
    public function run()
    {
        if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
            $data = $this->parsePostRequestData();
            if ($data)
                $this->processRequest($data);
        }
        else
            $this->sourceCheck();
        
        exit(0);
    }
    
    public abstract function processRequest($data);
    
    public function isTextMsg()
    {
        return $this->msgType == self::MSG_TYPE_TEXT;
    }
    
    public function isLocationMsg()
    {
        return $this->msgType == self::MSG_TYPE_LOCATION;
    }

    public static function outputText($toUserName, $fromUserName, $content)
    {
        $text = '<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    <FuncFlag>0</FuncFlag>
                </xml>';
        
        $text = sprintf($text, $toUserName, $fromUserName, time(), self::REPLY_TYPE_TEXT, $content);
        return $text;
    }
    
    
    
    
    
    
    
    
    
    
    
    

    private function parsePostRequestData()
    {
        $rawData = $GLOBALS['HTTP_RAW_POST_DATA'];
        $data = simplexml_load_string($rawData, 'SimpleXMLElement', LIBXML_NOCDATA);
        if ($data !== false) {
            $this->msgType = $data->MsgType;
        }
    
        return $data;
    }
    
    
    private function checkSignature()
    {
        $signature = $_GET['signature'];
        $timestamp = $_GET['timestamp'];
        $nonce = $_GET['nonce'];
    
        $params = array($this->_token, $timestamp, $nonce);
        sort($params);
        $sig = sha1(implode($params));
    
        return $sig == $signature;
    }
    
    public function sourceCheck()
    {
        if ($this->checkSignature()) {
            $echoStr = $_GET['echostr'];
            echo $echostr;
        }
    
        exit(0);
    }
}