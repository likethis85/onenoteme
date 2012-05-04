<?php
class WeiboCommand extends CConsoleCommand
{
    const ACCOUNT_SLEEP_TIME = 30;
    const WEIBO_ROWS_COUNT = 50;
    
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
        $appKey = '456860706';
        $appSecert = '19168ffef668231aa22f74683d3d18e7';
        $url = 'https://api.weibo.com/2/statuses/user_timeline.json';
        $params = array(
            'source' => $appKey,
            'screen_name' => $account,
            'since_id' => $since_id,
            'count' => self::WEIBO_ROWS_COUNT,
            'trim_user' => 1,
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
        $temp['id'] = $row['idstr'];
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
        
        $model = new PostTemp();
        $model->attributes = $temp;
        $result = $model->save();
        
        $text = date('Y-m-d H:i:s', time()) . ' - ID: ' . $row['idstr'] . ' - ';
        if ($model->hasErrors())
            $text .= join('; ', $model->getErrors());
        else
            $text .= 'Save Success';
        
        $text .= "\n";
        echo $text;
        
        return $result;
    }
    
    private static function fetchWeiboAccounts()
    {
        $rows = app()->getDb()->createCommand()
            ->select('display_name, last_pid')
            ->from(TABLE_NAME_WEIBO_ACCOUNT)
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
            ->update(TABLE_NAME_WEIBO_ACCOUNT, $columns, 'display_name = :name', array(':name' => $account));
        
    }
    
    public function actionPost()
    {
        
    }
}