<?php
class SiteController extends Controller
{
    public function actionIndex()
    {
        
        $this->render('index', array(
            'data' => time(),
        ));
        
    }
    
    public function actionLogin()
    {
        
    }
    
    public function actionLogout()
    {
        
    }
    
    public function signup()
    {
        
    }
    
    public function actionTest()
    {
        header('Content-Type: text/html; charset=utf-8');
        
        $model = DCategory::model()->findByPk(1);
        $result = $model->delete();
        
        var_dump($result);
    }
}
