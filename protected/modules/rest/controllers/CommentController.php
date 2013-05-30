<?php
class CommentController extends RestController
{
    public function filters()
    {
        return array(
            'postOnly + create, report'
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
        if (empty($post_id) || empty($content))
            throw new CDRestException(CDRestError::PARAM_NOT_COMPLETE, 'post_id, content is required');
        
        $comment = new ApiComment;
        $comment->post_id = $post_id;
        $comment->content = $content;
        if ($comment->save())
            $this->output(CDRestDataFormat::formatComment($comment));
        else
            throw new CDRestException(CDRestError::COMMENT_SAVE_ERROR);
    }
    
    /**
     * 举报评论
     * @param string $comment_id, required
     */
    public function actionReport()
    {
        $comment_id = (int)request()->getPost('comment_id');
        if (empty($comment_id))
            throw new CDRestException(CDRestError::PARAM_NOT_COMPLETE, 'comment_id is required');
        
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(array('id'=>$comment_id));
        $counters = array('report_count' => 1);
        $result = ApiComment::model()->updateCounters($counters, $criteria);
        
        $this->output($result);
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
    
        $posts = ApiComment::model()->published()->findAll($criteria);
        $rows = CDRestDataFormat::formatComments($posts);
    
        $this->output($rows);
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


