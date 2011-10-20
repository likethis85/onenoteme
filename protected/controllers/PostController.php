<?php
class PostController extends Controller
{
    public function actionCreate()
    {
        $model = new Post();
        if (request()->getIsPostRequest() && isset($_POST['Post'])) {
            $model->attributes = $_POST['Post'];
            if ($model->save()) {
                $msg = '<span class="cgreen f12px">发布成功，' . CHtml::link('点击查看', $model->url, array('target'=>'_blank')) . '，您还可以继续发布。</span>';
                user()->setFlash('createPostResult', $msg);
                $this->redirect(aurl('post/create'));
            }
            else
                user()->setFlash('createPostResult', '<span class="cred f12px">发布出错，查看下面详细错误信息。</span>');
        }
        $this->render('create', array('model'=>$model));
    }
    
    public function actionLatest()
    {
        $cmd = app()->db->createCommand()
            ->order('id desc')
            ->limit(30)
            ->where('state != :state', array(':state' => DPost::STATE_DISABLED));
        $models = DPost::model()->findAll($cmd);
        
        $this->render('list', array('models' => $models));
    }
    
    public function actionHour()
    {
        $date = new DateTime();
        $date->sub(new DateInterval('PT1H'));
        $time = $date->getTimestamp();
        $condition = 'create_time > ' . $time;
        
        $models = DPost::fetchValidList(param('postCountOfPage'), 1, $condition, '(up_score-down_score) desc, id desc');
        $this->render('list', array('models' => $models));
    }
    
    public function actionHour8()
    {
        $date = new DateTime();
        $date->sub(new DateInterval('PT8H'));
        $time = $date->getTimestamp();
        $condition = 'create_time > ' . $time;
        
        $models = DPost::fetchValidList(param('postCountOfPage'), 1, $condition, '(up_score-down_score) desc, id desc');
        $this->render('list', array('models' => $models));
    }
    
    public function actionDay()
    {
        $date = new DateTime();
        $date->sub(new DateInterval('P1D'));
        $time = $date->getTimestamp();
        $condition = 'create_time > ' . $time;
        
        $models = DPost::fetchValidList(param('postCountOfPage'), 1, $condition, '(up_score-down_score) desc, id desc');
        $this->render('list', array('models' => $models));
    }
    
    public function actionWeek()
    {
        $date = new DateTime();
        $date->sub(new DateInterval('P1W'));
        $time = $date->getTimestamp();
        $condition = 'create_time > ' . $time;
        
        $models = DPost::fetchValidList(param('postCountOfPage'), 1, $condition, '(up_score-down_score) desc, id desc');
        $this->render('list', array('models' => $models));
    }
    
    public function actionMonth()
    {
        $date = new DateTime();
        $date->sub(new DateInterval('P1M'));
        $time = $date->getTimestamp();
        $condition = 'create_time > ' . $time;
        
        $models = DPost::fetchValidList(param('postCountOfPage'), 1, $condition, '(up_score-down_score) desc, id desc');
        $this->render('list', array('models' => $models));
    }
    
    public function actionList($cid)
    {
        $cid = (int)$cid;
        $limit = param('postCountOfPage');
        $where = 'state != :state and category_id = :cid';
        $params = array(':state'=>DPost::STATE_DISABLED, ':cid'=>$cid);
        $cmd = app()->db->createCommand()
            ->order('id desc')
            ->limit($limit)
            ->where($where, $params);
            
        $count = DPost::model()->count($where, $params);
        $pages = new CPagination($count);
        $pages->setPageSize($limit);
        
        $offset = $pages->getCurrentPage() * $limit;
        $cmd->offset($offset);
        $models = DPost::model()->findAll($cmd);
        
        $this->render('list', array(
        	'models' => $models,
            'pages' => $pages,
        ));
    }
    
    public function actionVote()
    {
        $cmd = app()->db->createCommand()
            ->order('id asc')
            ->where('state = :state', array(':state'=>DPost::STATE_DISABLED));
        $model = DPost::model()->find($cmd);
        
        $this->render('vote', array(
        	'model' => $model,
        ));
    }
    
    public function actionScore($id)
    {
        $id = (int)$id;
        $column = ((int)$_POST['score'] > 0) ? 'up_score' : 'down_score';
        $counters = array($column => 1);
        $result = Post::model()->updateCounters($counters, 'id = :id', array(':id'=>$id));
        echo (int)$result;
        exit(0);
    }
    
    public function actionDown($id)
    {
        $socre = ((int)$_POST['score'] > 0) ? 1 : -1;
    }
}