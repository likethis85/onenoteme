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
        
        echo ($result === false) ? 0 : $result;
        exit(0);
    }
    
    private static function update(AdminPost $model)
    {
        if (empty($model->content)) return false;
        
        $url = 'https://upload.api.weibo.com/2/statuses/update.json';
        $data = array(
            'source' => WEIBO_APP_KEY,
            'access_token' => app()->session['access_token'],
            'status' => $model->content,
        );
        
        $curl = new CdCurl();
        $curl->post($url, $data);
        if ($curl->errno() == 0) {
            $result = json_decode($curl->rawdata(), true);
            return $result['id'];
        }
        else
            return false;
    }
    
    private static function upload(AdminPost $model)
    {
        if (empty($model->content)) return false;
        
        $curl = new CdCurl();
        $curl->get($model->getBmiddlePic());
        if ($curl->errno() == 0)
            $picData = $curl->rawdata();
        else
            return false;
        
        $url = 'https://upload.api.weibo.com/2/statuses/upload.json';
        $data = array(
            'source' => WEIBO_APP_KEY,
            'access_token' => app()->session['access_token'],
            'status' => $model->content,
            'pic' => $picData,
        );
        
        $curl = new CdCurl();
        $curl->post($url, $data);
        if ($curl->errno() == 0) {
            $result = json_decode($curl->rawdata(), true);
            return $result['id'];
        }
        else
            return false;
    }
    
    public function actionTest()
    {
        $url = 'https://upload.api.weibo.com/2/statuses/upload.json';
        $url = 'https://api.weibo.com/2/statuses/update.json';
    
//         $curl = new CdCurl();
//         $curl->get('http://static.php.net/www.php.net/images/php.gif');
//         if ($curl->errno() == 0)
//             $picData = $curl->rawdata();
//         else
//             return false;
        
        $data = array(
            'source' => WEIBO_APP_KEY,
            'access_token' => app()->session['access_token'],
            'status' => urlencode('这是一条测试数据'),
//             'pic' => $picData,
        );
        
        $curl = new CdCurl();
        $curl->headers(array('content-type'=>'text/plain'));
        $curl->post($url, $data);
        if ($curl->errno() == 0) {
            $result = json_decode($curl->rawdata(), true);
            print_r($result);
            return $result['id'];
        }
        else
            return false;
    }
}