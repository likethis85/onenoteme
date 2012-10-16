<?php
class PostController extends Controller
{
    public function filters()
    {
        return array(
            'ajaxOnly  + score, views',
            'postOnly + score, views',
            array(
                'COutputCache + show',
                'duration' => 600,
                'varyByParam' => array('id'),
            ),
            array(
                'COutputCache + originalPic',
                'duration' => 24*60*60*7,
                'varyByParam' => array('id'),
            ),
        );
    }
    
    public function actionScore()
    {
        $pid = (int)$_POST['pid'];
        if ($pid <= 0) throw new CHttpException(500);
        
        $column = ((int)$_POST['score'] > 0) ? 'up_score' : 'down_score';
        $counters = array($column => 1);
        $result = Post::model()->updateCounters($counters, 'id = :id', array(':id'=>$pid));
        echo (int)$result;
        exit(0);
    }
    
    public function actionShow($id)
    {
        $duration = 60*60;
        $id = (int)$id;
        if ($id <= 0)
            throw new CHttpException(500, '非法请求');
        
        if (request()->getIsAjaxRequest()) {
            $commentsData = self::fetchComments($id);
            $html = $this->renderPartial('/comment/list', $commentsData, true);
            echo $html;
            exit(0);
        }
        
        if (user()->getFlash('allowUserView'))
            $post = Post::model()->findByPk($id);
        else {
            $criteria = new CDbCriteria();
            $criteria->addColumnCondition(array('state'=>POST_STATE_ENABLED));
            $post = Post::model()->cache($duration)->findByPk($id, $criteria);
        }
        if (null === $post)
            throw new CHttpException(403, '该段子不存在或未被审核');
        
        // 获取后几个Post
        $nextPosts = self::fetchNextPosts($post, 7);
        
        // 获取评论
        $limit = param('commentCountOfPage');
        $conditions = array('and', 'post_id = :pid', 'state = :state');
        $params = array(':pid' => $id, ':state' => COMMENT_STATE_ENABLED);
        
        $commentsData = self::fetchComments($id);
        
        $this->pageTitle = trim(strip_tags($post->title)) . ', ' . $post->getTagText(',') . ' - 挖段子';
        $pageKeyword = '美女写真,美女图片,美女写真,性感美女,清纯少女,大学校花,淘女郎,微女郎';
        if ($post->tags)
            $pageKeyword = $post->getTagText(',') . ',' . $pageKeyword;
        $this->setKeywords($pageKeyword);
        $this->setDescription($post->content);
        
        $this->channel = (int)$post->channel_id;
        $this->render('show', array(
            'post' => $post,
            'nextPosts' => $nextPosts,
            'prevUrl' => self::prevPostUrl($post),
            'nextUrl' => self::nextPostUrl($post),
            'returnUrl' => self::returnUrl($post->channel_id),
            'comments' => $commentsData['comments'],
            'pages' => $commentsData['pages'],
        ));
    }
    
    public function actionOriginalPic($id)
    {
        $id = (int)$id;
        if ($id <= 0)
            throw new CHttpException(503, '非法请求');
        
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(array('state'=>POST_STATE_ENABLED));
        $model = Post::model()->findByPk($id, $criteria);
            
        if (null === $model || empty($model->originalPic))
            throw new CHttpException(403, '该段子不存在或未被审核');

        $this->pageTitle = '原始图片' . ' - ' . trim(strip_tags($model->title)) . '  ' . $model->tagText;
        $pageKeyword = '美女写真,美女图片,美女写真,性感美女,清纯少女,大学校花,淘女郎,微女郎';
        if ($model->tags)
            $pageKeyword = $model->getTagText(',') . ',' . $pageKeyword;
        $this->setKeywords($pageKeyword);
        $this->setDescription($model->content);
        $this->layout = 'blank';
        $this->render('/post/original_pic', array('model'=>$model));
    }
    
    public function actionViews($callback)
    {
        $id = (int)$_POST['id'];
        $counters = array('view_nums' => 1);
        $result = Post::model()->updateCounters($counters, 'id = :postid', array(':postid' => $id));
        CDBase::jsonp($callback, $result);
    }
    
    private static function prevPostUrl(Post $post)
    {
        $duration = 60*60;
        $createTime = (int)$post->create_time;
        $channelID = (int)$post->channel_id;
        $id = app()->getDb()->cache($duration)->createCommand()
            ->select('id')
            ->from(TABLE_POST)
            ->where(array('and', 'create_time > :createtime', 'channel_id = :channelid', 'state = :enabled'),
                array(':createtime' => $createTime, ':enabled' => POST_STATE_ENABLED, ':channelid'=>$channelID))
            ->order('create_time asc, id asc')
            ->limit(1)
            ->queryScalar();
        
        $url = ($id > 0) ? aurl('post/show', array('id' => $id)) : '';
        return $url;
    }
    
    private static function nextPostUrl(Post $post)
    {
        $duration = 60*60;
        $createTime = (int)$post->create_time;
        $channelID = (int)$post->channel_id;
        $id = app()->getDb()->cache($duration)->createCommand()
            ->select('id')
            ->from(TABLE_POST)
            ->where(array('and', 'create_time < :createtime', 'channel_id = :channelid', 'state = :enabled'),
                array(':createtime' => $createTime, ':enabled' => POST_STATE_ENABLED, ':channelid'=>$channelID))
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
        $duration = 60*60;
        
        $createTime = (int)$post->create_time;
        $channelID = (int)$post->channel_id;
        
        if ($channelID == CHANNEL_DUANZI)
            return array();
        
        $count = (int)$count;
        $column = (int)$column;
        
        if ($column < 1 || $count < 1)
            throw new CException('$column和$count 不能小于1');
        
        $count = ceil($count / $column) * $column;
        
        $criteria = new CDbCriteria();
        $criteria->addCondition("create_time < $createTime");
        $criteria->addColumnCondition(array('channel_id'=>$channelID, 'state'=>POST_STATE_ENABLED));
        $criteria->order = 'create_time desc, id desc';
        $criteria->limit = $count;
        
        $models = Post::model()->cache($duration)->findAll($criteria);
        
        return $models;
    }
    
    private static function fetchComments($pid)
    {
        $duration = 60*60;
        
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
        
        if ($pages->getCurrentPage() < $_GET[$pages->pageVar]-1)
            return array();
        
        $models = Comment::model()->cache($duration)->findAll($criteria);
        return array(
            'comments' => $models,
            'pages' => $pages,
        );
    }
}




