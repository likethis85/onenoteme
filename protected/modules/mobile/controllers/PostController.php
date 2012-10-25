<?php
class PostController extends MobileController
{
    public function actionShow($id)
    {
        $id = (int)$id;
        $post = MobilePost::model()->findByPk($id);
        if ($post === null)
            throw new CHttpException(403, t('post_is_not_found'));
        
        $comments = MobileComment::model()->fetchList($id);
        $comment = new MobileCommentForm();
        $comment->post_id = $id;
        
        if ($post->getContainCode()) {
            cs()->registerScriptFile(sbu('libs/prettify/prettify.js'), CClientScript::POS_END);
            cs()->registerCssFile(sbu('libs/prettify/prettify.css'), 'screen');
        }

        $this->setSiteTitle($post->title);
        cs()->registerMetaTag('all', 'robots');
        $this->render('show', array(
            'post' => $post,
            'comments' => $comments,
            'comment' => $comment,
        ));
    }
    
    
    
}