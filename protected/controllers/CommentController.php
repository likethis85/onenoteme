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
        $limit = param('commentCountOfPage');
        $conditions = array('and', 'post_id = :pid', 'state = :state');
        $params = array(':pid' => $pid, ':state' => DComment::STATE_ENABLED);
        
        $cmd = app()->db->createCommand()
            ->order('id asc')
            ->limit($limit)
            ->where($conditions, $params);
            
        $count = DComment::model()->count($conditions, $params);
        $pages = new CPagination($count);
        $pages->setPageSize($limit);
        $offset = $pages->getCurrentPage() * $limit;
        $cmd->offset($offset);
        $models = DComment::model()->findAll($cmd);
            
        if (request()->getIsAjaxRequest()) {
            $this->renderPartial('ajax_list', array(
            	'models' => $models,
                'postid' => $pid,
                'count' => $count,
            ));
            exit(0);
        }
        else {
            $this->render('list', array(
            	'models' => $models,
                'postid' => $pid,
                'pages' => $pages,
            ));
            app()->end();
        }
    }
    
    public function actionCreate()
    {
        $c = new Comment();
        $c->post_id = $_POST['postid'];
        $c->content = $_POST['content'];
        $c->user_id = user()->isGuest ? 0 : user()->id;
        $c->user_name = user()->isGuest ? '' : user()->name;
        $c->state = (app()->session['state'] >= User::STATE_EDITOR) ? Comment::STATE_ENABLED : Comment::STATE_DISABLED;
        echo (int)$c->save();
        exit(0);
    }
}