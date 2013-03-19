<?php
class CommentController extends Controller
{
    public function filters()
    {
        $duration = 60;
        return array(
            'ajaxOnly + create, score, list, score',
            'postOnly + create, score',
            array(
                'COutputCache + list',
                'duration' => $duration,
                'varyByParam' => array('id'),
            ),
        );
    }
    
    public function actionList($id)
    {
        $id = (int)$id;
        try {
            $comments = Comment::fetchListByPostID($id, 1, param('comment_count_page_home'));
            $html = $this->renderPartial('create', array('postid' => $id), true);
            if (!empty($comments))
                $html .= $this->renderPartial('list', array('comments'=>$comments), true);
            
            $data = array('html' => $html);
            CJSON::encode($data);
        }
        catch (Exception $e) {
            throw new CHttpException(500, $e->getMessage());
        }
        exit(0);
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
            $data['html'] = $this->renderPartial('list', array('comments'=>array($c)), true);
        else
            $data['error'] = '发表评论出错';
        
        CJSON::encode($data);
        exit(0);
    }

    public function actionScore()
    {
        $id = (int)$_POST['id'];
        $column = ((int)$_POST['score'] > 0) ? 'up_score' : 'down_score';
        $counters = array($column => 1);
        $result = Comment::model()->updateCounters($counters, 'id = :id', array(':id'=>$id));
        $data = array('errno' => (int)!$result);
        CJSON::encode($data);
        exit(0);
    }
}
