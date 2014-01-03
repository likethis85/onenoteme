<?php
class WeiboCommand extends CConsoleCommand
{
    const ERROR_WEIBO_EXIST = -1;
    
    const ACCOUNT_SLEEP_TIME = 10;
    const WEIBO_ROWS_COUNT = 50;
    
    const APP_KEY = '456860706';
    const APP_SECERET = '19168ffef668231aa22f74683d3d18e7';
    
    public function actionCollect()
    {
        $weiboAccouts = self::fetchWeiboAccounts();
        $count = count($weiboAccouts);
        foreach ($weiboAccouts as $index => $account) {
            $this->collectOnce($account);
            echo date('Y-m-d H:i:s', time()) . ' - sleep ' . self::ACCOUNT_SLEEP_TIME . " seconds\n";
            if ($index < $count-1)
                sleep(self::ACCOUNT_SLEEP_TIME);
        }
    }
    
    private function collectOnce($account)
    {
        $accountName = $account['display_name'];
        $since_id = $account['last_pid'] ? $account['last_pid'] : 0;
        
        $url = 'https://api.weibo.com/2/statuses/user_timeline.json';
        $params = array(
            'source' => self::APP_KEY,
            'screen_name' => $accountName,
            'since_id' => $since_id,
            'count' => self::WEIBO_ROWS_COUNT,
            'trim_user' => 0,
        );
        
        $fetch = new CDCurl();
        $fetch->ssl()->get($url, $params);
        $jsonData = $fetch->rawdata();
        
        $text = date('Y-m-d H:i:s', time()) . ' - ';
        $errno = $fetch->errno();
        if ($errno !== 0) {
            $text .= 'access api error: ' . $fetch->error() . "\n";
            echo $text;
            return false;
        }

        $rows = json_decode($jsonData, true);
        if (empty($rows['statuses'])) {
            $text .= "$accountName, no latest posts.\n";
            echo $text;
            return false;
        }
        
        $count = 0;
        foreach ((array)$rows['statuses'] as $index => $row) {
            $pid = $row['idstr'];
            if (array_key_exists('retweeted_status', $row))
                $row = $row['retweeted_status'];
            
            try {
                $result = self::saveRow($row, $account);
                if ($result === true) $count++;
                if ($index == 0)
                    self::updateLastTimeAndPID($accountName, $pid);
            }
            catch (Exception $e) {
                $text .= "ID: $pid - Save Exception\n";
                echo $text;
                continue;
            }
        }
        $text .= "Account: {$accountName}, Total Count: {$count}\n";
        echo $text;
        return $count;
    }
    
    private static function saveRow($row, $account)
    {
        $prompt = date('Y-m-d H:i:s', time()) . ' - ID: ' . $row['idstr'] . ' - ';
        
        $idstr = strip_tags(trim($row['idstr']));
        $exist = self::checkWeiboExist($idstr);
        if ($exist) {
            $prompt .= "This row is exist\n";
            return self::ERROR_WEIBO_EXIST;
        }
        
        $text = $row['text'];
        if (empty($text)) return false;
        
        if (self::keywordsFilter($text) === false)
            return false;
        
        $text = preg_replace('/(#.*?#)|(（.*?）)|(\(.*?\))|(【.*?】)|(http:\/\/t\.cn\/\w+)|(@.+?\s{1})/is', '', $text);
        if (array_key_exists('thumbnail_pic', $row)) {
            $temp['thumbnail_pic'] = $row['thumbnail_pic'];
            $temp['bmiddle_pic'] = $row['bmiddle_pic'];
            $temp['original_pic'] = $row['original_pic'];
            $temp['content'] = $text;
        }
        elseif (mb_strlen($text) > 10) {
            $temp['content'] = $text;
        }
        else
            return false;
        
        $temp['repost_count'] = (int)$row['reposts_count'];
        $temp['comment_count'] = (int)$row['comments_count'];
        $temp['weibo_id'] = $idstr;
        $temp['user_id'] = $account['user_id'];
        $temp['user_name'] = $account['user_name'];
        $temp['account_id'] = $account['id'];
        
        $model = new PostTemp();
        $model->attributes = $temp;
        
        $result = $model->save();
        
        if ($model->hasErrors()) {
            $prompt .= "\n";
            echo $prompt;
            $errors = $model->getErrors();
            print_r($errors);
        }
        else {
            self::saveWeiboID($idstr);
            $prompt .= "Save Success\n";
            echo $prompt;
        }
        
        return $result;
    }
    
