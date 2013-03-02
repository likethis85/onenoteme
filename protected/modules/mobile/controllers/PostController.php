<?php
class PostController extends MobileController
{
    public function filters()
    {
        return array(
            array(
                'COutputCache + show, detail',
                'duration' => param('mobile_post_show_cache_expire'),
                'varyByParam' => array('id'),
            ),
        );
    }
    
    public function actionDetail($id)
    {
        $this->redirect(aurl('post/show', array('id'=>$id)));
    }
    
    public function actionShow($id)
    {
        $id = (int)$id;
        $post = MobilePost::model()->findByPk($id);
        if ($post === null)
            throw new CHttpException(403, '内容不存在');
        
        $comments = MobileComment::model()->fetchListByPostID($id);
        $comment = new MobileCommentForm();
        $comment->post_id = $id;

        $this->pageTitle = trim(strip_tags($post->title)) . ', ' . $post->getTagText(',') . ' - 挖段子';
        $pageKeyword = '精品笑话，内涵段子,内涵图,邪恶漫画,黄色笑话,幽默笑话,成人笑话,夫妻笑话,笑话集锦,荤段子,黄段子,大学校花,美女写真';
        if ($post->tags)
            $pageKeyword = $post->getTagText(',') . ',' . $pageKeyword;
        $this->setKeywords($pageKeyword);
        $this->setDescription($post->content);
        
        cs()->registerMetaTag('all', 'robots');
        $this->render('show', array(
            'post' => $post,
            'comments' => $comments,
            'comment' => $comment,
        ));
    }
    
    public function actionViews($callback)
    {
        $id = (int)$_POST['id'];
        $counters = array('view_nums' => 1);
        $result = Post::model()->updateCounters($counters, 'id = :postid', array(':postid' => $id));
        CDBase::jsonp($callback, $result);
    }

    public function actionScore($callback)
    {
        $pid = (int)$_POST['pid'];
        if ($pid <= 0) throw new CHttpException(500);
    
        $column = ((int)$_POST['score'] > 0) ? 'up_score' : 'down_score';
        $counters = array($column => 1);
        $result = Post::model()->updateCounters($counters, 'id = :pid', array(':pid'=>$pid));
        $data = array('errno' => (int)!$result);
        CDBase::jsonp($callback, $data);
    }
}