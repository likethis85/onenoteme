<?php
class CommentController extends RestController
{
    public function filters()
    {
        return array(
            'postOnly + create',
            'putOnly + report',
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
        
        $comment = new ApiComment();
        $comment->post_id = $post_id;
        $comment->content = $content;
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
    public function actionReport()
    {
        $commentID = (int)request()->getPut('comment_id');
        if (empty($commentID))
            throw new CHttpException(500, 'request is invalid');
        
        $criteria = new CDbCriteria();
        $criteria->select = array('id', 'report_count');
        $comment = ApiComment::model()->published()->findByPk($commentID, $criteria);
        
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
    public function actionSupport()
    {
        $commentID = (int)request()->getPut('comment_id');
        if (empty($commentID))
            throw new CHttpException(500, 'request is invalid');
    
        $criteria = new CDbCriteria();
        $criteria->select = array('id', 'up_score');
        $comment = ApiComment::model()->published()->findByPk($commentID, $criteria);
    
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


