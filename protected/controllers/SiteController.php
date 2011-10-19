<?php
class SiteController extends DController
{
    public function actionIndex()
    {
        
        $this->render('site/index', array(
            'data' => time(),
        ));
        
    }
    
    public function actionTest()
    {
        var_dump(app()->cache);
        var_dump(app()->db);
        exit;
        
        echo param('myname');
        exit;
        echo app()->getStaticBasePath();
        exit;
        echo time() . '<hr />';
        throw new DException(Cdc::t('cdc','Application base path "{path}" is not a valid directory.',
				array('{path}'=>'/ba')));

        echo __FILE__ . '<hr />' . __FUNCTION__;
    }
}
