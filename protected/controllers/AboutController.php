<?php
class AboutController extends Controller
{
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
        
    }
    
    public function actionContact()
    {
        
    }
    
    public function actionPolicy()
    {
        
    }
}