<?php
class WeixinController extends Controller
{
    const TOKEN = 'waduanzi.com';
    
    public function actionIndex()
    {
        if (request()->getIsPostRequest()) {
            $data = $this->parsePostRequestData();
            $this->processRequest($data);
        }
        else
            $this->sourceCheck();

        exit(0);
    }
    
    private function checkSignature()
    {
        $signature = $_GET['signature'];
        $timestamp = $_GET['timestamp'];
        $nonce = $_GET['nonce'];
    
        $tmpArr = array(self::TOKEN, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
    
        return $tmpStr == $signature;
    }
    
    private function sourceCheck()
    {
        if ($this->checkSignature()) {
            $echoStr = $_GET['echostr'];
            echo $echostr;
        }
        
        exit(0);
    }
    
    private function parsePostRequestData()
    {
        $rawData = $GLOBALS["HTTP_RAW_POST_DATA"];
        $data = simplexml_load_string($rawData, 'SimpleXMLElement', LIBXML_NOCDATA);
        
        return $data;
    }
    
    private function processRequest($data)
    {
        $fromUsername = $data->FromUserName;
        $toUsername = $data->ToUserName;
        $keyword = trim($data->Content);
        $time = time();
        
        $textTpl = "<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s}]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[%s]]></MsgType>
            <Content><![CDATA[%s]]></Content>
            <FuncFlag>0<FuncFlag>
            </xml>";
        if (empty($keyword))
            echo 'Input something...';
        else {
            $msgType = "text";
            $contentStr = "Welcome to wechat world!";
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            file_put_contents(app()->getRuntimePath() . '/wx.txt', $resultStr);
            echo $resultStr;
        }
    }
    
}

