<?php
class AboutController extends Controller
{
    public function init()
    {
        parent::init();
        $this->layout = 'about';
    }
    
    public function filters()
    {
        return array(
            array(
                'COutputCache',
                'duration' => 3600 * 24,
            ),
        );
    }
    
    public function actionIndex()
    {
        $this->setSitePageTitle('关于我们');
        $this->channel = 'about';
        $this->render('index');
    }
    
    public function actionContact()
    {
        $this->setSitePageTitle('联系我们');
        $this->channel = 'contact';
        $this->render('contact');
    }
    
    public function actionPolicy()
    {
        $this->setSitePageTitle('免责声明');
        $this->channel = 'policy';
        $this->render('policy');
    }
}