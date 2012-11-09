<?php
class CDWeixin
{
    const MSG_TYPE_TEXT = 'text';
    const MSG_TYPE_LOCATION = 'location';
    
    const REPLY_TYPE_TEXT = 'text';
    const REPLY_TYPE_NEWS = 'news';

    /**
     * 接收到的post数据
     * @var object
     */
    private $_postData;
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
        $this->parsePostRequestData();
    }
    
    public function run()
    {
        if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
            if ($this->_postData && $this->beforeProcess() === true) {
                $this->processRequest($this->_postData);
                $this->afterProcess();
            }
            else
                throw new Exception('POST 数据不正确或者beforeProcess方法没有返回true');
        }
        else
            $this->sourceCheck();
        
        exit(0);
    }
    
    public function isTextMsg()
    {
        return $this->_postData->MsgType == self::MSG_TYPE_TEXT;
    }
    
    public function isLocationMsg()
    {
        return $this->_postData->MsgType == self::MSG_TYPE_LOCATION;
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
    
        $text = sprintf($textTpl, $this->_postData->FromUserName, $this->_postData->ToUserName, time(), self::REPLY_TYPE_TEXT, $content);
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
                $items .= sprintf($itemTpl, $p['Title'], $p['Discription'], $p['PicUrl'], $p['Url']);
            else
                throw new Exception('$posts 数据结构错误');
        }
        
        $text = sprintf($textTpl, $this->_postData->FromUserName, $this->_postData->ToUserName, time(), self::REPLY_TYPE_NEWS, $content, count($posts), $items);
        return $text;
    }
    
    
    public function parsePostRequestData()
    {
        $rawData = $GLOBALS['HTTP_RAW_POST_DATA'];
        $data = simplexml_load_string($rawData, 'SimpleXMLElement', LIBXML_NOCDATA);
        if ($data !== false)
            $this->_postData = $data;
    
        return $data;
    }
    
    
    public function getPostData()
    {
        return $this->_postData;
    }
    
    protected function beforeProcess()
    {
        return true;
    }
    
    protected function afterProcess()
    {
    }

    protected function processRequest($data)
    {
        throw new Exception('此方法必须被重写');
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
    
    private function sourceCheck()
    {
        if ($this->checkSignature()) {
            $echoStr = $_GET['echostr'];
            echo $echostr;
        }
        else
            throw new Exception('签名不正确');
    
        exit(0);
    }
}

