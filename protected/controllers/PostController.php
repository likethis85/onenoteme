<?php
class PostController extends Controller
{
    public function filters()
    {
        return array(
//             'switchMobile + show',
            'ajaxOnly + score, like, unlike, views',
            'postOnly + score, like, unlike, views',
            array(
                'COutputCache + show',
                'duration' => 600,
                'varyByParam' => array('id'),
                'varyByExpression' => array($this, 'showPageCacheFilterCallback'),
            ),
        );
    }
    
    public function showPageCacheFilterCallback()
    {
        return request()->getServerName() . (int)user()->getIsGuest();
    }
    
    public function actionPublish()
    {
        $this->channel = 'publish';
        
        if (user()->getIsGuest()) {
	        $url = CDBaseUrl::loginUrl(abu(request()->getUrl()));
	        request()->redirect($url);
	        exit(0);
	    }
	    
        $model = new PostForm();
        if (request()->getIsPostRequest() && isset($_POST['PostForm'])) {
            $model->attributes = $_POST['PostForm'];
            if ($model->validate() && $model->save()) {
                user()->setFlash('allow_author_view', '1');
                user()->setFlash('publish_post_success', '您的笑话已经成功提交！如果您是会员，审核通过后我们会发邮箱通知您。');
                $this->redirect(request()->getUrl());
            }
        }
        
        $this->setSitePageTitle('分享我的笑话');
        $this->render('publish', array('model'=>$model));
    }
    
    public function actionScore()
    {
        $pid = (int)$_POST['pid'];
        if ($pid <= 0) throw new CHttpException(500);
        
        $column = ((int)$_POST['score'] > 0) ? 'up_score' : 'down_score';
        $counters = array($column => 1);
        $result = Post::model()->updateCounters($counters, 'id = :pid', array(':pid'=>$pid));
        $data = array('errno' => (int)!$result);
        echo CJSON::encode($data);
        exit(0);
    }
    
    public function actionShow($id)
    {
        $duration = 60*60;
        $id = (int)$id;
        if ($id <= 0)
            throw new CHttpException(500, '非法请求');
        
        if (user()->getIsAdmin() || user()->hasFlash('allow_author_view'))
            $post = Post::model()->findByPk($id);
        else {
            $criteria = new CDbCriteria();
            $criteria->addColumnCondition(array('state'=>POST_STATE_ENABLED));
            if (!user()->getIsGuest())
                $criteria->addColumnCondition(array('user_id'=>(int)user()->id), 'AND', 'OR');
            
            $post = Post::model()->cache($duration)->findByPk($id, $criteria);
        }
        if (null === $post)
            throw new CHttpException(403, '该段子不存在或未被审核');
        
        // 获取后几个Post
        $nextPosts = array();
        if ($post->getIsImageType())
            $nextPosts = self::fetchNextPosts($post, 9);
        
        // 获取评论
        $commentsData = self::fetchComments($id);
        
        $tagsText = $post->getTagText(',');
        if ($tagsText)
            $tagsText = ', ' . $tagsText;
        $this->pageTitle = trim(strip_tags($post->title)) . $tagsText . ' - ' . app()->name;
        $pageKeyword = '精品笑话，内涵段子,内涵图,邪恶漫画,黄色笑话,幽默笑话,成人笑话,夫妻笑话,笑话集锦,荤段子,黄段子,大学校花,美女写真';
        if ($post->tags)
            $pageKeyword = $post->getTagText(',') . ',' . $pageKeyword;
        $this->setKeywords($pageKeyword);
        $this->setDescription($post->getPlainSummary());
        
        $mobileUrl = aurl('mobile/post/show', array('id'=>$id));
        cs()->registerMetaTag('format=html5;url=' . $mobileUrl, null, 'mobile-agent');
        
        $shareData = sprintf("{'text':'%s'}", $post->content);
        if ($post->getMiddlePic())
            $shareData = sprintf("{'text':'%s', 'pic':'%s'}", $post->content, $post->getMiddlePic());
        
        $this->channel = $post->channel_id . $post->media_type;
        $this->showAdvert = ($post->create_time > SITE_ADD_CONTENT_LEVEL_TIMESTAMP) && $post->getContentLevelAllow();
        $this->render('show', array(
            'post' => $post,
            'nextPosts' => $nextPosts,
            'prevUrl' => self::prevPostUrl($post),
            'nextUrl' => self::nextPostUrl($post),
            'returnUrl' => self::returnUrl($post->channel_id, $post->media_type),
            'comments' => $commentsData['comments'],
            'pages' => $commentsData['pages'],
            'shareData' => $shareData,
        ));
    }
    
