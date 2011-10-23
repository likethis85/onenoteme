<?php
class PostController extends Controller
{
    public function filters()
    {
        return array(
            'ajaxOnly  + vote, score',
            'postOnly + vote, score',
        );
    }
    
    public function actionIndex()
    {
        $this->forward('post/latest');
    }

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
        $limit = param('postCountOfPage');
        $where = 'state != :state';
        $params = array(':state' => DPost::STATE_DISABLED);
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
        
        $this->render('latest', array(
        	'models' => $models,
            'pages' => $pages,
        ));
    }
    
    public function actionHour()
    {
        $date = new DateTime();
        $date->sub(new DateInterval('PT1H'));
        $time = $date->getTimestamp();
        $condition = 'create_time > ' . $time;
        
        $models = DPost::fetchValidList(param('postCountOfPage'), 1, $condition, '(up_score-down_score) desc, id desc');
        $this->render('hot_list', array('models' => $models));
    }
    
    public function actionHour8()
    {
        $date = new DateTime();
        $date->sub(new DateInterval('PT8H'));
        $time = $date->getTimestamp();
        $condition = 'create_time > ' . $time;
        
        $models = DPost::fetchValidList(param('postCountOfPage'), 1, $condition, '(up_score-down_score) desc, id desc');
        $this->render('hot_list', array('models' => $models));
    }
    
    public function actionDay()
    {
        $date = new DateTime();
        $date->sub(new DateInterval('P1D'));
        $time = $date->getTimestamp();
        $condition = 'create_time > ' . $time;
        
        $models = DPost::fetchValidList(param('postCountOfPage'), 1, $condition, '(up_score-down_score) desc, id desc');
        $this->render('hot_list', array('models' => $models));
    }
    
    public function actionWeek()
    {
        $date = new DateTime();
        $date->sub(new DateInterval('P1W'));
        $time = $date->getTimestamp();
        $condition = 'create_time > ' . $time;
        
        $models = DPost::fetchValidList(param('postCountOfPage'), 1, $condition, '(up_score-down_score) desc, id desc');
        $this->render('hot_list', array('models' => $models));
    }
    
    public function actionMonth()
    {
        $date = new DateTime();
        $date->sub(new DateInterval('P1M'));
        $time = $date->getTimestamp();
        $condition = 'create_time > ' . $time;
        
        $models = DPost::fetchValidList(param('postCountOfPage'), 1, $condition, '(up_score-down_score) desc, id desc');
        $this->render('hot_list', array('models' => $models));
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
        
        $cmd = app()->db->createCommand()
            ->order('orderid desc, id asc');
        $categories = DCategory::model()->findAll($cmd);
        
        $this->render('list_of_category', array(
        	'models' => $models,
            'pages' => $pages,
            'categories' => $categories,
        ));
    }
    
    public function actionAppraise()
    {
        $where = 'state = :state';
        $params = array(':state'=>DPost::STATE_DISABLED);
        $cmd = app()->db->createCommand()
            ->order('id asc')
            ->where($where, $params);
            
        $count = DPost::model()->count($where, $params);
        $offset = mt_rand(0, abs($count-1));
        $cmd->offset($offset);
        $model = DPost::model()->find($cmd);
        
        $this->render('vote', array(
        	'model' => $model,
        ));
    }
    
    public function actionVote()
    {
        // this request require post and ajax
        $id = (int)$_POST['id'];
        $column = ((int)$_POST['score'] > 0) ? 'up_score' : 'down_score';
        $cmd = app()->getDb()->createCommand()
            ->where('id = :id and state = :state', array(':id'=>$id, ':state'=>DPost::STATE_DISABLED));
        $model = DPost::model()->find($cmd);
        
        if ($model === null)
            throw new CHttpException(500, '该段子不存在或已经通过签定');
        
        if (null === $model)
            throw new CHttpException(500, '非法请求');

        $model->$column += 1;
        
        $attributes = array($column);
        if ($model->getCanShow()) {
            $model->state = DPost::STATE_ENABLED;
            $attributes[] = 'state';
        }

        echo (int)$model->update($attributes);
        exit(0);
    }
    
    public function actionScore()
    {
        // this request require post and ajax
        $id = (int)$_POST['id'];
        $column = ((int)$_POST['score'] > 0) ? 'up_score' : 'down_score';
        $counters = array($column => 1);
        $result = Post::model()->updateCounters($counters, 'id = :id', array(':id'=>$id));
        echo (int)$result;
        exit(0);
    }
    
}