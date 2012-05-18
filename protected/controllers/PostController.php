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
            $model->user_id = user()->getIsGuest() ? 0 : user()->id;
            if (!user()->getIsGuest() && empty($model->user_name))
                $model->user_name = user()->name;
            $model->state = (app()->session['state'] >= User::STATE_EDITOR) ? Post::STATE_ENABLED : Post::STATE_DISABLED;
            
            $model->pic = CUploadedFile::getInstance($model, 'pic');
            if ($model->save(true, array('id', 'channel_id', 'category_id', 'title', 'content', 'create_time', 'up_score', 'down_score', 'comment_nums', 'tags', 'state'))) {
                $msg = '<span class="cgreen f12px">发布成功，' . CHtml::link('点击查看', $model->url, array('target'=>'_blank')) . '，您还可以继续发布。</span>';
                user()->setFlash('createPostResult', $msg);
                user()->setFlash('allowUserView', user()->name);
                
                if ($model->pic) {
                    $path = CDBase::makeUploadPath('pics');
                    $file = CDBase::makeUploadFileName('');
                    $bigFile = 'big_' . $file;
                    $filename = $path['path'] . $file;
                    $bigFilename = $path['path'] . $bigFile;

                    try {
                        $im = new CdImage();
                        $im->load($model->pic->tempName);
                        $im->saveAsJpeg($bigFilename);
                        $post->big_pic = fbu($path['url'] . $im->filename());
                        $im->saveAsJpeg($filename, 50);
                        $post->pic = fbu($path['url'] . $im->filename());
                        $model->update(array('pic', 'big_pic'));
                    }
                    catch (Exception $e) {
                        $model->addError('pic', '上传图片错误');
                    }
                }
                if (!$model->hasErrors())
                    $this->redirect(aurl('post/create'));
            }
            else
                user()->setFlash('createPostResult', '<span class="cred f12px">发布出错，查看下面详细错误信息。</span>');
        }
        $cmd = app()->getDb()->createCommand()
            ->select(array('id', 'name'))
            ->order('orderid desc, id asc');
        $categories = CHtml::listData(DCategory::model()->findAll($cmd), 'id', 'name');
        global $channels;
        
        $this->pageTitle = '发段子 - 挖段子';
        $this->setKeywords('发布段子,发布经典语录,发布糗事,发布秘密,发布笑话');
        $this->setDescription('发布段子,发布经典语录,发布糗事,发布秘密,发布笑话');
        
        $this->channel = 'create';
        $this->render('create', array(
        	'model'=>$model,
            'channels' => $channels,
            'categories' => $categories,
        ));
    }
    
    public function actionLatest()
    {
        $duration = 120;
        $limit = param('postCountOfPage');
        $where = 't.state != :state';
        $params = array(':state' => DPost::STATE_DISABLED);
        $cmd = app()->db->cache($duration)->createCommand()
            ->order('t.create_time desc, t.id desc')
            ->limit($limit)
            ->where($where, $params);
            
        $count = DPost::model()->cache($duration)->count($where, $params);
        $pages = new CPagination($count);
        $pages->setPageSize($limit);
        
        $offset = $pages->getCurrentPage() * $limit;
        $cmd->offset($offset);
        $models = DPost::model()->cache($duration)->findAll($cmd);
        
        $this->pageTitle = '挖段子 - 笑死人不尝命 - 每日精品笑话连载';
        $this->setKeywords('每日精品笑话连载,网络趣图，漫画,邪恶漫画,趣图百科,暴走漫画连载,阳光正妹,爱正妹,糗事百科,笑话大全 爆笑,黄色笑话,幽默笑话,成人笑话,经典笑话,笑话短信,爆笑笑话,幽默笑话大全,夫妻笑话,笑话集锦,搞笑笑话,荤笑话,极品笑话,黄段子,爆笑短信,最新笑话,最全的笑话,经典语录,糗事百科,秘密,笑话段子,经典笑话,笑话大全,搞笑大全,我们爱讲冷笑话,哈哈笑');
        $this->setDescription('最新发布的段子，每日精品笑话连载。网罗互联网各种精品段子，各种糗事，各种笑话，各种秘密，各种经典语录，各种有趣的图片，各种漂亮mm校花模特正妹，应有尽有。烦了、累了、无聊了，就来挖段子逛一逛。');
        
        $this->channel = 'live';
        $this->render('latest', array(
        	'models' => $models,
            'pages' => $pages,
        ));
    }
    
    public function actionHottest()
    {
        $this->forward('post/hour8');
    }
    
    public function actionList($cid)
    {
        $cid = (int)$cid;
        $limit = param('postCountOfPage');
        $where = 'state != :state and category_id = :cid';
        $params = array(':state'=>DPost::STATE_DISABLED, ':cid'=>$cid);
        $cmd = app()->db->createCommand()
            ->order('create_time desc, id desc')
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
        
        $this->pageTitle = '瞅瞅 - 挖段子';
        $this->setKeywords('段子分类,' . implode(',', CHtml::listData($categories, 'id', 'name')));
        $this->setDescription('挖段子分类和每个分类的笑话列表。');
        
        $this->channel = 'waduanzi';
        $this->render('list_of_category', array(
        	'models' => $models,
            'pages' => $pages,
            'categories' => $categories,
        ));
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
    
    public function actionShow($id)
    {
        $id = (int)$id;
        if ($id <= 0)
            throw new CHttpException(500, '非法请求');
        
        $cmd = app()->getDb()->createCommand();
        if (user()->getFlash('allowUserView'))
            $post = DPost::model()->findByPk($id);
        else {
            $cmd->where('id = :id and state != :state', array('id' => $id, ':state' => DPost::STATE_DISABLED));
            $post = DPost::model()->find($cmd);
        }
        if (null === $post)
            throw new CHttpException(404, '该段子不存在或未被审核');
        
        // 获取评论
        $limit = param('commentCountOfPage');
        $conditions = array('and', 'post_id = :pid', 'state = :state');
        $params = array(':pid' => $id, ':state' => DComment::STATE_ENABLED);
        
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
    
    public function actionSitemap()
    {
        $cmd = app()->getDb()->createCommand()
            ->select('id')
            ->from(DPost::model()->table())
            ->where('state != 0')
            ->order('id desc')
            ->limit(5000);
        $posts = $cmd->queryAll();
        $this->renderPartial('sitemap', array(
            'posts' => $posts
        ));
        app()->end();
    }
    
}