    private static function keywordsFilter($text)
    {
        $words = array('代购', '地址在这里', '推荐给大家', '宝贝地址', '推荐MM', '限时打折', '黑头', '现价',
                '原价', '包邮', '正品', '特价', '推荐收藏', '抢购', '秒杀地址', '开团', '秒杀');
        foreach ($words as $word) {
            $pos = strpos($text, $word);
            if ($pos !== false)
                return false;
        }
            
        return true;
    }
    
    private static function fetchWeiboAccounts()
    {
        $rows = app()->getDb()->createCommand()
            ->from(TABLE_WEIBO_ACCOUNT)
            ->order('id asc')
            ->queryAll();
        
        return $rows;
    }
    
    private static function updateLastTimeAndPID($account, $pid)
    {
        $columns = array(
            'last_time' => $_SERVER['REQUEST_TIME'],
            'last_pid' => $pid,
        );
        $result = app()->getDb()->createCommand()
            ->update(TABLE_WEIBO_ACCOUNT, $columns, 'display_name = :name', array(':name' => $account));
        
    }
    
    private static function checkWeiboExist($id)
    {
        $count = app()->getDb()->createCommand()
            ->select('count(*)')
            ->from(TABLE_WEIBO_ID)
            ->where('wid = :id', array(':id'=>$id))
            ->queryScalar();
        
        return $count > 0;
    }
    
    private static function saveWeiboID($idstr)
    {
        $columns = array('wid' => $idstr);
        $result = app()->getDb()->createCommand()
            ->insert(TABLE_WEIBO_ID, $columns);
        
        return $result > 0;
    }
    
/*********** 定时发送到新浪微博和腾讯微博 *************************************/
    
    public function actionTestToken()
    {
        $sinaToken = redis()->get('sina_weibo_access_token');
        $qqToken = redis()->get('qq_weibo_access_token');

        var_dump($sinaToken);
        var_dump($qqToken);
    }
    
    public function actionPostToWeibo()
    {
        $prompt = date('Y-m-d H:i:s - ', time());
        $sinaToken = redis()->get('sina_weibo_access_token');
        $qqToken = redis()->get('qq_weibo_access_token');
        if (empty($sinaToken) || empty($qqToken)) {
            echo $prompt . "token expired.\n";
            exit(0);
        }
        
        $models = self::fetchWeiboPosts();
        
        if (empty($models)) {
            echo $prompt . "models is empty.\n";
            exit(0);
        }
        
        foreach ($models as $model) {
            $picUrl = $model->getMiddlePic();
            if (empty($picUrl)) {
                if (mt_rand(0, 2) > 0)
                    $picUrl = 'http://ww4.sinaimg.cn/bmiddle/61b3022etw1e2hlgcttk8j.jpg';
            }
            
            if (empty($picUrl)) {
                $result = self::sinatUpdate($model);
                $result2 = self::qqtUpdate($model);
            }
            else {
                $result = self::sinatUpload($model, $picUrl);
                $result2 = self::qqtUpload($model, $picUrl);
            }
            
            if ($result !== false) {
                $model->weibo_id = $result;
                $model->save(true, array('weibo_id'));
            }
            
            echo $prompt . (($result === false) ? 'sina failed' : 'sina success, weibo id: ' . $result) . "\n";
            echo $prompt . (($result2 === false) ? 'qqt failed' : 'qqt success, weibo id: ' . $result2) . "\n";
        }
    }
    
