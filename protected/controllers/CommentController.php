<?php
class CommentController extends Controller
{
    public function filters()
    {
        return array(
            'ajaxOnly + create, score',
            'postOnly + create, score',
        );
    }
    
    public function actionList($id, $callback = '')
    {
        $id = (int)$id;
        try {
            $comments = Comment::fetchListByPostID($id, 1, param('comment_count_page_home'));
            $html = $this->renderPartial('comment/create', array('postid' => $id));
            if (!empty($comments))
                $html = $this->renderPartial('/comment/list', array('comments'=>$comments), true);
            
            $data = array('html' => $html);
            CDBase::jsonp($callback, $data);
        }
        catch (Exception $e) {
            throw new CHttpException(500, $e->getMessage());
        }
        exit(0);
    }
    
    public function actionCreate($callback)
    {
        $c = new Comment();
        $c->post_id = $_POST['postid'];
        $c->content = $_POST['content'];
        $c->user_id = user()->isGuest ? 0 : user()->id;
        $c->user_name = user()->isGuest ? '' : user()->name;
        $c->state = COMMENT_STATE_ENABLED;
        $result = (int)$c->save();
        $data['errno'] = (int)!$result;
        if ($result)
            $data['html'] = $this->renderPartial('/comment/list', array('comments'=>array($c)), true);
        else
            $data['error'] = '发表评论出错';
        
        CDBase::jsonp($callback, $data);
    }

    public function actionScore($callback)
    {
        $id = (int)$_POST['id'];
        $column = ((int)$_POST['score'] > 0) ? 'up_score' : 'down_score';
        $counters = array($column => 1);
        $result = Comment::model()->updateCounters($counters, 'id = :id', array(':id'=>$id));
        $data = array('errno' => (int)!$result);
        CDBase::jsonp($callback, $data);
    }
}