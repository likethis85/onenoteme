<?php
class AboutController extends MobileController
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
                'duration' => 3600,
            ),
        );
    }
    
    public function actionIndex()
    {
        $this->setSitePageTitle('关于我们');
        $this->channel = 'about';
        $this->render('index');
    }
}