<?php
class WeiboController extends AdminController
{
    public function actionPosts()
    {
        $pageSize = 20;
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(array('weibo_id' => ''));
        $criteria->limit = $pageSize;
        $criteria->order = 't.id asc';
        
        $models = AdminPost::model()->findAll($criteria);
        $data = array(
            'models' => $models,
        );
        $this->render('posts', $data);
    }
    
    public function actionSkip($id)
    {
        $result = AdminPost::model()->updateByPk($id, array('weibo_id'=>'0'));
        echo (int)$result;
        exit(0);
    }
    
    public function actionCreate($id)
    {
        $id = (int)$id;
        $model = AdminPost::model()->findByPk($id);
        if ($model === null) {
            echo 0;
            exit(0);
        }
        
        $picUrl = $model->getBmiddlePic();
        if (empty($picUrl)) {
            $result = self::update($model);
        }
        else {
            $result = self::upload($model);
        }
        
        if ($result !== false) {
            $model->weibo_id = $result;
            $model->save(true, array('weibo_id'));
        }
        
        echo ($result === false) ? 0 : $result;
        exit(0);
    }
    
    private static function update(AdminPost $model)
    {
        if (empty($model->content)) return false;
        
        $url = 'https://upload.api.weibo.com/2/statuses/update.json';
        
        
        $shortUrl = self::shortUrl($model->getUrl());
        $urlLen = empty($shortUrl) ? 0 : strlen($shortUrl);
        $content = mb_strimwidth($model->content, 0, 135 - $urlLen, '...', app()->charset) . $shortUrl;
        $data = array(
            'source' => WEIBO_APP_KEY,
            'access_token' => app()->session['access_token'],
            'status' => $content,
        );
        foreach ($data as $key => $item)
            $args[] = urlencode($key) . '=' . $item;
        
        $curl = new CdCurl();
        $curl->post($url, join('&', $args));
        if ($curl->errno() == 0) {
            $result = json_decode($curl->rawdata(), true);
            return $result['idstr'] ? $result['idstr'] : false;
        }
        else
            return false;
    }
    
    private static function upload(AdminPost $model)
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
        
        $shortUrl = self::shortUrl($model->getUrl());
        $urlLen = empty($shortUrl) ? 0 : strlen($shortUrl);
        $content = mb_strimwidth($model->content, 0, 135 - $urlLen, '...', app()->charset) . $shortUrl;
        $data = array(
            'source' => WEIBO_APP_KEY,
            'access_token' => app()->session['access_token'],
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
    
    private static function shortUrl($longUrl)
    {
        $url = 'https://api.weibo.com/2/short_url/shorten.json';
        $data = array(
            'source' => WEIBO_APP_KEY,
            'url_long' => $longUrl,
        );
        
        $curl = new CdCurl();
        $curl->get($url, $data);
        if ($curl->errno() == 0) {
            $urls = json_decode($curl->rawdata(), true);
            $short = $urls[0];
            var_dump($short);
            return ($short['result']) ? $short['url_short'] : false;
        }
        else
            return false;
    }
}

