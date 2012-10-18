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
                'varyBySession' => true,
                'varyByParam' => array('page', 's'),
                'requestTypes' => array('GET'),
            ),
            array(
                'COutputCache + index',
                'duration' => $duration,
                'varyBySession' => true,
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
    
    public function actionIndex($s = POST_LIST_STYLE_GRID)
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
        $this->setKeywords('挖段子,挖笑话,挖冷图,挖福利,精品笑话,网络趣图，内涵段子,内涵图,邪恶漫画,趣图,笑话大全 爆笑笑话,黄色笑话,幽默笑话,成人笑话,经典笑话,笑话短信,幽默笑话大全,夫妻笑话,笑话集锦,搞笑笑话,荤笑话,极品笑话,黄段子,爆笑短信,美女写真,美女图片,美女写真,性感美女,清纯少女,大学校花,淘女郎,微女郎');
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
        if (!user()->getIsGuest()) {
            $returnUrl = strip_tags(trim($url));
            if (empty($returnUrl)) $returnUrl = aurl('site/index'); // @todo 如果有了用户中心，这里应该跳转到用户中心
            request()->redirect($returnUrl);
            exit(0);
        }
        
        
        $model = new LoginForm('login');
        if (request()->getIsPostRequest() && isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            if ($model->validate() && $model->login())
                ;
            else
                $model->captcha = '';
        }
        else {
            $returnUrl = strip_tags(trim($url));
            if (empty($returnUrl))
                $returnUrl = request()->getUrlReferrer();
            if (empty($returnUrl))
                $returnUrl = aurl('site/index'); // @todo 如果有了用户中心，这里应该跳转到用户中心
            $model->returnUrl = urlencode($returnUrl);
        }
        
        cs()->registerMetaTag('noindex, follow', 'robots');
        $this->pageTitle = '登录' . app()->name;
        
        $this->render('login', array('form'=>$model));
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
        if (!user()->getIsGuest()) {
// @todo 如果有了用户中心，这里应该跳转到用户中心
//             $this->redirect(aurl('user/default'));
            $this->redirect(aurl('site/index'));
            exit(0);
        }
        
        
        $model = new LoginForm('signup');
        if (request()->getIsPostRequest() && isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            if ($model->validate() && $model->signup())
                ;
            else
                $model->captcha = '';
        }
        
        cs()->registerMetaTag('noindex, follow', 'robots');
        $this->pageTitle = '注册成为' . app()->name . '会员';
        
        $this->render('signup', array('form'=>$model));
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