    public function actionViews()
    {
        $id = (int)$_POST['id'];
        $counters = array('view_nums' => 1);
        $result = Post::model()->updateCounters($counters, 'id = :postid', array(':postid' => $id));
        $data = array('errno' => (int)!$result);
        echo CJSON::encode($data);
        exit(0);
    }
    
    public function actionLike()
    {
        $pid = (int)$_POST['pid'];
        $row = app()->getDb()->createCommand()
            ->from(TABLE_POST_FAVORITE)
            ->select('id')
            ->where(array('and', 'user_id = :userid', 'post_id = :postid'), array(':userid'=>$this->getUserID(), ':postid'=>$pid))
            ->queryScalar();
        
        if ($row !== false) {
            $data = array('errno' => CD_NO, 'id'=>$row);
            echo CJSON::encode($data);
            exit(0);
        }
        
        $columns = array(
            'user_id' => $this->getUserID(),
            'post_id' => $pid,
            'create_time' => $_SERVER['REQUEST_TIME'],
            'create_ip' => CDBase::getClientIp(),
        );
        try {
            $result = app()->getDb()->createCommand()
                ->insert(TABLE_POST_FAVORITE, $columns);
            
            if ($result > 0) {
                $counters = array('favorite_count' => 1);
                $result = Post::model()->updateCounters($counters, 'id = :postid', array(':postid' => $pid));
                $data = array('errno' => CD_NO);
            }
            else
                $data = array('errno' => CD_YES);
        }
        catch (Exception $e) {
            $data = array('errno' => CD_YES, 'error'=>$e->getMessage());
        }
        
        echo CJSON::encode($data);
        exit(0);
    }
    
    public function actionUnlike($id)
    {
        $id = (int)$id;
        $conditions = array('and', 'user_id = :userid', 'post_id = :postid');
        $params = array(':userid'=>$this->getUserID(), ':postid'=>$id);
        $result = app()->getDb()->createCommand()
            ->delete(TABLE_POST_FAVORITE, $conditions, $params);
        
        if ($result > 0) {
            $counters = array('favorite_count' => 1);
            $result = Post::model()->updateCounters($counters, 'id = :postid', array(':postid' => $id));
            $data = array('errno' => CD_NO);
        }
        
        $data = array(
            'errno' => $result ? CD_NO : CD_YES,
        );
        
        echo CJSON::encode($data);
        exit(0);
    }
    
    private static function prevPostUrl(Post $post)
    {
        $duration = 15;
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
        
        $url = ($id > 0) ? aurl('post/show', array('id' => $id, 'source' => 'prev')) : '';
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
        
        $url = ($id > 0) ? aurl('post/show', array('id' => $id, 'source' => 'next')) : '';
        return $url;
    }
    
    private static function returnUrl($channel_id, $mediatype)
    {
        $channelID = (int)$channel_id;
        if (!in_array($channelID, CDBase::channels()))
            return false;
        
        $mediatype = (int)$mediatype;
        switch ($mediatype) {
            case MEDIA_TYPE_TEXT:
                $url = aurl('channel/joke');
                break;
            case MEDIA_TYPE_IMAGE:
                $url = aurl('channel/lengtu');
                break;
            default:
                $url = CDBaseUrl::siteHomeUrl();
                break;
        }
        
        return $url;
    }
    
    private static function fetchNextPosts(Post $post, $count = 9, $column = 3)
    {
        $duration = 60*60;
        
        $createTime = (int)$post->create_time;
        $channelID = (int)$post->channel_id;
        
        $count = (int)$count;
        $column = (int)$column;
        
        if ($column < 1 || $count < 1)
            throw new CException('$column和$count 不能小于1');
        
        $count = ceil($count / $column) * $column;
        
        $criteria = new CDbCriteria();
        $criteria->addCondition("create_time < $createTime");
        $criteria->addColumnCondition(array('channel_id'=>$channelID, 'media_type'=>MEDIA_TYPE_IMAGE, 'state'=>POST_STATE_ENABLED));
        $criteria->order = 'create_time desc, id desc';
        $criteria->limit = $count;
        
        $models = Post::model()->cache($duration)->findAll($criteria);
        
        return $models;
    }
    
    private static function fetchComments($pid)
    {
        $duration = 60 * 10;
        
        $pid = (int)$pid;
        if ($pid <=0) return array();
        
        $criteria = new CDbCriteria();
        $columns = array(
            'post_id' => $pid,
            'state' => COMMENT_STATE_ENABLED,
        );
        $criteria->addColumnCondition($columns);
        $criteria->limit = param('comment_count_page');
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




