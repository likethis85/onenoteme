<?php
class SiteController extends Controller
{
    public function actions()
    {
        return array_merge(parent::actions(), array(
            'page' => array(
                'class' => 'CViewAction',
            ),
        ));
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
    
    public function actionIndex()
    {
        if ($this->checkUserAgentIsMobile())
            $this->redirect(aurl('mobile'));
        
        $this->pageTitle = '挖段子';
//        $this->setKeywords('经典语录,糗事百科,秘密,笑话段子,笑话大全,搞笑大全,我们爱讲冷笑话,哈哈笑');
//        $this->setDescription('网罗互联网各种精品段子，各种糗事，各种笑话，各种秘密，各种经典语录，应有尽有。烦了、累了、无聊了，就来挖段子逛一逛。');
        $this->forward('post/live');
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
    
    public function actionTest()
    {
        for ($i=0; $i<10000; $i++) {
            $d = mt_rand(0, 20000);
            $id[] = $d;
            echo $d . '<br />';
            if (count($id) >=30) break;
        }
        exit;
        echo md5('i am ios secret key') . '<br />';
        echo md5('i am chrome secret key') . '<br />';
        echo uniqid() . '<br />';
        echo uniqid() . '<br />';
        
        exit;
        $url = 'http://img.1626.com/images/userup/1201/511201111823251.jpg';
        $curl = new CdCurl();
        $curl->get($url);
        $data = $curl->rawdata();
        $curl->close();
        $im = new CdImage();
        $im->load($data);
//         $im->crop(640, 960);
        $filename = app()->runtimePath . '/640';
        $im->saveAsJpeg($filename, 50);
        
        exit;
        
        $this->render('test');
    }
}
