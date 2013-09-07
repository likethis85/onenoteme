<?php
class CommentController extends RestController
{
    public function filters()
    {
        return array(
            'postOnly + create',
            'putOnly + support, report',
        );
    }
    
    /**
     * 发布评论
     * @param string $post_id, required
     * @param string $content, requried
     */
    public function actionCreate()
    {
        $post_id = (int)request()->getPost('post_id');
        $content = trim(request()->getPost('content'));
        $userid = (int)request()->getPost('user_id');
        $username = trim(request()->getPost('user_name'));
        if (empty($post_id) || empty($content))
            throw new CDRestException(CDRestError::PARAM_NOT_COMPLETE, 'post_id, content is required');
        
        $comment = new RestComment();
        $comment->post_id = $post_id;
        $comment->content = $content;
        $comment->user_id = $userid;
        $comment->user_name = $username;
        $comment->state = COMMENT_STATE_ENABLED;
        $comment->source = $this->fetchCommentSource();
        
        if ($comment->save()) {
            $data = CDRestDataFormat::formatComment($comment);
            $this->output($data);
        }
        else
            throw new CDRestException(CDRestError::COMMENT_SAVE_ERROR);
    }
    
    /**
     * 举报评论
     * @param string $comment_id, required
     */
    public function actionReport($comment_id)
    {
        $comment_id = (int)$comment_id;
        if (empty($comment_id))
            throw new CHttpException(500, 'request is invalid');
        
        $criteria = new CDbCriteria();
        $criteria->select = array('id', 'report_count');
        $comment = RestComment::model()->published()->findByPk($comment_id, $criteria);
        
        if ($comment === null)
            throw new CHttpException(404, 'comment is not found');
        
        $comment->report_count++;
        $result = $comment->save(true, array('report_count'));
        $data = array(
            'comment_id' => $comment->id,
            'report_count' => $comment->report_count,
        );
        $this->output($data);
    }
    
    /**
     * 举报评论
     * @param string $comment_id, required
     */
    public function actionSupport($comment_id)
    {
        $comment_id = (int)$comment_id;
        if (empty($comment_id))
            throw new CHttpException(500, 'request is invalid');
    
        $criteria = new CDbCriteria();
        $criteria->select = array('id', 'up_score');
        $comment = RestComment::model()->published()->findByPk($comment_id, $criteria);
    
        if ($comment === null)
            throw new CHttpException(404, 'comment is not found');
    
        $comment->up_score++;
        $result = $comment->save(true, array('up_score'));
        $data = array(
            'comment_id' => $comment->id,
            'up_score' => $comment->up_score,
        );
        $this->output($data);
    }
    
    /**
     * 获取一条段子的评论
     * @param integer $post_id 段子ID，required.
     * @param integer $lasttime 最后更新时间，optional.
     */
    public function actionShow($post_id, $lasttime = 0)
    {
        $post_id = (int)$post_id;
        $lasttime = (int)$lasttime;
        
        $criteria = new CDbCriteria();
        $criteria->select = $this->selectColumns();
        $criteria->limit = $this->timelineRowCount();
        $criteria->order = 't.create_time asc';
        $criteria->with = array('user', 'user.profile');
        $criteria->addColumnCondition(array('post_id'=>$post_id));
    
        if ($lasttime > 0) {
            $criteria->addCondition('t.create_time > :lasttime');
            $criteria->params[':lasttime'] = $lasttime;
        }
    
        $posts = RestComment::model()->published()->findAll($criteria);
        $rows = CDRestDataFormat::formatComments($posts);
    
        $this->output($rows);
    }
    
    
    
    
    
    
    
    
    private function fetchCommentSource()
    {
        $source = COMMENT_SOURCE_UNKNOWN;
        $osname = strtolower($this->osName);
        if (stripos($osname, 'android') !== false)
            $source = COMMENT_SOURCE_ANDROID;
        elseif (stripos($osname, 'iphone') !== false)
            $source = COMMENT_SOURCE_IPHONE;
        elseif (stripos($osname, 'ipad') !== false)
            $source = COMMENT_SOURCE_IPAD;
    
        return $source;
    }
    
    protected function timelineRowCount()
    {
        return 10;
    }
    
    /**
     * 返回字段列表
     */
    public function selectColumns()
    {
        return array('id', 'post_id', 'content', 'create_time',
                'up_score', 'down_score', 'user_id', 'user_name', 'recommend');
    }
}


