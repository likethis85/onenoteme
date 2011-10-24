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
