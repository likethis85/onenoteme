<?php
class WdzWeixin extends CDWeixin
{
    const POST_JOKE_CONTENT_MIN_LEN = 20;
    
    public function processRequest()
    {
        if ($this->isTextMsg())
            $this->textMsgRequest();
        else
            $this->unSupportMsgType();
    
        exit(0);
    }
    
    private function textMsgRequest()
    {
        $hello = 'hello2bizuser';
        $input = strtolower(trim($this->_data->Content));
    
        if ($input == $hello) {
            $this->welcome();
            exit(0);
        }
        
        if (is_numeric($input)) {
            $method = 'method_' . $input[0]; // 取第一个数字
            $result = false;
            if (method_exists($this, $method)) {
                if (false === call_user_func(array($this, $method)))
                    self::error();
            }
            else
                $this->method_0();
        
            exit(0);
        }
    
        $method = 'method_' . strtolower($input);
        if (method_exists($this, $method)) {
            if (call_user_func(array($this, $method)) === false)
                $this->error();
        }
        elseif (mb_strlen($input, app()->charset) > self::POST_JOKE_CONTENT_MIN_LEN)
            $this->postJoke();
        else
            $this->method_0();
    }
    
    private function postJoke()
    {
        $post = new Post();
        $post->channel_id = CHANNEL_DUANZI;
        $post->content = strip_tags($this->_data->Content);
        $post->create_time = $_SERVER['REQUEST_TIME'];
        $post->state = POST_STATE_UNVERIFY;
        $post->up_score = mt_rand(150, 500);
        $post->down_score = mt_rand(10, 50);
        $post->view_nums = mt_rand(500, 1000);
        $post->thumbnail_pic = $post->bmiddle_pic = $post->original_pic = '';
        try {
            $result = $post->save();
        }
        catch (Exception $e) {
            $result = false;
        }
        
        $text = "感谢您的分享，偶代表火星的段友感谢您！\n";
        // 此处添加'非常'只是为了通过此查看是否保存成功
        if ($result)
            $text = '非常' . $text;
        $xml = $this->outputText($text);
        header('Content-Type: application/xml');
        echo $xml;
        exit(0);
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
    
    private function welcome()
    {
        $text = "没错！这里就是要啥有啥，想啥有啥的挖段子微信大本营！\n\n您有推荐的冷笑话或、搞笑图片或有意思的视频欢迎直接微信投稿，也可以发送给我们与大家一起分享哟～" . self::helpInfo();
        $xml = $this->outputText($text);
        header('Content-Type: application/xml');
        echo $xml;
    }
    
    private function unSupportMsgType()
    {
        $text = "Sorry，我们现在还不支持关键字搜索、图片上传和地理位置消息查询。" . self::helpInfo();
        $xml = $this->outputText($text);
        header('Content-Type: application/xml');
        echo $xml;
    }
    
    private function method_1()
    {
        $this->nextJoke();
    }
    
    private function method_2()
    {
        $this->nextLengtu();
    }
    
    private function method_3()
    {
        $this->nextGirl();
    }
    
    private function method_4()
    {
        $this->nextGhost();
    }
    
    private function method_5()
    {
        $this->nextVideo();
    }
    
    private function method_0()
    {
        $text = '您有推荐的冷笑话或、搞笑图片或有意思的视频欢迎直接微信投稿，与大家一起分享哟～' . self::helpInfo();
        $xml = $this->outputText($text);
        header('Content-Type: application/xml');
        echo $xml;
    }
    
    private function nextJoke()
    {
        $input = trim($this->_data->Content);
        $count = (int)$input[1];
        if ($count <= 0 || $count > 5)
            $count = 2;
        
        $wxid = $this->_data->FromUserName;
        $lastID = app()->getDb()->createCommand()
            ->select('last_joke_pid')
            ->from(TABLE_USER_WEIXIN)
            ->where('wx_token = :wxid', array(':wxid'=>$wxid))
            ->queryScalar();
        
        $cmd = app()->getDb()->createCommand()
            ->select(array('id', 'content'))
            ->from(TABLE_POST)
            ->where(array('and', 'state = :enabled', 'channel_id = :channelID', 'id > :lastID'), array(':enabled' => POST_STATE_ENABLED, ':channelID'=>CHANNEL_DUANZI, ':lastID' => (int)$lastID))
            ->order('id asc')
            ->limit($count);

        $rows = $cmd->queryAll();
        if (empty($rows)) return ;
        
        foreach ($rows as $row)
            $contents[] = $row['content'];
        $content = join("\n--------------------\n\n", $contents);
        $lastRow = array_pop($rows);
        $lastID = (int)$lastRow['id'];
        
        if (empty($content)) return ;
        
        if ($lastID === false) {
            $columns = array(
                'wx_token' => $wxid,
                'create_time' => time(),
                'last_time' => time(),
                'last_joke_pid' => 0,
            );
            app()->getDb()->createCommand()
                ->insert(TABLE_USER_WEIXIN, $columns);
        }
        else {
            $columns = array(
                'last_time' => time(),
                'last_joke_pid' => $lastID,
            );
            app()->getDb()->createCommand()
                ->update(TABLE_USER_WEIXIN, $columns, 'wx_token = :wxid', array(':wxid' => $wxid));
        }
        
        $i = mt_rand(0, 2);
        if ($i === 0)
            $content .= self::helpInfo();
        elseif ($i === 1) {
            $tip = "\n\n-------------------------\n段友们，是时候回复一下13，14，1x 。。。了，或许更能满足您的需求哦！";
            $content .= $tip;
        }
        $xml = $this->outputText($content);
        header('Content-Type: application/xml');
        echo $xml;
    }
    
    private function nextLengtu()
    {
        $wxid = $this->_data->FromUserName;
        $lastID = app()->getDb()->createCommand()
            ->select('last_lengtu_pid')
            ->from(TABLE_USER_WEIXIN)
            ->where('wx_token = :wxid', array(':wxid'=>$wxid))
            ->queryScalar();
        
        $params = array(':enabled' => POST_STATE_ENABLED, ':channelID'=>CHANNEL_LENGTU, ':lastID' => (int)$lastID);
        $cmd = app()->getDb()->createCommand()
            ->select(array('id', 'title', 'content', 'bmiddle_pic'))
            ->from(TABLE_POST)
            ->where(array('and', 'state = :enabled', 'channel_id = :channelID', 'id > :lastID'), $params)
            ->order('id asc');
        $row = $cmd->queryRow();
        
        if (empty($row['content'])) return ;
        
        if ($lastID === false) {
            $columns = array(
                'wx_token' => $wxid,
                'create_time' => time(),
                'last_time' => time(),
                'last_lengtu_pid' => 0,
            );
            app()->getDb()->createCommand()
                ->insert(TABLE_USER_WEIXIN, $columns);
        }
        else {
            $columns = array(
                'last_time' => time(),
                'last_lengtu_pid' => (int)$row['id'],
            );
            app()->getDb()->createCommand()
                ->update(TABLE_USER_WEIXIN, $columns, 'wx_token = :wxid', array(':wxid' => $wxid));
        }
        
        $text = h($row['title']);
        $posts = array(
            array(
                'Title' => $text,
                'Discription' => mb_strimwidth($row['content'], 0, 150, '...', app()->charset),
                'PicUrl' => $row['bmiddle_pic'],
                'Url' => aurl('mobile/post/show', array('id'=>$row['id'])),
            )
        );
        $posts = array_merge($posts, self::advert());
        $xml = $this->outputNews($text, $posts);
        header('Content-Type: application/xml');
        echo $xml;
    }
    
    private function nextGirl()
    {
        $wxid = $this->_data->FromUserName;
        $lastID = app()->getDb()->createCommand()
            ->select('last_girl_pid')
            ->from(TABLE_USER_WEIXIN)
            ->where('wx_token = :wxid', array(':wxid'=>$wxid))
            ->queryScalar();
        
        $params = array(':enabled' => POST_STATE_ENABLED, ':channelID'=>CHANNEL_GIRL, ':lastID' => (int)$lastID);
        $cmd = app()->getDb()->createCommand()
            ->select(array('id', 'title', 'content', 'bmiddle_pic'))
            ->from(TABLE_POST)
            ->where(array('and', 'state = :enabled', 'channel_id = :channelID', 'id > :lastID'), $params)
            ->order('id asc');
        $row = $cmd->queryRow();
        
        if (empty($row['content'])) return ;
        
        if ($lastID === false) {
            $columns = array(
                'wx_token' => $wxid,
                'create_time' => time(),
                'last_time' => time(),
                'last_girl_pid' => 0,
            );
            app()->getDb()->createCommand()
                ->insert(TABLE_USER_WEIXIN, $columns);
        }
        else {
            $columns = array(
                'last_time' => time(),
                'last_girl_pid' => (int)$row['id'],
            );
            app()->getDb()->createCommand()
                ->update(TABLE_USER_WEIXIN, $columns, 'wx_token = :wxid', array(':wxid' => $wxid));
        }
        
        $text = h($row['title']);
        $posts = array(
            array(
                'Title' => $text,
                'Discription' => mb_strimwidth($row['content'], 0, 150, '...', app()->charset),
                'PicUrl' => $row['bmiddle_pic'],
                'Url' => aurl('mobile/post/show', array('id'=>$row['id'])),
            )
        );
        $posts = array_merge($posts, self::advert());
        $xml = $this->outputNews($text, $posts);
        header('Content-Type: application/xml');
        echo $xml;
    }
    
    private function nextGhost()
    {
        $wxid = $this->_data->FromUserName;
        $lastID = app()->getDb()->createCommand()
            ->select('last_ghost_pid')
            ->from(TABLE_USER_WEIXIN)
            ->where('wx_token = :wxid', array(':wxid'=>$wxid))
            ->queryScalar();
        
        $params = array(':enabled' => POST_STATE_ENABLED, ':channelID'=>CHANNEL_GHOSTSTORY, ':lastID' => (int)$lastID);
        $cmd = app()->getDb()->createCommand()
            ->select(array('id', 'title', 'content'))
            ->from(TABLE_POST)
            ->where(array('and', 'state = :enabled', 'channel_id = :channelID', 'id > :lastID'), $params)
            ->order('id asc');
        $row = $cmd->queryRow();
        
        if (empty($row['content'])) return ;
        
        if ($lastID === false) {
            $columns = array(
                'wx_token' => $wxid,
                'create_time' => time(),
                'last_time' => time(),
                'last_ghost_pid' => 0,
            );
            app()->getDb()->createCommand()
                ->insert(TABLE_USER_WEIXIN, $columns);
        }
        else {
            $columns = array(
                'last_time' => time(),
                'last_ghost_pid' => (int)$row['id'],
            );
            app()->getDb()->createCommand()
                ->update(TABLE_USER_WEIXIN, $columns, 'wx_token = :wxid', array(':wxid' => $wxid));
        }
        
        $content = $row['content'] . self::helpInfo();
        $xml = $this->outputText($content);
        header('Content-Type: application/xml');
        echo $xml;
    }
    
    private function nextVideo()
    {
        $wxid = $this->_data->FromUserName;
        $lastID = app()->getDb()->createCommand()
            ->select('last_video_pid')
            ->from(TABLE_USER_WEIXIN)
            ->where('wx_token = :wxid', array(':wxid'=>$wxid))
            ->queryScalar();
        
        $params = array(':enabled' => POST_STATE_ENABLED, ':channelID'=>CHANNEL_VIDEO, ':lastID' => (int)$lastID);
        $cmd = app()->getDb()->createCommand()
            ->select(array('id', 'title', 'content', 'bmiddle_pic'))
            ->from(TABLE_POST)
            ->where(array('and', 'state = :enabled', 'channel_id = :channelID', 'id > :lastID'), $params)
            ->order('id asc');
        $row = $cmd->queryRow();
        
        if (empty($row['content'])) return ;
        
        if ($lastID === false) {
            $columns = array(
                'wx_token' => $wxid,
                'create_time' => time(),
                'last_time' => time(),
                'last_video_pid' => 0,
            );
            app()->getDb()->createCommand()
                ->insert(TABLE_USER_WEIXIN, $columns);
        }
        else {
            $columns = array(
                'last_time' => time(),
                'last_video_pid' => (int)$row['id'],
            );
            app()->getDb()->createCommand()
                ->update(TABLE_USER_WEIXIN, $columns, 'wx_token = :wxid', array(':wxid' => $wxid));
        }
        
        $text = h($row['title']);
        $posts = array(
            array(
                'Title' => $text,
                'Discription' => mb_strimwidth($row['content'], 0, 150, '...', app()->charset),
                'PicUrl' => '',
                'Url' => aurl('mobile/post/show', array('id'=>$row['id'])),
            )
        );
        $xml = $this->outputNews($text, $posts);
        header('Content-Type: application/xml');
        echo $xml;
    }
    
    private static function helpInfo()
    {
        $text = "\n\n-------------------------\n";
        $text .= "①回复 1 查看笑话\n";
        $text .= "②回复 2 查看趣图\n";
        $text .= "③回复 3 查看女神\n";
        $text .= "④回复 4 查看鬼故事\n";
        $text .= "⑤回复 0 查看帮助\n";
        $text .= '⑥投递笑话，请直接发送笑话内容，笑话必须要大于' . self::POST_JOKE_CONTENT_MIN_LEN . "字\n";
        $text .= "\n喜欢我们就召唤好友添加'挖段子'或'waduanzi'为好友关注我们吧！";
        return $text;
    }
    
    private static function error()
    {
        $text = '系统接口整在升级中，请稍候再试。。。' . self::helpInfo();
        $xml = $this->outputText($text);
        header('Content-Type: application/xml');
        echo $xml;
    }

    private static function advert()
    {
        return array(
//             array(
//                 'Title' => '天猫年货大街！春节前，天猫年货最后一拨啦！',
//                 'Discription' => '春节前，天猫年货最后一拨啦，干完这一票，风光过大年！',
//                 'PicUrl' => 'http://t2.qpic.cn/mblogpic/ef659973621af6747cf2/160',
//                 'Url' => 'http://t.cn/zj37mMZ',
//             )
        );
    }
}


