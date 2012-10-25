<?php
class CommentController extends MobileController
{
    public function actionCreate($callback, $id = 0)
    {
        $id = (int)$id;
        $callback = strip_tags(trim($callback));
    
        if (!request()->getIsAjaxRequest() || !request()->getIsPostRequest() || empty($callback))
            throw new CHttpException(500);
    
        $data = array();
        $model = new MobileCommentForm();
        $model->attributes = $_POST['MobileCommentForm'];
        $model->content = h($model->content);
    
        if ($id > 0 && $quote = Comment::model()->findByPk($id)) {
            $quoteTitle = sprintf(t('comment_quote_title'), $quote->authorName);
            $html = '<fieldset class="beta-comment-quote"><legend>' . $quoteTitle . '</legend>' . $quote->content . '</fieldset>';
            $model->content = $html . $model->content;
        }
    
        if ($model->validate() && ($comment = $model->save())) {
            $data['errno'] = 0;
            $data['text'] = t('ajax_comment_done');
            $data['html'] = $this->renderPartial('/comment/_one', array('comments'=>array($comment)), true); // @todo 反回此条评论的html代码
        }
        else {
            $data['errno'] = 1;
            $attributes = array_keys($model->getErrors());
            foreach ($attributes as $attribute)
                $labels[] = $model->getAttributeLabel($attribute);
            $errstr = join(' ', $labels);
            $data['text'] = sprintf(t('ajax_comment_error'), $errstr);
        }
        echo $callback . '(' . json_encode($data) . ')';
        exit(0);
    }
    
    public function actionList($pid, $callback, $page = 1)
    {
        $pid = (int)$pid;
        $page = (int)$page;
        $page = $page < 1 ? 1 : $page;
        
        $post = MobilePost::model()->findByPk($pid);
        if ($post === null)
            throw new CHttpException(403, t('post_is_not_found'));
        
        $comments = MobileComment::model()->fetchList($pid, $page);
        
        $data['errno'] = 0;
        $data['text'] = t('ajax_comment_done');
        $data['html'] = $this->renderPartial('/comment/_one', array('comments'=>$comments, 'post'=>$post), true);
        
        echo $callback . '(' . json_encode($data) . ')';
        exit(0);
    }
}