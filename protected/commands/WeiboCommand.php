<?php
class WeiboCommand extends CConsoleCommand
{
    const SLEEP_TIME = 60;
    
    public function actionCollect()
    {
        $username = '这个漫画很邪恶';
    }
    
    private function collectOnce($username)
    {
        $appKey = '456860706';
        $appSecert = '19168ffef668231aa22f74683d3d18e7';
        $url = 'https://api.weibo.com/2/statuses/user_timeline.json';
        $params = array(
            'source' => $appKey,
            'screen_name' => $username,
            'count' => 10,
            'trim_user' => 1,
            'page' => $page,
        );
        
        $fetch = new CdCurl();
        $fetch->ssl()->get($url, $params);
        $jsonData = $fetch->rawdata();
        
        $errno = $fetch->errno();
        if ($errno !== 0) return false;

        $rows = json_decode($jsonData, true);
        foreach ((array)$rows['statuses'] as $row) {
            if (array_key_exists('retweeted_status', $row))
                $row = $row['retweeted_status'];
        
            $temp['id'] = $row['id'];
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
            $data[] = $temp;
            
            $model = new PostTemp();
            $model->attributes = $data;
            if ($model->save()) {
                self::updateLastTimeAndPID($username, $temp['id']);
            }
            unset($temp);
        }
    }
    
    private static function updateLastTimeAndPID($username, $pid)
    {
        $columns = array(
            'last_time' => $_SERVER['REQUEST_TIME'],
            'last_pid' => (int)$pid,
        );
        $result = app()->getDb()->createCommand()
            ->update(TABLE_NAME_WEIBO_ACCOUNT, $columns, 'display_name = :name', array(':name' => $username));
        
    }
    
    public function actionPost()
    {
        
    }
}