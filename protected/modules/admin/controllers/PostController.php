<?php
class PostController extends AdminController
{
    public function actionWeibo()
    {
        $pageSize = 20;
        $criteria = new CDbCriteria();
        
        $count = PostTemp::model()->count($criteria);
        $pages = new CPagination($count);
        $pages->setPageSize($pageSize);
        $pages->applyLimit($criteria);
        $criteria->order = 't.id desc';
        
        $models = PostTemp::model()->findAll($criteria);
        $data = array(
            'pages' => $pages,
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
                $content = trim($_POST['weibotext']);
                $content = empty($content) ? $temp->content : $content;
                $post = new Post();
                $post->content = $content;
                $post->channel_id = $channel_id;
                $post->up_score = mt_rand(100, 300);
                $post->down_score = mt_rand(10, 40);
                if ($channel_id == CHANNEL_LENGTU || $channel_id == CHANNEL_GIRL) {
                    $post->thumbnail = $temp->thumbnail_pic;
                    $post->pic = $temp->bmiddle_pic;
                    $post->big_pic = $temp->original_pic;
                }
                $result = $post->save();
                if ($result)
                    $temp->delete();
                $data = (int)$result;
            }
            catch (Exception $e) {
                $data = 0;
            }
        }
        CDBase::jsonp($callback, $data);
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
