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
            if ($model->save(true, array('id', 'category_id', 'title', 'content', 'create_time', 'up_score', 'down_score', 'comment_nums', 'tags', 'state'))) {
                $msg = '<span class="cgreen f12px">发布成功，' . CHtml::link('点击查看', $model->url, array('target'=>'_blank')) . '，您还可以继续发布。</span>';
                user()->setFlash('createPostResult', $msg);
                user()->setFlash('allowUserView', user()->name);
                
                if ($model->pic) {
                    $path = CDBase::makeUploadPath('pics');
                    $file = CDBase::makeUploadFileName($model->pic->extensionName);
                    $filename = $path['path'] . $file;
                    
                    if ($model->pic->saveAs($filename)) {
                        $model->pic = fbu($path['url'] . $file);
                        $model->update(array('pic'));
                    }
                }
                
                $this->redirect(aurl('post/create'));
            }
            else
                user()->setFlash('createPostResult', '<span class="cred f12px">发布出错，查看下面详细错误信息。</span>');
        }
        $cmd = app()->getDb()->createCommand()
            ->select(array('id', 'name'))
            ->order('orderid desc, id asc');
        $categories = CHtml::listData(DCategory::model()->findAll($cmd), 'id', 'name');
        
        $this->pageTitle = '发段子 - 挖段子';
        $this->setKeywords('发布段子,发布经典语录,发布糗事,发布秘密,发布笑话');
        $this->setDescription('发布段子,发布经典语录,发布糗事,发布秘密,发布笑话');
        
        $this->channel = 'create';
        $this->render('create', array(
        	'model'=>$model,
            'categories' => $categories,
        ));
    }
    
    public function actionLatest()
    {
        $this->redirect(url('post/live'));
    }
    
    public function actionLive()
    {
        $duration = 300;
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
        $this->setKeywords('每日精品笑话连载,笑话大全 爆笑,黄色笑话,幽默笑话,成人笑话,经典笑话,笑话短信,爆笑笑话,幽默笑话大全,夫妻笑话,笑话集锦,搞笑笑话,荤笑话,极品笑话,黄段子,爆笑短信,最新笑话,最全的笑话,经典语录,糗事百科,秘密,笑话段子,经典笑话,笑话大全,搞笑大全,我们爱讲冷笑话,哈哈笑');
        $this->setDescription('最新发布的段子，每日精品笑话连载。网罗互联网各种精品段子，各种糗事，各种笑话，各种秘密，各种经典语录，应有尽有。烦了、累了、无聊了，就来挖段子逛一逛。');
        
        $this->channel = 'live';
        $this->render('latest', array(
        	'models' => $models,
            'pages' => $pages,
        ));
    }
    
    public function actionHottest()
    {
        $this->forward('post/hour');
    }
    
    public function actionHour()
    {
        $date = new DateTime();
        $date->sub(new DateInterval('PT1H'));
        $time = $date->getTimestamp();
        $condition = 'create_time > ' . $time;
        
        $models = DPost::fetchValidList(param('postCountOfPage'), 1, $condition, '(up_score-down_score) desc, id desc');
        
        $this->pageTitle = '1小时最热门 - 挖段子';
        $this->setKeywords('每日精品笑话连载,笑话大全 爆笑,黄色笑话,幽默笑话,成人笑话,经典笑话,笑话短信,爆笑笑话,幽默笑话大全,夫妻笑话,笑话集锦,搞笑笑话,荤笑话,极品笑话,黄段子,爆笑短信,最新笑话,最全的笑话,经典语录,糗事百科,秘密,笑话段子,经典笑话,笑话大全,搞笑大全,我们爱讲冷笑话,哈哈笑');
        $this->setDescription('一小时内段子排行，一小时内笑话排行，一小时内经典语录排行 ，一小时糗事排行。');
        
        $this->channel = 'hottest';
        $this->render('hot_list', array('models' => $models));
    }
    
    public function actionHour8()
    {
        $date = new DateTime();
        $date->sub(new DateInterval('PT8H'));
        $time = $date->getTimestamp();
        $condition = 'create_time > ' . $time;
        
        $models = DPost::fetchValidList(param('postCountOfPage'), 1, $condition, '(up_score-down_score) desc, id desc');
        
        $this->pageTitle = '8小时最热门 - 挖段子';
        $this->setKeywords('每日精品笑话连载,笑话大全 爆笑,黄色笑话,幽默笑话,成人笑话,经典笑话,笑话短信,爆笑笑话,幽默笑话大全,夫妻笑话,笑话集锦,搞笑笑话,荤笑话,极品笑话,黄段子,爆笑短信,最新笑话,最全的笑话,经典语录,糗事百科,秘密,笑话段子,经典笑话,笑话大全,搞笑大全,我们爱讲冷笑话,哈哈笑');
        $this->setDescription('8小时内段子排行，8小时内笑话排行，8小时内经典语录排行 ，8小时糗事排行。');
        
        $this->channel = 'hottest';
        $this->render('hot_list', array('models' => $models));
    }
    
    public function actionDay()
    {
        $date = new DateTime();
        $date->sub(new DateInterval('P1D'));
        $time = $date->getTimestamp();
        $condition = 'create_time > ' . $time;
        
        $models = DPost::fetchValidList(param('postCountOfPage'), 1, $condition, '(up_score-down_score) desc, id desc');
        
        $this->pageTitle = '一天最热门 - 挖段子';
        $this->setKeywords('每日精品笑话连载,笑话大全 爆笑,黄色笑话,幽默笑话,成人笑话,经典笑话,笑话短信,爆笑笑话,幽默笑话大全,夫妻笑话,笑话集锦,搞笑笑话,荤笑话,极品笑话,黄段子,爆笑短信,最新笑话,最全的笑话,经典语录,糗事百科,秘密,笑话段子,经典笑话,笑话大全,搞笑大全,我们爱讲冷笑话,哈哈笑');
        $this->setDescription('一天内段子排行，一天内笑话排行，一天内经典语录排行 ，一天糗事排行。');
        
        $this->channel = 'hottest';
        $this->render('hot_list', array('models' => $models));
    }
    
    public function actionWeek()
    {
        $date = new DateTime();
        $date->sub(new DateInterval('P1W'));
        $time = $date->getTimestamp();
        $condition = 'create_time > ' . $time;
        
        $models = DPost::fetchValidList(param('postCountOfPage'), 1, $condition, '(up_score-down_score) desc, id desc');
        
        $this->pageTitle = '一周最热门 - 挖段子';
        $this->setKeywords('每日精品笑话连载,笑话大全 爆笑,黄色笑话,幽默笑话,成人笑话,经典笑话,笑话短信,爆笑笑话,幽默笑话大全,夫妻笑话,笑话集锦,搞笑笑话,荤笑话,极品笑话,黄段子,爆笑短信,最新笑话,最全的笑话,经典语录,糗事百科,秘密,笑话段子,经典笑话,笑话大全,搞笑大全,我们爱讲冷笑话,哈哈笑');
        $this->setDescription('一周内段子排行，一周内笑话排行，一周内经典语录排行 ，一周糗事排行。');
        
        $this->channel = 'hottest';
        $this->render('hot_list', array('models' => $models));
    }
    
    public function actionMonth()
    {
        $date = new DateTime();
        $date->sub(new DateInterval('P1M'));
        $time = $date->getTimestamp();
        $condition = 'create_time > ' . $time;
        
        $models = DPost::fetchValidList(param('postCountOfPage'), 1, $condition, '(up_score-down_score) desc, id desc');
        
        $this->pageTitle = '一周最热门 - 挖段子';
        $this->setKeywords('每日精品笑话连载,笑话大全 爆笑,黄色笑话,幽默笑话,成人笑话,经典笑话,笑话短信,爆笑笑话,幽默笑话大全,夫妻笑话,笑话集锦,搞笑笑话,荤笑话,极品笑话,黄段子,爆笑短信,最新笑话,最全的笑话,经典语录,糗事百科,秘密,笑话段子,经典笑话,笑话大全,搞笑大全,我们爱讲冷笑话,哈哈笑');
        $this->setDescription('一周内段子排行，一周内笑话排行，一周内经典语录排行 ，一周糗事排行。');
        
        $this->channel = 'hottest';
        $this->render('hot_list', array('models' => $models));
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
        $model = DPost::model()->cache(0)->find($cmd);
        
        $this->pageTitle = '鉴定 - 挖段子';
        $this->setKeywords('审核段子,鉴定段子');
        $this->setDescription('审核鉴定网友发布的段子是否符合挖段子标准，为挖段子增砖添瓦。');
        
        $this->channel = 'appraise';
        $this->render('appraise', array(
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
            
        if ($model->getCanDelete()) {
            echo (int)$model->delete();
            exit(0);
        }

        $model->$column += 1;
        
        $attributes = array($column);
        if ($model->getCanShow()) {
            $model->state = DPost::STATE_ENABLED;
            $model->create_time = $_SERVER['REQUEST_TIME'];
            $attributes[] = 'create_time';
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