    private static function sinatShortUrl($longUrl)
    {
        $url = 'https://api.weibo.com/2/short_url/shorten.json';
        $data = array(
        'source' => WEIBO_APP_KEY,
        'url_long' => $longUrl,
        );
    
        $curl = new CDCurl();
        $curl->get($url, $data);
        if ($curl->errno() == 0) {
            $result = json_decode($curl->rawdata(), true);
            $short = $result['urls'][0];
            return ($short['result']) ? $short['url_short'] : false;
        }
        else
            return false;
    }
    
    private static function fetchWeiboPosts()
    {
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(array('channel_id'=>CHANNEL_FUNNY, 'media_type'=>MEDIA_TYPE_TEXT, 'state'=>POST_STATE_ENABLED, 'weibo_id' => ''));
        $criteria->order = 't.id desc';
        $models[] = Post::model()->find($criteria);
    
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(array('channel_id'=>CHANNEL_FUNNY, 'media_type'=>MEDIA_TYPE_IMAGE, 'state'=>POST_STATE_ENABLED, 'weibo_id' => ''));
        $criteria->order = 't.id desc';
        $models[] = Post::model()->find($criteria);
    
        /*
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(array('channel_id'=>CHANNEL_GIRL, 'media_type'=>MEDIA_TYPE_IMAGE, 'state'=>POST_STATE_ENABLED, 'weibo_id' => ''));
        $criteria->order = 't.id desc';
        $models[] = Post::model()->find($criteria);
        */
    
        return $models;
        
    }
    
    private static function sinatUpdate(Post $model)
    {
        if (empty($model->content)) return false;
    
        $url = 'https://api.weibo.com/2/statuses/update.json';
    
        $postUrl = 'http://www.waduanzi.com/archives/' . $model->id;
        $sinatShortUrl = self::sinatShortUrl($postUrl);
        $tail = '...' . $sinatShortUrl;
        if ($model->channel_id == CHANNEL_FUNNY)
            $tail = '#搞笑#' . $tail;
        $accounts = self::fetchSinatRelativeAccounts(2);
        $tags = self::fetchPostTags($model, 2);
        $subLen = 140 - mb_strlen($tags, app()->charset) - mb_strlen($tail, app()->charset) - mb_strlen($accounts, app()->charset);
        $content = mb_substr(strip_tags($model->content), 0, $subLen, app()->charset) . $accounts . $tail . $tags;
        
        $data = array(
            'source' => WEIBO_APP_KEY,
            'access_token' => redis()->get('sina_weibo_access_token'),
            'status' => urlencode($content),
        );
        foreach ($data as $key => $item)
            $args[] = urlencode($key) . '=' . $item;
    
        $curl = new CDCurl();
        $curl->post($url, join('&', $args));
//         var_dump($curl->rawdata());
//         var_dump($curl->errno());exit;
        if ($curl->errno() == 0) {
            $result = json_decode($curl->rawdata(), true);
            return $result['idstr'] ? $result['idstr'] : false;
        }
        else
            return false;
    }
    
