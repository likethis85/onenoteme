<?php
class PostController extends MobileController
{
    public function filters()
    {
        return array(
            'ajaxOnly + views, score',
            'postOnly + views, score',
            array(
                'COutputCache + show',
                'duration' => param('mobile_post_show_cache_expire'),
                'varyByParam' => array('id', 'source'),
                'varyByExpression' => array(request(), 'getServerName'),
            ),
        );
    }
    
    public function actionShow($id, $source = '')
    {
        $id = (int)$id;
        $post = MobilePost::model()->findByPk($id);
        if ($post === null)
            throw new CHttpException(403, '内容不存在');
        
        $comments = MobileComment::model()->fetchListByPostID($id);
        $comment = new MobileCommentForm();
        $comment->post_id = $id;

        $tagsText = $post->getTagText(',');
        if ($tagsText)
            $tagsText = ', ' . $tagsText;
        $this->pageTitle = trim(strip_tags($post->title)) . $tagsText . ' - ' . app()->name;
        $pageKeyword = '精品笑话，内涵段子,内涵图,邪恶漫画,黄色笑话,幽默笑话,成人笑话,夫妻笑话,笑话集锦,荤段子,黄段子,大学校花,美女写真';
        if ($post->tags)
            $pageKeyword = $post->getTagText(',') . ',' . $pageKeyword;
        $this->setKeywords($pageKeyword);
        $this->setDescription($post->getPlainSummary());
        
        $this->channel = $post->channel_id . $post->media_type;
        cs()->registerMetaTag('all', 'robots');
        $this->render('show', array(
            'post' => $post,
            'comments' => $comments,
            'comment' => $comment,
            'prevUrl' => self::prevPostUrl($post),
            'nextUrl' => self::nextPostUrl($post),
        ));
    }
    
    public function actionViews()
    {
        $id = (int)$_POST['id'];
        $counters = array('view_nums' => 1);
        $result = Post::model()->updateCounters($counters, 'id = :postid', array(':postid' => $id));
        echo CJSON::encode($result);
        exit(0);
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
    
    private static function prevPostUrl(Post $post)
    {
        $duration = 10;
        $createTime = (int)$post->create_time;
        $channelID = (int)$post->channel_id;
        $conditions = array('and', 'create_time > :createtime', 'channel_id = :channelid', 'state = :enabled');
        $params = array(':createtime' => $createTime, ':enabled' => POST_STATE_ENABLED, ':channelid'=>$channelID);
        $id = app()->getDb()->cache($duration)->createCommand()
            ->select('id')
            ->from(TABLE_POST)
            ->where($conditions, $params)
            ->andWhere(array('in', 'media_type', array(MEDIA_TYPE_TEXT, MEDIA_TYPE_IMAGE)))
            ->order('create_time asc, id asc')
            ->limit(1)
            ->queryScalar();
    
        return ($id > 0) ? aurl('mobile/post/show', array('id' => $id, 'source'=>'prev')) : '';
    }
    
    private static function nextPostUrl(Post $post)
    {
        $duration = 60*60;
        $createTime = (int)$post->create_time;
        $channelID = (int)$post->channel_id;
        $conditions = array('and', 'create_time < :createtime', 'channel_id = :channelid', 'state = :enabled');
        $params = array(':createtime' => $createTime, ':enabled' => POST_STATE_ENABLED, ':channelid'=>$channelID);
        $id = app()->getDb()->cache($duration)->createCommand()
            ->select('id')
            ->from(TABLE_POST)
            ->where($conditions, $params)
            ->andWhere(array('in', 'media_type', array(MEDIA_TYPE_TEXT, MEDIA_TYPE_IMAGE)))
            ->order('create_time desc, id desc')
            ->limit(1)
            ->queryScalar();
    
        return ($id > 0) ? aurl('mobile/post/show', array('id' => $id, 'source'=>'next')) : '';
    }
}


