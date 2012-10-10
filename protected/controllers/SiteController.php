<?php
class SiteController extends Controller
{
    public function filte2rs()
    {
        $duration = 300;
        return array(
            array(
                'COutputCache + baidumap, sitemap, links',
                'duration' => $duration,
            ),
            array(
                'COutputCache + index',
                'duration' => $duration,
                'varyByParam' => array('page', 's'),
                'requestTypes' => array('GET'),
            ),
            array(
                'COutputCache + index',
                'duration' => $duration,
                'varyByParam' => array('page', 's'),
                'requestTypes' => array('POST'),
            ),
        );
    }
    
    private function checkUserAgentIsMobile()
    {
        $agents = array('android', 'iphone', 'blackberry', 'webos', 'windows phone');
        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        
        foreach ($agents as $v)
            if (strpos($agent, $v))
                return true;
        
        return false;
    }
    
    public function actionIndex($s = POST_LIST_STYLE_WATERFALL)
    {
        if ($this->checkUserAgentIsMobile())
            $this->redirect(aurl('mobile'));
        
        $s = strip_tags(trim($s));
        $limit = ($s == POST_LIST_STYLE_WATERFALL) ? param('waterfall_post_count_page') : param('grid_post_count_page');
        
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(array('state'=>POST_STATE_ENABLED));
        if ($s == POST_LIST_STYLE_WATERFALL)
            $criteria->addCondition('channel_id != '. CHANNEL_VIDEO);
        $criteria->order = 'create_time desc, id desc';
        $criteria->limit = $limit;
        
        $countDuration = 60*60*24;
        $count = Post::model()->cache($countDuration)->count($criteria);
        $pages = new CPagination($count);
        $pages->setPageSize($limit);
        $pages->applyLimit($criteria);

        if ($pages->getCurrentPage() < $_GET[$pages->pageVar]-1)
            $models = array();
        else
            $models = Post::model()->findAll($criteria);
        
        $this->pageTitle = '挖段子 - 笑死人不偿命 - 每日精品笑话连载';
        $this->setKeywords('挖笑话,挖冷图,挖福利,每日精品笑话连载,网络趣图，漫画,邪恶漫画,趣图百科,暴走漫画连载,阳光正妹,爱正妹,糗事百科,笑话大全 爆笑,黄色笑话,幽默笑话,成人笑话,经典笑话,笑话短信,爆笑笑话,幽默笑话大全,夫妻笑话,笑话集锦,搞笑笑话,荤笑话,极品笑话,黄段子,爆笑短信,最新笑话,最全的笑话,经典语录,糗事百科,秘密,笑话段子,经典笑话,笑话大全,搞笑大全,我们爱讲冷笑话,哈哈笑');
        $this->setDescription('最新发布的段子，每日精品笑话连载。网罗互联网各种精品段子，各种糗事，各种笑话，各种秘密，各种经典语录，各种有趣的图片，各种漂亮mm校花模特正妹，应有尽有。烦了、累了、无聊了，就来挖段子逛一逛。');
        
        $this->channel = 'home';
        if (request()->getIsAjaxRequest()) {
            $view = ($s == POST_LIST_STYLE_WATERFALL) ? '/post/mixed_list' : '/post/grid_list';
            $this->renderPartial($view, array(
                'models' => $models,
                'pages' => $pages,
            ));
        } else {
            $view = ($s == POST_LIST_STYLE_WATERFALL) ? 'fall_index' : 'grid_index';
            $this->render($view, array(
                'models' => $models,
                'pages' => $pages,
            ));
        }
    }
    
    public function actionLogin($url = '')
    {
        $returnUrl = strip_tags(trim($url));
        if (empty($returnUrl)) $returnUrl = app()->homeUrl;
        
        if (!user()->isGuest) {
            request()->redirect($returnUrl);
        }
        
        $model = new LoginForm();
        if (request()->getIsPostRequest() && isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            if ($model->validate()) {
                $model->login();
                $this->redirect($returnUrl);
            }
        }
        $this->pageTitle = '登录' . app()->name;
        $this->setKeywords($this->pageTitle);
        $this->setDescription('登录' . app()->name . '后，可以发表评论、投稿及审核段子。');
        $this->render('login', array('model'=>$model));
    }
    
    public function actionLogout()
    {
        if (user()->getIsGuest())
            $this->redirect(user()->returnUrl);
        else {
            user()->logout();
            $returnUrl = request()->getUrlReferrer() ? request()->getUrlReferrer() : user()->returnUrl;
            $this->redirect($returnUrl);
        }
    }
    
    public function actionSignup()
    {
        if (!user()->isGuest) {
            $this->redirect(user()->returnUrl);
        }
        
        $model = new SignupForm();
        if (request()->getIsPostRequest() && isset($_POST['SignupForm'])) {
            $model->attributes = $_POST['SignupForm'];
            if ($model->validate()) {
                $user = $model->createUser();
                if ($user !== false)
                    user()->loginRequired();
            }
        }
        $this->pageTitle = '注册成为' . app()->name . '会员';
        $this->setKeywords($this->pageTitle);
        $this->setDescription('注册成为' . app()->name . '会员后，可以发表评论、投稿及审核段子。');
        $this->render('signup', array('model'=>$model));
    }

    public function actionBaidumap()
    {
        $pageSize = 30;
        $duration = 3600 * 24;
        $count = app()->getDb()->cache($duration)->createCommand()
            ->select('count(*)')
            ->from(TABLE_POST)
            ->where('state = :enabled', array(':enabled' => POST_STATE_ENABLED))
            ->queryScalar();
        
        $pages = new CPagination($count);
        $pages->setPageSize($pageSize);
        $offset = ($pages->getCurrentPage() - 1) * $pageSize;
        
        $cmd = app()->getDb()->cache($duration)->createCommand()
            ->select('id, title, content, tags')
            ->from(TABLE_POST)
            ->where('state = ' . POST_STATE_ENABLED)
            ->order('id asc')
            ->limit($pageSize)
            ->offset($offset);
        
        $posts = $cmd->queryAll();
        
        $this->render('baidumap', array(
            'posts' => $posts,
            'pages' => $pages,
        ));
    }
    
    public function actionSitemap()
    {
        $cmd = app()->getDb()->createCommand()
            ->select('id')
            ->from(TABLE_POST)
            ->where('state = ' . POST_STATE_ENABLED)
            ->order('id desc')
            ->limit(2000);
        $posts = $cmd->queryAll();
        $this->renderPartial('sitemap', array(
            'posts' => $posts
        ));
        app()->end();
    }

    public function actionLinks()
    {
        $this->render('links');
    }

    public function actionTest()
    {
        echo uniqid() . '<br />';
        echo md5('yaoyiyao phonebook');
    }
}