    /*
    private static function sinatUpload(Post $model)
    {
        if (empty($model->content)) return false;
    
        $url = 'https://api.weibo.com/2/statuses/upload_url_text.json';
    
        $postUrl = 'http://www.waduanzi.com/archives/' . $model->id;
        $sinatShortUrl = self::sinatShortUrl($postUrl);
        $tail = '...' . $sinatShortUrl;
        if ($model->channel_id == CHANNEL_FUNNY)
            $tail = '#搞笑#' . $tail;
        $accounts = self::fetchSinatRelativeAccounts2);
        $tags = self::fetchPostTags($model, 2);
        $subLen = 140 - mb_strlen($tags, app()->charset) - mb_strlen($tail, app()->charset) - mb_strlen($accounts, app()->charset);
        $content = mb_substr($model->content, 0, $subLen, app()->charset) . $tail . $tags. $accounts;
        $data = array(
            'source' => WEIBO_APP_KEY,
            'access_token' => redis()->get('sina_weibo_access_token'),
            'status' => urlencode($content),
            'url' => $model->getOriginalPic(),
        );
        foreach ($data as $key => $item)
            $args[] = urlencode($key) . '=' . $item;
            
        $curl = new CDCurl();
        $curl->post($url, join('&', $args));
//         var_dump($curl->rawdata());
//         var_dump($curl->errno());exit;
        if ($curl->errno() == 0) {
            $result = json_decode($curl->rawdata(), true);
            return $result['idstr'] ? $result['idstr'] : false;
        }
        else
            return false;
    }
    */
    
    
    private static function sinatUpload(Post $model, $picUrl = '')
    {
        if (empty($model->content)) return false;
    
        $curl = new CDCurl();
        $curl->get($picUrl);
        if ($curl->errno() == 0) {
            $picData = $curl->rawdata();
            $picfile = app()->getRuntimePath() . DS . uniqid();
            $result = file_put_contents($picfile, $picData);
            if ($result === false)
                throw new CException('生成临时文件出错', 0);
        }
        else
            return false;
    
        $url = 'https://upload.api.weibo.com/2/statuses/upload.json';
    
        $postUrl = 'http://www.waduanzi.com/archives/' . $model->id;
        $sinatShortUrl = self::sinatShortUrl($postUrl);
        $tail = '...' . $sinatShortUrl;
        if ($model->channel_id == CHANNEL_FUNNY)
            $tail = '#搞笑#' . $tail;
        $accounts = self::fetchSinatRelativeAccounts(2);
        $tags = self::fetchPostTags($model, 2);
        $subLen = 140 - mb_strlen($tags, app()->charset) - mb_strlen($tail, app()->charset) - mb_strlen($accounts, app()->charset);
        $content = mb_substr(strip_tags($model->content), 0, $subLen, app()->charset) . $tags. $accounts . $tail;
        $data = array(
            'source' => WEIBO_APP_KEY,
            'access_token' => redis()->get('sina_weibo_access_token'),
            'status' => $content,
            'pic' => '@' . $picfile,
        );
    
        $curl = new CDCurl();
        $curl->post($url, $data);
        @unlink($picfile);
        if ($curl->errno() == 0) {
            $result = json_decode($curl->rawdata(), true);
            return $result['idstr'] ? $result['idstr'] : false;
        }
        else
            return false;
    }
    
    
    private static function qqtUpdate(Post $model)
    {
        if (empty($model->content)) return false;
    
        $url = 'https://open.t.qq.com/api/t/add';
    
        $postUrl = 'http://www.waduanzi.com/archives/' . $model->id;
        $sinatShortUrl = self::sinatShortUrl($postUrl);
        $tail = '...' . $sinatShortUrl;
        if ($model->channel_id == CHANNEL_FUNNY)
            $tail = '#搞笑#' . $tail;
        $accounts = self::fetchQQtRelativeAccounts(2);
        $tags = self::fetchPostTags($model, 2);
        $subLen = 140 - mb_strlen($tags, app()->charset) - mb_strlen($tail, app()->charset) - mb_strlen($accounts, app()->charset);
        $content = mb_substr(strip_tags($model->content), 0, $subLen, app()->charset) . $tags . $accounts . $tail;
        $data = array(
            'oauth_consumer_key' => QQT_APP_KEY,
            'access_token' => redis()->get('qq_weibo_access_token'),
            'openid' => redis()->get('qq_weibo_user_id'),
            'clientip' => request()->getUserHostAddress(),
            'oauth_version' => '2.a',
            'scope' => 'all',
            'format' => 'json',
            'content' => $content,
            'syncflag' => 1, // 不同步到空间
        );
        foreach ($data as $key => $item)
            $args[] = urlencode($key) . '=' . $item;
    
        $curl = new CDCurl();
        $curl->post($url, join('&', $args));
        //         var_dump($curl->rawdata());
        //         var_dump($curl->errno());exit;
        if ($curl->errno() == 0) {
            $data = json_decode($curl->rawdata(), true);
            return ($data['ret'] == 0) ? $data['data']['id'] : false;
        }
        else
            return false;
    
    }
    
