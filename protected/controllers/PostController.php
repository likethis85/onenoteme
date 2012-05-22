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
    
    public function actionLatest()
    {
        $duration = 120;
        $limit = param('postCountOfPage');
        
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(array('state'=>POST_STATE_ENABLED));
        $criteria->order = 'create_time desc, id desc';
        $criteria->limit = $limit;
        
        $count = Post::model()->count($criteria);
        $pages = new CPagination($count);
        $pages->setPageSize($limit);
        $pages->applyLimit($criteria);
        
        $models = Post::model()->findAll($criteria);
        
        $this->pageTitle = '挖段子 - 笑死人不尝命 - 每日精品笑话连载';
        $this->setKeywords('每日精品笑话连载,网络趣图，漫画,邪恶漫画,趣图百科,暴走漫画连载,阳光正妹,爱正妹,糗事百科,笑话大全 爆笑,黄色笑话,幽默笑话,成人笑话,经典笑话,笑话短信,爆笑笑话,幽默笑话大全,夫妻笑话,笑话集锦,搞笑笑话,荤笑话,极品笑话,黄段子,爆笑短信,最新笑话,最全的笑话,经典语录,糗事百科,秘密,笑话段子,经典笑话,笑话大全,搞笑大全,我们爱讲冷笑话,哈哈笑');
        $this->setDescription('最新发布的段子，每日精品笑话连载。网罗互联网各种精品段子，各种糗事，各种笑话，各种秘密，各种经典语录，各种有趣的图片，各种漂亮mm校花模特正妹，应有尽有。烦了、累了、无聊了，就来挖段子逛一逛。');
        
        $this->channel = 'live';
        $this->render('mixed_list', array(
            'models' => $models,
            'pages' => $pages,
        ));
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

