<?php
class PostController extends AdminController
{
    public function actionWeibo()
    {
        $pageSize = 20;
        $criteria = new CDbCriteria();
        $criteria->limit = $pageSize;
        $criteria->order = 't.id asc';
        
        $models = PostTemp::model()->findAll($criteria);
        $data = array(
            'models' => $models,
        );
        $this->render('weibo', $data);
    }
    
    public function actionWeiboVerify($id, $channel_id, $callback)
    {
        $id = (int)$id;
        $channel_id = (int)$channel_id;
        $temp = PostTemp::model()->findByPk($id);
        if ($temp === null)
            $data = 1;
        else {
            try {
                $username = $temp['username'];
                $userid = app()->getDb()->createCommand()
                    ->select('id')
                    ->from(TABLE_USER)
                    ->where('screen_name = :username', array(':username' => $username))
                    ->queryScalar();
                
                $content = trim($_POST['weibotext']);
                $content = empty($content) ? $temp->content : $content;
                $post = new Post();
                $post->content = $content;
                $post->channel_id = $channel_id;
                $post->up_score = mt_rand(100, 300);
                $post->down_score = mt_rand(10, 40);
                if ($userid > 0) {
                    $post->user_id = (int)$userid;
                    $post->user_name = $username;
                }
                if ($channel_id == CHANNEL_LENGTU || $channel_id == CHANNEL_GIRL) {
                    $post->thumbnail_pic = $temp->thumbnail_pic;
                    $post->bmiddle_pic = $temp->bmiddle_pic;
                    $post->original_pic = $temp->original_pic;
                }
                $result = $post->save();
                if ($result) {
                    $temp->delete();
                    self::saveWeiboComments($post->id, $temp->weibo_id);
                }
                $data = (int)$result;
            }
            catch (Exception $e) {
                $data = 0;
                echo $e->getMessage();
            }
        }
        CDBase::jsonp($callback, $data);
    }
    
    private static function saveWeiboComments($pid, $wid)
    {
        $data = self::fetchWeiboComments($wid);
        $comments = $data['comments'];
        foreach ($comments as $row) {
            $text = self::filterComment($row['text']);
            if (empty($text)) continue;
            
            self::saveCommentRow($pid, $text);
            echo '<li>' . $text . '</li>';
        }
    }
    
    private static function filterComment($text)
    {
        $pattern = '/\[.+?\]/is';
        $text = preg_replace($pattern, '', $text);
        $pos = mb_strpos($text, '//', 0, app()->charset);
        if ($pos === 0)
            return false;
        elseif ($pos > 0) {
            $text = mb_substr($text, 0, $pos, app()->charset);
        }
        
        $pos = mb_strpos($text, '@', 0, app()->charset);
        if ($pos === 0)
            return false;
        elseif ($pos > 0) {
            $text = mb_substr($text, 0, $pos, app()->charset);
        }
        
        return trim($text);
    }
    
    private static function saveCommentRow($pid, $text)
    {
        $pid = (int)$pid;
        if (empty($pid) || empty($text)) return false;
        
        try {
            $model = new Comment();
            $model->content = $text;
            $model->post_id = $pid;
            $model->up_score = mt_rand(20, 70);
            $model->down_score = mt_rand(0, 10);
            $model->state = COMMENT_STATE_ENABLED;
            return $model->save();
        }
        catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }
    
    private static function fetchWeiboComments($wid)
    {
        $url = 'https://api.weibo.com/2/comments/show.json';
        $data = array(
            'source' => WEIBO_APP_KEY,
            'access_token' => app()->session['access_token'],
            'id' => $wid
        );
        
        $curl = new CdCurl();
        $curl->get($url, $data);
        if ($curl->errno() == 0) {
            $comments = json_decode($curl->rawdata(), true);
            return $comments;
        }
        else {
            echo $curl->error();
            return false;
        }
    }
    
    public function actionWeiboDelete($id, $callback)
    {
        $id = (int)$id;
        $result = PostTemp::model()->findByPk($id)->delete();
        CDBase::jsonp($callback, (int)$result);
    }
    
    public function actionVerify()
    {
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(array('state'=>Post::STATE_DISABLED));
        $data = self::fetchPostList($criteria, true, true);

        $this->render('verify', $data);
    }
    
    public function actionToday()
    {
        $date = getdate();
        $timestamp = mktime(0, 0, 0, $date['mon'], $date['mday'], $date['year']);
        $criteria = new CDbCriteria();
        $criteria->addCondition('create_time > :timestamp');
        $criteria->params = array(':timestamp' => $timestamp);
        $data = self::fetchPostList($criteria, true, true);
        
        $this->render('list', $data);
    }
    
    public function actionList()
    {
        $data = self::fetchPostList(null, true, true);
        
        $this->render('list', $data);
    }
    
    public function actionSearch()
    {
        
    }
    
    private static function fetchPostList(CDbCriteria $criteria = null, $pages = true, $sort = false)
    {
        $pageSize = 30;
        $criteria = ($criteria === null) ? new CDbCriteria() : $criteria;
        
        if ($pages) {
            $count = Post::model()->count($criteria);
            $pages = new CPagination($count);
            $pages->setPageSize($pageSize);
            $pages->applyLimit($criteria);
        }
        else
            $criteria->limit = $pageSize;
        
        if ($sort) {
            $sort = new CSort('Post');
            $sort->defaultOrder = 't.id desc';
            $sort->applyOrder($criteria);
        }
        else
            $criteria->order = 't.id desc';
        
        $models = Post::model()->findAll($criteria);
        $data = array(
            'sort' => $sort,
            'pages' => $pages,
            'models' => $models,
        );
        return $data;
    }
}
