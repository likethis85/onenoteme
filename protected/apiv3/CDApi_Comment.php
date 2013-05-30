<?php
class CDApi_Comment extends ApiBase
{
    public function init()
    {
        $this->saveDeviceConnectHistory();
    }
    
    /**
     * 发布评论
     * @param string $post_id, required
     * @param string $content, requried
     */
    public function create()
    {
        $this->requiredParams(array('post_id', 'content'));
        $params = $this->filterParams(array('post_id', 'content'));
        
        $comment = new ApiComment;
        $comment->post_id = (int)$params['post_id'];
        $comment->content = trim(strip_tags($params['content']));
        if ($comment->save())
            return $this->formatRow($comment);
        else
            throw new CDApiException(ApiError::COMMENT_SAVE_ERROR);
        
    }
    
    /**
     * 获取一条段子的评论
     * @param integer $post_id 段子ID，required.
     * @param integer $lasttime 最后更新时间，optional.
     * @param integer $maxtime 载入更多评论时截止的最大时间，optional.
     */
    public function show()
    {
        $this->requiredParams(array('post_id'));
        $params = $this->filterParams(array('post_id', 'lasttime'));
        
        $criteria = new CDbCriteria();
        $criteria->select = $this->selectColumns();
        $criteria->limit = $this->timelineRowCount();
        $criteria->order = 't.create_time asc';
        $criteria->with = array('user', 'user.profile');
        
        $lasttime = (int)$params['lasttime'];
        $maxtime = (int)$params['maxtime'];
        if ($lasttime > 0) {
            $criteria->addCondition('t.create_time > :lasttime');
            $criteria->params[':lasttime'] = $lasttime;
        }
        
        $posts = ApiComment::model()->findAll($criteria);
        $rows = $this->formatRows($posts);
        
        return $rows;
    }

    
    
    
    
    
    
    
    
    
    protected function timelineRowCount()
    {
        return 10;
    }

    protected function formatRows(array $models)
    {
        $rows = array();
        foreach ($models as $index => $model)
            $rows[$index] = CDDataFormat::formatComment($model);
    
        $models = null;
        return $rows;
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