    private static function qqtUpload(Post $model, $picUrl = '')
    {
        if (empty($model->content)) return false;
    
        $url = 'https://open.t.qq.com/api/t/add_pic_url';
    
        $postUrl = 'http://www.waduanzi.com/archives/' . $model->id;
        $sinatShortUrl = self::sinatShortUrl($postUrl);
        $tail = '...' . $sinatShortUrl;
        if ($model->channel_id == CHANNEL_FUNNY)
            $tail = '#搞笑#' . $tail;
        $accounts = self::fetchQQtRelativeAccounts(2);
        $tags = self::fetchPostTags($model, 2);
        $subLen = 140 - mb_strlen($tags, app()->charset) - mb_strlen($tail, app()->charset) - mb_strlen($accounts, app()->charset);
        $content = mb_substr(strip_tags($model->content), 0, $subLen, app()->charset) . $tags . $accounts . $tail;
        $data = array(
            'oauth_consumer_key' => QQT_APP_KEY,
            'access_token' => redis()->get('qq_weibo_access_token'),
            'openid' => redis()->get('qq_weibo_user_id'),
            'clientip' => request()->getUserHostAddress(),
            'oauth_version' => '2.a',
            'scope' => 'all',
            'format' => 'json',
            'content' => $content,
            'syncflag' => 1, // 不同步到空间
            'pic_url' => $picUrl,
        );
        foreach ($data as $key => $item)
            $args[] = urlencode($key) . '=' . $item;
    
        $curl = new CDCurl();
        $curl->post($url, join('&', $args));
        $jsonData = json_decode($curl->rawdata(), true);
//         var_dump($curl->rawdata());
//         var_dump($curl->errno());exit;
        if ($curl->errno() == 0 && $jsonData['ret'] == 0) {
            $data = json_decode($curl->rawdata(), true);
            return $data['data']['id'];
        }
        else
            return false;
    }
    
    private static function fetchPostTags(Post $model, $count = 1)
    {
        $tags = '';
        if ($model->tags) {
            $tags = array_slice($model->getTagArray(), 0, $count);
            $tags = '#' . join('##', $tags) . '#';
        }
        return $tags;
    }
    
    private static function fetchSinatRelativeAccounts($count = 2)
    {
        $url = 'https://upload.api.weibo.com/2/statuses/public_timeline.json';
        
        $args = array(
            'source' => WEIBO_APP_KEY,
            'access_token' => redis()->get('sina_weibo_access_token'),
            'count' => $count,
        );
        
        $curl = new CDCurl();
        $curl->get($url, $args);
//         var_dump($curl->rawdata());
//         var_dump($curl->errno());exit;
        $statuses = array();
        if ($curl->errno() == 0) {
            $result = json_decode($curl->rawdata(), true);
            $statuses = $result['statuses'];
        }
        else
            return false;
        
        $text = '';
        $accounts = array();
        foreach ($statuses as $status)
            $accounts[] = '@' . $status['user']['screen_name'];
        $text = join(' ', $accounts);
        
        return $text;
    }
    
    private static function fetchQQtRelativeAccounts($count = 2)
    {
        $url = 'https://open.t.qq.com/api/statuses/public_timeline';
        
        $args = array(
            'oauth_consumer_key' => QQT_APP_KEY,
            'access_token' => redis()->get('qq_weibo_access_token'),
            'openid' => redis()->get('qq_weibo_user_id'),
            'clientip' => request()->getUserHostAddress(),
            'oauth_version' => '2.a',
            'scope' => 'all',
            'pos' => 0,
            'format' => 'json',
            'reqnum' => $count,
        );
        
        $curl = new CDCurl();
        $curl->get($url, $args);
//         var_dump($curl->rawdata());
//         var_dump($curl->errno());exit;
        $users = array();
        if ($curl->errno() == 0) {
            $data = json_decode($curl->rawdata(), true);
            if ($data['ret'] == 0)
                $users = array_keys($data['data']['user']);
            else
                return false;
        }
        else
            return false;
        
        $text = '';
        $accounts = array();
        foreach ($users as $user)
            $accounts[] = '@' . $user;
        $text = join(' ', $accounts);
        return $text;
    }
    
    
}



