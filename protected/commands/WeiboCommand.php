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
    
    public function actionPost()
    {
        
    }
}