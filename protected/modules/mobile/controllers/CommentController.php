<?php
class CommentController extends MobileController
{
    public function filters()
    {
        return array(
            'ajaxOnly + create, supprt, against, rating',
            'postOnly + create, supprt, against, rating',
            array(
                'COutputCache + list',
                'duration' => param('mobile_comment_list_cache_expire'),
                'varyByParam' => array('pid', 'page'),
            ),
        );
    }
    
    public function actionCreate($id = 0)
    {
        $id = (int)$id;
    
        $data = array();
        $model = new MobileCommentForm();
        $model->attributes = $_POST['MobileCommentForm'];
        $model->content = h($model->content);
    
        if ($id > 0 && $quote = Comment::model()->findByPk($id)) {
            $quoteTitle = sprintf('', $quote->authorName);
            $html = sprintf('<fieldset class="beta-comment-quote"><legend>引用%s的评论:</legend>%s</fieldset>', $quoteTitle, $quote->content);
            $model->content = $html . $model->content;
        }
    
        if ($model->validate() && ($comment = $model->save())) {
            $data['errno'] = 0;
            $data['text'] = '评论成功';
            $data['html'] = $this->renderPartial('/comment/_one', array('comments'=>array($comment)), true); // @todo 反回此条评论的html代码
        }
        else {
            $data['errno'] = 1;
            $attributes = array_keys($model->getErrors());
            foreach ($attributes as $attribute)
                $labels[] = $model->getAttributeLabel($attribute);
            $errstr = join(' ', $labels);
            $data['text'] = sprintf('评论失败, %s不正确', $errstr);
        }
        echo CJSON::encode($data);
        exit(0);
    }
    
    public function actionList($pid, $page = 1)
    {
        $pid = (int)$pid;
        $page = (int)$page;
        $page = $page < 1 ? 1 : $page;
        
        $post = MobilePost::model()->findByPk($pid);
        if ($post === null)
            throw new CHttpException(403, '请求的内容不存在');
        
        $comments = MobileComment::model()->fetchList($pid, $page);
        
        $data['errno'] = 0;
        $data['text'] = '评论成功';
        $data['html'] = $this->renderPartial('/comment/_one', array('comments'=>$comments, 'post'=>$post), true);
        
        echo CJSON::encode($data);
        exit(0);
    }

    public function actionSupport($id)
    {
        self::rating('up_score', $id);
        exit(0);
    }
    
    public function actionAgainst($id)
    {
        self::rating('down_score', $id);
        exit(0);
    }

    private static function rating($field, $id)
    {
//         sleep(2);
        $id = (int)$id;
        $callback = strip_tags(trim($callback));
        $field = strip_tags(trim($field));
        if ($id <= 0)
            throw new CHttpException(500);
    
        $counters = array($field => 1);
        try {
            $nums = Comment::model()->updateCounters($counters, 'id = :commentid', array(':commentid'=>$id));
            $data['errno'] = (int)($nums === 0);
            $data['text'] = ($nums === 0) ? '评论出错' : '感谢您的参与';
            echo CJSON::encode($data);
            exit(0);
        }
        catch (Exception $e) {
            throw new CHttpException(500);
        }
    }
}