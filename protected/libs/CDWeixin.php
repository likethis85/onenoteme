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

    public static function outputText($toUserName, $fromUserName, $text)
    {
        $dom = new DOMDocument('1.0', app()->charset);
    
        $toUser = $dom->createElement('ToUserName');
        $toUser->appendChild($dom->createCDATASection($toUserName));
        $dom->appendChild($toUser);
    
        $fromUser = $dom->createElement('FromUserName');
        $fromUser->appendChild($dom->createCDATASection($fromUserName));
        $dom->appendChild($fromUser);
    
        $dom->appendChild(new DOMElement('CreateTime', time()));
    
        $msgType = $dom->createElement('MsgType');
        $msgType->appendChild($dom->createCDATASection(self::REPLY_TYPE_TEXT));
        $dom->appendChild($msgType);
    
        $content = $dom->createElement('Content');
        $content->appendChild($dom->createCDATASection($text));
        $dom->appendChild($content);
    
        $dom->appendChild(new DOMElement('FuncFlag', 0));
    
        return $dom->saveXML();
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