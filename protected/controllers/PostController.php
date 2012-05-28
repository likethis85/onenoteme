<?php
class PostController extends Controller
{
    public function filters()
    {
        return array(
            'ajaxOnly  + score',
            'postOnly + score',
        );
    }
    
    public function actionScore()
    {
        $id = (int)$_POST['id'];
        $column = ((int)$_POST['score'] > 0) ? 'up_score' : 'down_score';
        $counters = array($column => 1);
        $result = Post::model()->updateCounters($counters, 'id = :id', array(':id'=>$id));
        echo (int)$result;
        exit(0);
    }
    
    public function actionShow($id)
    {
        $id = (int)$id;
        if ($id <= 0)
            throw new CHttpException(500, '非法请求');
        
        $cmd = app()->getDb()->createCommand();
        if (user()->getFlash('allowUserView'))
            $post = Post::model()->findByPk($id);
        else {
            $criteria = new CDbCriteria();
            $criteria->addColumnCondition(array('state'=>POST_STATE_ENABLED));
            $post = Post::model()->findByPk($id, $criteria);
        }
        if (null === $post)
            throw new CHttpException(404, '该段子不存在或未被审核');
        
        // 获取后几个Post
        $nextPosts = self::fetchNextPosts($post, 7);
        
        // 获取评论
        $limit = param('commentCountOfPage');
        $conditions = array('and', 'post_id = :pid', 'state = :state');
        $params = array(':pid' => $id, ':state' => COMMENT_STATE_ENABLED);
        
        $commentsData = self::fetchComments($id);
        
        $this->pageTitle = trim(strip_tags($post->title)) . ' - 挖段子';
        $this->setKeywords($this->pageTitle);
        $this->setDescription($post->content);
        
        $this->channel = (int)$post->channel_id;
        $this->render('show', array(
            'post' => $post,
            'nextPosts' => $nextPosts,
            'prevUrl' => self::prevPostUrl($post),
            'nextUrl' => self::nextPostUrl($post),
            'returnUrl' => self::returnUrl($post->channel_id),
            'comments' => $commentsData['models'],
            'pages' => $commentsData['pages'],
        ));
    }
    
    private static function prevPostUrl(Post $post)
    {
        $createTime = (int)$post->create_time;
        $id = app()->getDb()->createCommand()
            ->select('id')
            ->from(TABLE_POST)
            ->where("create_time > :createtime and state = :enabled and thumbnail_pic != ''", array(':createtime' => $createTime, ':enabled' => POST_STATE_ENABLED))
            ->order('create_time asc, id asc')
            ->limit(1)
            ->queryScalar();
        
        $url = ($id > 0) ? aurl('post/show', array('id' => $id)) : '';
        return $url;
    }
    
    private static function nextPostUrl(Post $post)
    {
        $createTime = (int)$post->create_time;
        $id = app()->getDb()->createCommand()
            ->select('id')
            ->from(TABLE_POST)
            ->where("create_time < :createtime and state = :enabled and thumbnail_pic != ''", array(':createtime' => $createTime, ':enabled' => POST_STATE_ENABLED))
            ->order('create_time desc, id desc')
            ->limit(1)
            ->queryScalar();
        
        $url = ($id > 0) ? aurl('post/show', array('id' => $id)) : '';
        return $url;
    }
    
    private static function returnUrl($channel_id)
    {
        $channelID = (int)$channel_id;
        if (!in_array($channelID, Post::channels()))
            return false;
        
        switch ($channelID) {
            case CHANNEL_DUANZI:
                $url = aurl('channel/duanzi');
                break;
            case CHANNEL_LENGTU:
                $url = aurl('channel/lengtu');
                break;
            case CHANNEL_GIRL:
                $url = aurl('channel/girl');
                break;
            default:
                $url = app()->homeUrl;
                break;
        }
        
        return $url;
    }
    
    private static function fetchNextPosts(Post $post, $count = 6, $column = 3)
    {
        $createTime = (int)$post->create_time;
        $count = (int)$count;
        $column = (int)$column;
        
        if ($column < 1 || $count < 1)
            throw new CException('$column和$count 不能小于1');
        
        $count = ceil($count / $column) * $column;
        
        $criteria = new CDbCriteria();
        $criteria->addCondition("create_time < $createTime");
        $criteria->addColumnCondition(array('state'=>POST_STATE_ENABLED));
        $criteria->addCondition("thumbnail_pic != ''");
        $criteria->order = 'create_time desc, id desc';
        $criteria->limit = $count;
        
        $models = Post::model()->findAll($criteria);
        
        return $models;
    }
    
    private static function fetchComments($pid)
    {
        $pid = (int)$pid;
        if ($pid <=0) return array();
        
        $criteria = new CDbCriteria();
        $columns = array(
            'post_id' => $pid,
            'state' => COMMENT_STATE_ENABLED,
        );
        $criteria->addColumnCondition($columns);
        $criteria->limit = param('commentCountOfPage');
        $criteria->order = 'create_time asc';
        
        $pages = new CPagination(Comment::model()->count($criteria));
        $pages->setPageSize($criteria->limit);
        $pages->applyLimit($criteria);
        
        $models = Comment::model()->findAll($criteria);
        
        return array(
            'models' => $models,
            'pages' => $pages,
        );
    }
}




