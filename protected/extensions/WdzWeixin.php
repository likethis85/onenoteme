<?php
class WdzWeixin extends CDWeixin
{
    public function processRequest($data)
    {
        $input = trim($data->Content);
        file_put_contents(app()->getRuntimePath() . DS . 'wx.txt', input);
        if (empty($input))
            $this->method0();
        else
            $this->random();
        
    }
    
    public function errorHandler($errno, $error, $file = '', $line = 0)
    {
//         $log = sprintf('%s - %s - %s - %s', $errno, $error, $file, $line);
//         file_put_contents(app()->runtimePath . '/wx1.txt', $log);
    }
    
    public function errorException(Exception $e)
    {
//         $log = sprintf('%s - %s - %s - %s', $e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine());
//         file_put_contents(app()->runtimePath . '/wx2.txt', $log);
    }
    
    private function method1()
    {
        $text = '最冷笑话精选，每天分享笑话N枚，你的贴身开心果';
        $posts = array(
            array(
                'Title' => '挖笑话，每日最冷笑话精选',
                'Discription' => $text,
                'PicUrl' => 'http://s.waduanzi.com/images/wx1.jpg',
                'Url' => 'http://m.waduanzi.com/joke',
            )
        );
        $xml = $this->outputNews($text, $posts);
        header('Content-Type: application/xml');
        echo $xml;
    }
    
    private function method2()
    {
        $text = '挖趣图 - 最搞笑的，最好玩的，最内涵的图片精选';
        $posts = array(
            array(
                'Title' => '挖趣图 - 最内涵的图片精选',
                'Discription' => $text,
                'PicUrl' => 'http://s.waduanzi.com/images/wx2.jpg',
                'Url' => 'http://m.waduanzi.com/lengtu',
            )
        );
        $xml = $this->outputNews($text, $posts);
        header('Content-Type: application/xml');
        echo $xml;
    }
    
    private function method3()
    {
        $text = '挖福利 - 最新最全的女明星写真、清纯校花、美女模特、正妹性感自拍';
        $posts = array(
            array(
                'Title' => '挖福利 - 最新最全的美女模特',
                'Discription' => $text,
                'PicUrl' => 'http://s.waduanzi.com/images/wx3.jpg',
                'Url' => 'http://m.waduanzi.com/girl',
            )
        );
        $xml = $this->outputNews($text, $posts);
        header('Content-Type: application/xml');
        echo $xml;
    }
    
    private function method9()
    {
        $text = '最新发布的段子，每日精品笑话连载。网罗互联网各种精品段子，各种糗事，各种笑话，各种秘密，各种经典语录，各种有趣的图片，各种漂亮mm校花模特正妹，应有尽有。烦了、累了、无聊了，就来挖段子逛一逛。"';
        $posts = array(
            array(
                'Title' => '欢迎访问挖段子网手机版',
                'Discription' => $text,
                'PicUrl' => 'http://s.waduanzi.com/images/wx.jpg',
                'Url' => 'http://m.waduanzi.com',
            )
        );
        $xml = $this->outputNews($text, $posts);
        header('Content-Type: application/xml');
        echo $xml;
    }
    
    private function method0()
    {
        $text = "您有推荐的冷笑话或、搞笑图片或有意思的视频欢迎直接微信投稿，与大家一起分享哟～\n\n回复 1 查看最新笑话；\n回复 0 查看使用帮助";
        $xml = $this->outputText($text);
        header('Content-Type: application/xml');
        echo $xml;
    }
    
    private function random()
    {
        $duration = 24 * 60 * 60;
        $maxID = app()->getDb()->cache($duration)->createCommand()
            ->select('max(id)')
            ->from(TABLE_POST)
            ->queryScalar();
        
        $randomID = mt_rand(0, $maxID);
        $cmd = app()->getDb()->createCommand()
            ->select('content')
            ->from(TABLE_POST)
            ->where(array('and', 'state = :enabled', 'channel_id = 0', 'id > :randomID'), array(':enabled' => POST_STATE_ENABLED, ':randomID' => $randomID));
        $content = $cmd->queryScalar();
        
        if (empty($content)) return ;
        
        $content .= "\n\n回复 1 查看下一条\n回复 0 查看使用帮助";
        $xml = $this->outputText($content);
        header('Content-Type: application/xml');
        echo $xml;
    }
}


