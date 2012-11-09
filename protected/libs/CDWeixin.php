<?php
abstract class CDWeixin
{
    const MSG_TYPE_TEXT = 'text';
    const MSG_TYPE_LOCATION = 'location';
    
    const REPLY_TYPE_TEXT = 'text';
    const REPLY_TYPE_NEWS = 'news';

    public $msgType;
    private $_msgToUser;
    private $_msgFromUser;
    private $_token;
    
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

    public function outputText($content)
    {
        $textTpl = '<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime>%s</CreateTime>
                <MsgType><![CDATA[%s]]></MsgType>
                <Content><![CDATA[%s]]></Content>
                <FuncFlag>0</FuncFlag>
            </xml>';
    
        $text = sprintf($textTpl, $this->_msgFromUser, $this->_msgToUser, time(), self::REPLY_TYPE_TEXT, $content);
        return $text;
    }
    
    public function outputNews($content, $posts = array())
    {
        $textTpl = '<xml>
             <ToUserName><![CDATA[%s]]></ToUserName>
             <FromUserName><![CDATA[%s]]></FromUserName>
             <CreateTime>%s</CreateTime>
             <MsgType><![CDATA[%s]]></MsgType>
             <Content><![CDATA[%s]]></Content>
             <ArticleCount>%d</ArticleCount>
             <Articles>%s</Articles>
             <FuncFlag>1<FuncFlag>
         </xml>';
        
        $itemTpl = '<item>
             <Title><![CDATA[%s]]></Title>
             <Discription><![CDATA[%s]]></Discription>
             <PicUrl><![CDATA[%s]]></PicUrl>
             <Url><![CDATA[%s]]></Url>
         </item>';
        
        $items = '';
        foreach ((array)$posts as $p) {
            if (is_array($p))
                $items .= sprintf($itemTpl, $p['title'], $p['description'], $p['picurl'], $p['url']);
            else
                throw new Exception('$posts 数据结构错误');
        }
        
        $text = sprintf($textTpl, $this->_msgFromUser, $this->_msgToUser, time(), self::REPLY_TYPE_NEWS, $content, count($posts), $items);
        return $text;
    }
    
    
    
    
    
    

    private function parsePostRequestData()
    {
        $rawData = $GLOBALS['HTTP_RAW_POST_DATA'];
        $data = simplexml_load_string($rawData, 'SimpleXMLElement', LIBXML_NOCDATA);
        if ($data !== false) {
            $this->msgType = $data->MsgType;
            $this->_msgToUser = $data->ToUserName;
            $this->_msgFromUser = $data->FromUserName;
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