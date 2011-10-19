<?php
class PostController extends Controller
{
    public function actionCreate()
    {
        $model = new Post();
        if (request()->getIsPostRequest() && isset($_POST['Post'])) {
            $model->attributes = $_POST['Post'];
            if ($model->save()) {
                // @todo 保存成功后的操作
            }
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
        
        $models = self::topOfTime($condition);
        $this->render('list', array('models' => $models));
    }
    
    public function actionHour8()
    {
        $date = new DateTime();
        $date->sub(new DateInterval('PT8H'));
        $time = $date->getTimestamp();
        $condition = 'create_time > ' . $time;
        
        $models = self::topOfTime($condition);
        $this->render('list', array('models' => $models));
    }
    
    public function actionDay()
    {
        $date = new DateTime();
        $date->sub(new DateInterval('P1D'));
        $time = $date->getTimestamp();
        $condition = 'create_time > ' . $time;
        
        $models = self::topOfTime($condition);
        $this->render('list', array('models' => $models));
    }
    
    public function actionWeek()
    {
        $date = new DateTime();
        $date->sub(new DateInterval('P1W'));
        $time = $date->getTimestamp();
        $condition = 'create_time > ' . $time;
        
        $models = self::topOfTime($condition);
        $this->render('list', array('models' => $models));
    }
    
    public function actionMonth()
    {
        $date = new DateTime();
        $date->sub(new DateInterval('P1M'));
        $time = $date->getTimestamp();
        $condition = 'create_time > ' . $time;
        
        $models = self::topOfTime($condition);
        $this->render('list', array('models' => $models));
    }
    
    private static function topOfTime($where = '', $limit = 30, $order = 'id desc')
    {
        $defaultWhere = 'state != ' . DPost::STATE_DISABLED;
        if ($where)
            $where = array('and', $defaultWhere, $where);
        $cmd = app()->db->createCommand()
            ->order('id desc')
            ->limit(30)
            ->where($where);
        return DPost::model()->findAll($cmd);
    }
    
    public function actionTu()
    {
        // @todo 表中还未添加此图的字段pic
    }
    
    public function actionVote()
    {
        
    }
}