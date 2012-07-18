<?php
class WeiboCommand extends CConsoleCommand
{
    const ERROR_WEIBO_EXIST = -1;
    
    const ACCOUNT_SLEEP_TIME = 30;
    const WEIBO_ROWS_COUNT = 50;
    const JINGXUAN_SLEEP_TIME = 60;
    const WEIBO_JINGXUAN_ROWS_COUNT = 100;
    
    const APP_KEY = '456860706';
    const APP_SECERET = '19168ffef668231aa22f74683d3d18e7';
    
    public function actionJingxuan()
    {
        $types = array(2, 3, 6);
        foreach ($types as $type) {
            self::collectJingxuanWithType($type);
            sleep(self::JINGXUAN_SLEEP_TIME);
        }
    }
    
    private static function collectJingxuanWithType($typeID)
    {
        $url = 'https://api.weibo.com/2/suggestions/statuses/hot.json';
        $params = array(
            'source' => self::APP_KEY,
            'is_pic' => 0,
            'count' => self::WEIBO_JINGXUAN_ROWS_COUNT,
            'type' => (int)$typeID,
        );
        
        $fetch = new CdCurl();
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
            $text .= "no latest posts.\n";
            echo $text;
            return false;
        }
//         print_r($rows);
        $count = 0;
        foreach ((array)$rows['statuses'] as $index => $row) {
            $row = $row['status'];
            $pid = $row['idstr'];
            try {
                $result = self::saveRow($row);
                if ($result) $count++;
            }
            catch (Exception $e) {
                var_dump($e->getMessage());
                $text .= "ID: $pid - Save Exception\n";
                echo $text;
                continue;
            }
        }
        $text .= "Jingxuan Type: {$typeID}, Total Count: {$count}\n";
        echo $text;
    }
    
    public function actionCollect()
    {
        $weiboAccouts = self::fetchWeiboAccounts();
        $count = count($weiboAccouts);
        foreach ($weiboAccouts as $index => $account) {
            $this->collectOnce($account['display_name'], $account['last_pid']);
            echo date('Y-m-d H:i:s', time()) . ' - sleep ' . self::ACCOUNT_SLEEP_TIME . " seconds\n";
            if ($index < $count-1)
                sleep(self::ACCOUNT_SLEEP_TIME);
        }
    }
    
    private function collectOnce($account, $since_id)
    {
        $since_id = $since_id ? $since_id : 0;
        $url = 'https://api.weibo.com/2/statuses/user_timeline.json';
        $params = array(
            'source' => self::APP_KEY,
            'screen_name' => $account,
            'since_id' => $since_id,
            'count' => self::WEIBO_ROWS_COUNT,
            'trim_user' => 0,
        );
        
        $fetch = new CdCurl();
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
            $text .= "no latest posts.\n";
            echo $text;
            return false;
        }
        
        $count = 0;
        foreach ((array)$rows['statuses'] as $index => $row) {
            $pid = $row['idstr'];
            if (array_key_exists('retweeted_status', $row))
                $row = $row['retweeted_status'];
            
            try {
                $result = self::saveRow($row);
                if ($result) $count++;
                if ($index == 0)
                    self::updateLastTimeAndPID($account, $pid);
            }
            catch (Exception $e) {
                $text .= "ID: $pid - Save Exception\n";
                echo $text;
                continue;
            }
        }
        $text .= "Account: {$account}, Total Count: {$count}\n";
        echo $text;
    }
    
    private static function saveRow($row)
    {
        $prompt = date('Y-m-d H:i:s', time()) . ' - ID: ' . $idstr . ' - ';
        
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
        
        $temp['repost_count'] = (int)$row['repost_count'];
        $temp['comment_count'] = (int)$row['comment_count'];
        $temp['username'] = $row['user']['screen_name'];
        $temp['weibo_id'] = $idstr;
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
        $words = array('代购', '地址在这里', '推荐给大家', '宝贝地址', '推荐MM', '限时打折');
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
            ->select('display_name, last_pid')
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
    
    public function actionPostToWeibo()
    {
        $sinaToken = app()->cache->get('sina_weibo_access_token');
        $qqToken = app()->cache->get('qq_weibo_access_token');
        if (empty($sinaToken) || empty($qqToken)) {
            echo 'token expired.';
            exit(0);
        }
        
        $models = self::fetchWeiboPosts();
        foreach ($models as $model) {
            $picUrl = $model->getBmiddlePic();
            if (empty($picUrl)) {
                $result = self::SinatUpdate($model);
                $result2 = self::qqtUpdate($model);
            }
            else {
                $result = self::sinatUpload($model);
                $result2 = self::qqtUpload($model);
            }
            
            if ($result !== false) {
                $model->weibo_id = $result;
                $model->save(true, array('weibo_id'));
            }
            
            echo ($result === false) ? 'sina failed' : 'sina success, weibo id: ' . $result;
            echo ($result2 === false) ? 'qqt failed' : 'qqt success, weibo id: ' . $result2;
        }
    }
    
    private static function fetchWeiboPosts()
    {
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(array('channel_id'=>CHANNEL_DUANZI, 'weibo_id' => ''));
        $criteria->order = 't.id asc';
        $models[] = AdminPost::model()->find($criteria);
    
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(array('channel_id'=>CHANNEL_LENGTU, 'weibo_id' => ''));
        $criteria->order = 't.id asc';
        $models[] = AdminPost::model()->find($criteria);
    
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(array('channel_id'=>CHANNEL_GIRL, 'weibo_id' => ''));
        $criteria->order = 't.id asc';
        $models[] = AdminPost::model()->find($criteria);
    
        $data = array(
            'models' => $models,
        );
    }
    
    private static function SinatUpdate(AdminPost $model)
    {
        if (empty($model->content)) return false;
    
        $url = 'https://upload.api.weibo.com/2/statuses/update.json';
    
    
        $sinatShortUrl = self::sinatShortUrl($model->getUrl());
        $urlLen = empty($sinatShortUrl) ? 0 : strlen($sinatShortUrl);
        $content = mb_substr($model->content, 0, 130 - $urlLen, app()->charset) . '...' . $sinatShortUrl . ' @挖段子网';
        $data = array(
            'source' => WEIBO_APP_KEY,
            'access_token' => app()->cache->get('sina_weibo_access_token'),
            'status' => $content,
        );
        foreach ($data as $key => $item)
            $args[] = urlencode($key) . '=' . $item;
    
        $curl = new CdCurl();
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
    
    private static function sinatUpload(AdminPost $model)
    {
        if (empty($model->content)) return false;
    
        $curl = new CdCurl();
        $curl->get($model->getBmiddlePic());
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
    
        $sinatShortUrl = self::sinatShortUrl($model->getUrl());
        $urlLen = empty($sinatShortUrl) ? 0 : strlen($sinatShortUrl);
        $content = mb_substr($model->content, 0, 130 - $urlLen, app()->charset) . '...' . $sinatShortUrl . ' @挖段子网';
        $data = array(
        'source' => WEIBO_APP_KEY,
        'access_token' => app()->cache->get('sina_weibo_access_token'),
        'status' => $content,
        'pic' => '@' . $picfile,
        );
    
        $curl = new CdCurl();
        $curl->post($url, $data);
        @unlink($picfile);
        if ($curl->errno() == 0) {
            $result = json_decode($curl->rawdata(), true);
            return $result['idstr'] ? $result['idstr'] : false;
        }
        else
            return false;
    }
    
    private static function sinatShortUrl($longUrl)
    {
        $url = 'https://api.weibo.com/2/short_url/shorten.json';
        $data = array(
        'source' => WEIBO_APP_KEY,
        'url_long' => $longUrl,
        );
    
        $curl = new CdCurl();
        $curl->get($url, $data);
        if ($curl->errno() == 0) {
            $result = json_decode($curl->rawdata(), true);
            $short = $result['urls'][0];
            return ($short['result']) ? $short['url_short'] : false;
        }
        else
            return false;
    }
    
    private static function qqtUpdate(AdminPost $model)
    {
        if (empty($model->content)) return false;
    
        $url = 'https://open.t.qq.com/api/t/add';
    
        $sinatShortUrl = self::sinatShortUrl($model->getUrl());
        $urlLen = empty($sinatShortUrl) ? 0 : strlen($sinatShortUrl);
        $content = mb_substr($model->content, 0, 130 - $urlLen, app()->charset) . '...' . $sinatShortUrl . ' @cdcchen';
        $data = array(
            'oauth_consumer_key' => QQT_APP_KEY,
            'access_token' => app()->cache->get('qq_weibo_access_token'),
            'openid' => app()->cache->get('qq_weibo_user_id'),
            'clientip' => request()->getUserHostAddress(),
            'oauth_version' => '2.a',
            'scope' => 'all',
            'format' => 'json',
            'content' => $content,
            'syncflag' => 0,
        );
        foreach ($data as $key => $item)
            $args[] = urlencode($key) . '=' . $item;
    
        $curl = new CdCurl();
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
    
    private static function qqtUpload(AdminPost $model)
    {
        if (empty($model->content)) return false;
    
        $url = 'https://open.t.qq.com/api/t/add_pic_url';
    
        $sinatShortUrl = self::sinatShortUrl($model->getUrl());
        $urlLen = empty($sinatShortUrl) ? 0 : strlen($sinatShortUrl);
        $content = mb_substr($model->content, 0, 130 - $urlLen, app()->charset) . '...' . $sinatShortUrl . ' @cdcchen';
        $data = array(
            'oauth_consumer_key' => QQT_APP_KEY,
            'access_token' => app()->cache->get('qq_weibo_access_token'),
            'openid' => app()->cache->get('qq_weibo_user_id'),
            'clientip' => request()->getUserHostAddress(),
            'oauth_version' => '2.a',
            'scope' => 'all',
            'format' => 'json',
            'content' => $content,
            'syncflag' => 0,
            'pic_url' => $model->getBmiddlePic(),
        );
        foreach ($data as $key => $item)
            $args[] = urlencode($key) . '=' . $item;
    
        $curl = new CdCurl();
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
    
}