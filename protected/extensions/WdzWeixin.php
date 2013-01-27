<?php
class WdzWeixin extends CDWeixin
{
    public function processRequest($data)
    {
        $hello = 'hello2bizuser';
        $input = trim($data->Content);
        
        if (is_numeric($input)) {
            $method = 'method' . (int)$input[0]; // 取第一个数字
            $result = false;
            if (method_exists($this, $method)) {
                $result = call_user_func(array($this, $method), $data);
                if ($result === false)
                    self::error();
            }
        }

        $this->method0();
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
    
    private function method1($data)
    {
        $this->nextJoke($data);
    }
    
    private function method2($data)
    {
        $this->nextLengtu($data);
    }
    
    private function method3($data)
    {
        $this->nextGirl($data);
    }
    
    private function method4($data)
    {
        $this->nextVideo($data);
    }
    
    private function method0()
    {
        $text = '您有推荐的冷笑话或、搞笑图片或有意思的视频欢迎直接微信投稿，与大家一起分享哟～' . self::helpInfo();
        $xml = $this->outputText($text);
        header('Content-Type: application/xml');
        echo $xml;
    }
    
    private function nextJoke($data)
    {
        $input = trim($data->Content);
        $count = (int)$input[1];
        if ($count <= 0 || $count > 5)
            $count = 2;
        
        $wxid = $data->FromUserName;
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
    
    private function nextLengtu($data)
    {
        $wxid = $data->FromUserName;
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
    
    private function nextGirl($data)
    {
        $wxid = $data->FromUserName;
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
    
    private function nextVideo($data)
    {
        $wxid = $data->FromUserName;
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
        $text = "\n\n-------------------------\n回复 1 查看笑话\n回复 2 查看趣图\n回复 3 查看女神\n回复 0 查看帮助\n\n喜欢我们就召唤好友添加'挖段子'或'waduanzi'为好友关注我们吧！";
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


