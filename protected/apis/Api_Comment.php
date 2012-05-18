<?php
class Api_Comment extends ApiBase
{
    public function getlist()
    {
        $this->requiredParams(array('postid'));
        $params = $this->filterParams(array('postid', 'count', 'page'));
        
        $postid = (int)$params['postid'];
        $count = (int)$params['count'];
        $page = (int)$params['page'];
        
        if ($postid <= 0)
            $data = array('errno' => 1);
        else {
            $cmd = app()->getDb()->createCommand()
                ->select(array('t.id', 't.content', 't.create_time'))
                ->from(TABLE_COMMENT . ' t')
                ->order('t.id desc')
                ->where('t.post_id = :postid', array(':postid'=>$postid));
                
            if ($count > 0)
                $cmd->limit = $count;
            
            if ($page > 0)
                $cmd->offset = ($page - 1) * $count;
        
            $data = $cmd->queryAll();
            foreach ($data as $key => $row) {
                $data[$key] = self::formatRow($row);
            }
        }

        return $data;
    }
    
    private static function formatRow($row)
    {
        if (isset($row['create_time']))
            $row['create_time_text'] = date(param('formatShortDateTime'), $row['create_time']);
        
        if (isset($row['content']))
            $row['content'] = strip_tags(trim($row['content']));
        
        return $row;
    }
    
    
    public function create()
    {
        self::requirePost();
        $this->requiredParams(array('postid', 'content'));
        $params = $this->filterParams(array('postid', 'content', 'userid'));
        
        $postid = (int)$params['postid'];
        $content = strip_tags(trim($params['content']));
        $useid = (int)$params['userid'];
        
        if ($postid <= 0 || empty($content))
            $data = array(
                'errno'=>1,
                'message'=>'非法操作',
            );
        else {
            $comment = new Comment();
            $comment->post_id = $postid;
            $comment->content = $content;
            $comment->state = Comment::STATE_ENABLED;
            $comment->user_id = $userid;
            $result = $comment->save();
            
            if ($result) {
                $data = array('errno'=>0);
            }
            else
                $data = array(
                    'errno' => 1,
                    'message' => '数据库操作错误'
                );
        }
        
        return $data;
    }
}