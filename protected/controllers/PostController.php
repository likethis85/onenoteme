<?php
class PostController extends Controller
{
    public function filters()
    {
        return array(
            'ajaxOnly  + score',
            'postOnly + score',
        );
    }
    
    public function actionIndex()
    {
        $this->forward('post/latest');
    }
    
    public function actionScore()
    {
        $id = (int)$_POST['id'];
        $column = ((int)$_POST['score'] > 0) ? 'up_score' : 'down_score';
        $counters = array($column => 1);
        $result = Post::model()->updateCounters($counters, 'id = :id', array(':id'=>$id));
        echo (int)$result;
        exit(0);
    }
    
    public function actionShow($id)
    {
        $id = (int)$id;
        if ($id <= 0)
            throw new CHttpException(500, '非法请求');
        
        $cmd = app()->getDb()->createCommand();
        if (user()->getFlash('allowUserView'))
            $post = DPost::model()->findByPk($id);
        else {
            $cmd->where('id = :id and state = :state', array('id' => $id, ':state' => POST_STATE_ENABLED));
            $post = DPost::model()->find($cmd);
        }
        if (null === $post)
            throw new CHttpException(404, '该段子不存在或未被审核');
        
        // 获取评论
        $limit = param('commentCountOfPage');
        $conditions = array('and', 'post_id = :pid', 'state = :state');
        $params = array(':pid' => $id, ':state' => COMMENT_STATE_ENABLED);
        
        $cmd = app()->db->createCommand()
            ->order('id asc')
            ->limit($limit)
            ->where($conditions, $params);
            
        $count = DComment::model()->count($conditions, $params);
        $pages = new CPagination($count);
        $pages->setPageSize($limit);
        $offset = $pages->getCurrentPage() * $limit;
        $cmd->offset($offset);
        $comments = (array)DComment::model()->findAll($cmd);
        
        $this->pageTitle = trim(strip_tags($post->title)) . ' - 挖段子';
        $this->setKeywords($this->pageTitle);
        $this->setDescription($this->pageTitle);
        
        $this->channel = 'post';
        $this->render('show', array(
            'post' => $post,
            'comments' => $comments,
            'pages' => $pages,
        ));
    }
}

