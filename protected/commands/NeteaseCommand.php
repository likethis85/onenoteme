<?php
class NeteaseCommand extends CConsoleCommand
{
    public function actionSend()
    {
        $tokens = app()->cache->get('netease_mm_access_tokens');
        if ($tokens === false) {
            echo "access tokens is empty.\n";
            exit(0);
        }
        else {
            $tokens = unserialize($tokens);
        }
        
        $duanziLastID = app()->cache->get('duanzi_last_id');
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(array('channel_id'=>CHANNEL_DUANZI));
        $criteria->addCondition('id > ' . (int)$duanziLastID);
        $criteria->order = 't.id asc';
        $models[] = Post::model()->find($criteria);
        
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(array('channel_id'=>CHANNEL_LENGTU, 'weibo_id' => ''));
        $criteria->order = 't.id asc';
        $models[] = Post::model()->find($criteria);
        
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(array('channel_id'=>CHANNEL_GIRL, 'weibo_id' => ''));
        $criteria->order = 't.id asc';
        $models[] = Post::model()->find($criteria);
        
        foreach ($models as $model) {
            if ($model === null) continue;
            self::sendMore($model, $tokens);
        }
    }
    
    private static function sendMore(Post $model, $tokens)
    {
        $text = 'Post ID: ' . $model->id . ' - ';
        
        foreach ((array)$tokens as $token) {
            if ($token === false) {
                $text .= "access token expired.\n";
                echo $text;
                continue;
            }
            else {
                $result = self::neteaseUpdate($model, $token);
                $text .= $result ? 'send success: ' . $result : 'send error';
                $text .= "\n";
                echo $text;
            }
        }
    }
    
    private static function neteaseUpdate(Post $model, $token)
    {
        if ($model->getBmiddlePic()) {
            $imageUrl = self::neteaseUploadImage($model->getBmiddlePic());
            if (empty($imageUrl)) return false;
        }
    
        $url = 'https://api.t.163.com/statuses/update.json';
    
        $sinatShortUrl = self::sinatShortUrl($model->getUrl());
        $urlLen = empty($sinatShortUrl) ? 0 : strlen($sinatShortUrl);
        $content = mb_substr($model->content, 0, 150 - $urlLen, app()->charset) . '...' . $sinatShortUrl . ' @挖段子冷笑话';
        $data = array(
            'oauth_consumer_key' => NETEASE_APP_KEY,
            'access_token' => $token,
            'status' => $content . $imageUrl,
        );
        foreach ($data as $key => $item)
            $args[] = urlencode($key) . '=' . $item;
    
        $curl = new CdCurl();
        $curl->ssl()->post($url, join('&', $args));
        if ($curl->errno() == 0) {
            $data = json_decode($curl->rawdata(), true);
            return empty($data['id']) ? false : $data['id'];
        }
        else
            return false;
    }
    
    private static function neteaseUploadImage($imageUrl)
    {
        if (empty($imageUrl)) return false;
    
        $curl = new CdCurl();
        $curl->get($imageUrl);
        if ($curl->errno() == 0) {
            $picData = $curl->rawdata();
            $picfile = app()->getRuntimePath() . DS . uniqid() . '.jpg';
            $result = file_put_contents($picfile, $picData);
            if ($result === false)
                throw new CException('生成临时文件出错', 0);
        }
        else
            return false;
    
        $url = 'https://api.t.163.com/statuses/upload.json';
    
        $data = array(
                'oauth_consumer_key' => NETEASE_APP_KEY,
                'access_token' => app()->cache->get('netease_weibo_access_token'),
                'pic' => '@' . $picfile,
        );
    
        $curl = new CdCurl();
        $curl->ssl()->post($url, $data);
        @unlink($picfile);
        if ($curl->errno() == 0) {
            $result = json_decode($curl->rawdata(), true);
            return $result['upload_image_url'] ? $result['upload_image_url'] : false;
        }
        else
            return false;
    }
}