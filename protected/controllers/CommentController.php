<?php
class CommentController extends Controller
{
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
            $this->render('ajax_list', array(
            	'models' => $models,
                'postid' => $pid,
            ));
            exit(0);
        }
        else {
            $this->render('list', array(
            	'models' => $models,
                'pages' => $pages,
            ));
            exit(0);
        }
    }
    
    public function actionCreate()
    {
        // @todo ajax添加评论
        $comment = new DComment();
        $comment->attributes = $_POST['Comment'];
        echo (int)$comment->insert();
        exit(0);
    }
}