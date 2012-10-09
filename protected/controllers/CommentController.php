<?php
class CommentController extends Controller
{
    public function filters()
    {
        return array(
            'ajaxOnly + create',
            'postOnly + create',
        );
    }
    
    public function actionList($pid)
    {
        
    }
    
    public function actionCreate()
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
        
        echo CJSON::encode($data);
        exit(0);
    }

    public function actionScore()
    {
        $id = (int)$_POST['id'];
        $column = ((int)$_POST['score'] > 0) ? 'up_score' : 'down_score';
        $counters = array($column => 1);
        $result = Comment::model()->updateCounters($counters, 'id = :id', array(':id'=>$id));
        echo (int)$result;
        exit(0);
    }
}