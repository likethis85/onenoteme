<?php
class SiteController extends Controller
{
    public function filters()
    {
        return array(
            array(
                'COutputCache + bdmap, sitemap, links',
                'duration' => 600,
                'varyByParam' => array('page'),
            ),
            array(
                'COutputCache + index',
                'duration' => 120,
                'varyByParam' => array('page', 's', 'f'),
                'varyByExpression' => 'user()->getIsGuest() && CDBase::isMobileDevice()',
                'requestTypes' => array('GET'),
            ),
            array(
                'COutputCache + index',
                'duration' => 120,
                'varyByParam' => array('page', 's'),
                'requestTypes' => array('POST'),
            ),
        );
    }
    
    public function actionIndex($page = 1, $s = POST_LIST_STYLE_LINE, $f = 0)
    {
        $mobileUrl = aurl('mobile/default/index', array('page'=>$page));
        $this->autoSwitchMobile($mobileUrl);
        
        $s = strip_tags(trim($s));
        $limit = ($s == POST_LIST_STYLE_WATERFALL) ? param('waterfall_post_count_page') : param('line_post_count_page');
        
        $channels = array(CHANNEL_DUANZI, CHANNEL_LENGTU);
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(array('t.state'=>POST_STATE_ENABLED));
        
        if ($s != POST_LIST_STYLE_WATERFALL)
            $channels[] = CHANNEL_VIDEO;
        
        $criteria->addInCondition('channel_id', $channels);
        $criteria->order = 't.istop desc, t.create_time desc, t.id desc';
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
        
        $this->channel = 'home';
        if (request()->getIsAjaxRequest()) {
            $view = ($s == POST_LIST_STYLE_WATERFALL) ? '/post/mixed_list' : '/post/line_list';
            $this->renderPartial($view, array(
                'models' => $models,
                'pages' => $pages,
            ));
        } else {
            $this->pageTitle = param('sitename') . ' - ' . param('shortdesc');
            $this->setKeywords(param('home_index_keywords'));
            $this->setDescription(param('home_index_description'));
            
            if ($page > 1)
                $mobileUrl = aurl('mobile/default/index');
            else
                $mobileUrl = aurl('mobile/default/index', array('page'=>$page));
            cs()->registerMetaTag('format=html5;url=' . $mobileUrl, null, 'mobile-agent');
            
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
            if (empty($returnUrl)) $returnUrl = CDBase::memberHomeUrl();
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
                $returnUrl = CDBase::memberHomeUrl();
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
    
    public function actionQuickLogin()
    {
        $model = new LoginForm('quicklogin');
        if (request()->getIsPostRequest() && request()->getIsAjaxRequest() && isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            if ($model->validate() && $model->login(false)) {
                $data['errno'] = CD_NO;
                $data['html'] = $this->userToolbar();
            }
            else {
                $data['errno'] = CD_YES;
                $data['error'] = $model->getErrors();
            }
            
            echo CJSON::encode($data);
            exit(0);
        }
        
        $this->renderPartial('quick_login', array('form'=>$model));
        exit(0);
    }
    
    public function actionSignup()
    {
        if (!user()->getIsGuest()) {
            $this->redirect(CDBase::memberHomeUrl());
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

    public function actionBdmap()
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
            ->order('id desc')
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

    public function actionError()
    {
        $error = app()->errorHandler->error;
        if ($error) {
            if (request()->getIsAjaxRequest()) {
                $data['code'] = $error['code'];
                $data['message'] = $error['message'];
                echo CJSON::encode($data);
                exit(0);
            }
            else {
                $this->layout = 'error';
                $this->pageTitle = '错误 ' . $error['code'] . ' - ' . app()->name;
                $this->render('/system/error', $error);
            }
        }
    }
    
    public function actionTest()
    {
        exit;
        
        $filename = fbp('abc.png');
        $data = file_get_contents($filename);
        $im = new CDImage($data);
        
        $text = '挖段子';
        $font = Yii::getPathOfAlias('application.fonts') . DS . 'msyh.ttf';
        $color = array(200, 200, 200);
        $im->text($text, $font, 24, CDImage::MERGE_BOTTOM_LEFT, $color);
        $im->text('http://www.waduanzi.com', $font, 12, CDImage::MERGE_BOTTOM_RIGHT, $color);
        $im->saveAsPng(app()->runtimePath . DS . 'aaaa');
        $im->revert();
        $im->text($text.'网', $font, 24, CDImage::MERGE_BOTTOM_LEFT, $color);
        $im->text('http://www.waduanzi1.com', $font, 12, CDImage::MERGE_BOTTOM_RIGHT, $color);
        $im->saveAsPng(app()->runtimePath . DS . 'bbbb');
        $im->output();
        
        
        
        exit;
        echo uniqid() . '<br />';
        echo md5('yaoyiyao phonebook') . '<br />';
        
        $thumbWidth = IMAGE_THUMBNAIL_WIDTH;
        $thumbHeight = IMAGE_THUMBNAIL_HEIGHT;
        
        $url = 'http://www.fanjian.net/upload/201210231243255284.jpg';
        $images = CDBase::saveRemoteImages($url, $thumbWidth, $thumbHeight, true);
        var_dump($images);
    }
}
