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
        
        if (request()->getIsAjaxRequest()) {
            echo json_encode(DComment::model()->queryAll($cmd));
            exit(0);
        }
        else {
            $models = DComment::model()->findAll($cmd);
            $this->render('list', array(
            	'models' => $models,
                'pages' => $pages,
            ));
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