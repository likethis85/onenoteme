<?php
class SiteController extends Controller
{
    public function init()
    {
        $this->layout = 'site';
    }
    
    public function actions()
    {
        return array(
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }
    
    public function actionIndex()
    {
        $this->pageTitle = '挖段子';
        $this->setKeywords('经典语录,糗事百科,秘密,笑话段子,笑话大全,搞笑大全,我们爱讲冷笑话,哈哈笑');
        $this->setDescription('网罗互联网各种精品段子，各种糗事，各种笑话，各种秘密，各种经典语录，应有尽有。烦了、累了、无聊了，就来挖段子逛一逛。');
        $this->forward('post/latest');
    }
    
    public function actionLogin()
    {
        
        $this->render('login');
    }
    
    public function actionLogout()
    {
        
    }
    
    public function actionSignup()
    {
        $this->render('signup');
    }
    
    public function actionTest()
    {
        header('Content-Type: text/html; charset=utf-8');
        $dependency = new CDbCacheDependency('SELECT MAX(id) FROM {{post}}');
        Post::model()->cache(1000, $dependency)->findAll();
        
        var_dump($result);
    }
}
