<?php
class SiteController extends Controller
{
    private function checkUserAgentIsMobile()
    {
        $agents = array('android', 'iphone', 'blackberry', 'webos', 'windows phone');
        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        
        foreach ($agents as $v)
            if (strpos($agent, $v))
                return true;
        
        return false;
    }
    
    public function actionIndex()
    {
        if ($this->checkUserAgentIsMobile())
            $this->redirect(aurl('mobile'));
        
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

        if ($pages->getCurrentPage() < $_GET[$pages->pageVar]-1)
            $models = array();
        else
            $models = Post::model()->findAll($criteria);
        
        $this->pageTitle = '挖段子 - 笑死人不尝命 - 每日精品笑话连载';
        $this->setKeywords('每日精品笑话连载,网络趣图，漫画,邪恶漫画,趣图百科,暴走漫画连载,阳光正妹,爱正妹,糗事百科,笑话大全 爆笑,黄色笑话,幽默笑话,成人笑话,经典笑话,笑话短信,爆笑笑话,幽默笑话大全,夫妻笑话,笑话集锦,搞笑笑话,荤笑话,极品笑话,黄段子,爆笑短信,最新笑话,最全的笑话,经典语录,糗事百科,秘密,笑话段子,经典笑话,笑话大全,搞笑大全,我们爱讲冷笑话,哈哈笑');
        $this->setDescription('最新发布的段子，每日精品笑话连载。网罗互联网各种精品段子，各种糗事，各种笑话，各种秘密，各种经典语录，各种有趣的图片，各种漂亮mm校花模特正妹，应有尽有。烦了、累了、无聊了，就来挖段子逛一逛。');
        
        $this->channel = 'home';
        if (request()->getIsAjaxRequest())
            $this->renderPartial('/post/mixed_list', array(
                'models' => $models,
                'pages' => $pages,
            ));
        else
            $this->render('/post/mixed_list', array(
                'models' => $models,
                'pages' => $pages,
            ));
    }
    
    public function actionLogin()
    {
        if (!user()->isGuest) {
            $this->redirect(user()->returnUrl);
        }
        
        $model = new LoginForm();
        if (request()->getIsPostRequest() && isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            if ($model->validate()) {
                $model->login();
                $returnUrl = request()->getUrlReferrer() ? request()->getUrlReferrer() : user()->returnUrl;
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
        
        $model = new User();
        if (request()->getIsPostRequest() && isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            $model->state = param('userIsRequireEmailVerify') ? User::STATE_DISABLED : User::STATE_ENABLED;
            if ($model->save())
                $this->redirect(user()->loginUrl);
        }
        $this->pageTitle = '注册成为' . app()->name . '会员';
        $this->setKeywords($this->pageTitle);
        $this->setDescription('注册成为' . app()->name . '会员后，可以发表评论、投稿及审核段子。');
        $this->render('signup', array('model'=>$model));
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
}
