<?php
class PostController extends MobileController
{
    public function fil2ters()
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
        $this->forward('post/show');
    }
    
    public function actionShow($id)
    {
        $id = (int)$id;
        $post = MobilePost::model()->findByPk($id);
        if ($post === null)
            throw new CHttpException(403, '内容不存在');
        
        $comments = MobileComment::model()->fetchList($id);
        $comment = new MobileCommentForm();
        $comment->post_id = $id;

        $this->setSiteTitle($post->title);
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
}