<?php
class AccountController extends Controller
{
    public function actionLogin($url = '')
    {
        if (!user()->getIsGuest()) {
            $returnUrl = strip_tags(trim($url));
            if (empty($returnUrl)) $returnUrl = CDBaseUrl::memberHomeUrl();
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
                $returnUrl = CDBaseUrl::memberHomeUrl();
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
    
    public function actionSignup($url = '')
    {
        if (!user()->getIsGuest()) {
            $this->redirect(CDBaseUrl::memberHomeUrl());
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
        else {
            $returnUrl = strip_tags(trim($url));
            if (empty($returnUrl))
                $returnUrl = request()->getUrlReferrer();
            if (empty($returnUrl))
                $returnUrl = CDBaseUrl::memberHomeUrl();
            $model->returnUrl = urlencode($returnUrl);
        }
    
        cs()->registerMetaTag('noindex, follow', 'robots');
        $this->pageTitle = '注册成为' . app()->name . '会员';
    
        $this->render('signup', array('form'=>$model));
    }
    
    public function actionActivate($code)
    {
        $code = trim(strip_tags($code));
        $user = User::emailActivate($code);
        if ($user === false) {
            $data['errno'] = 1;
            $data['message'] = '激活链接已经失效。';
        }
        elseif (user()->getIsGuest()) {
            $identity = new UserIdentity($user->username, $user->password);
            if ($identity->authenticate(true)) {
                user()->login($identity);
                $data['errno'] = 0;
                $data['user'] = $user;
            }
            else {
                $data['errno'] = 1;
                $data['message'] = '激活成功，但您的账号状态不可用，请<a href="'. aurl('help/contact').'" target="_blank">联系客服人员</a>';
            }
        }
        else {
            $data['errno'] = 0;
            $data['user'] = $user;
        }
        $this->setPageTitle('邮箱确认激活');
        $this->render('activate', $data);
    }
}


