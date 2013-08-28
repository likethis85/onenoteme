<?php
class AdController extends RestController
{
    public function filters()
    {
        return array(
            'postOnly + click',
        );
    }
    
    public function actionClick()
    {
        $this->output($_REQUEST);
    }
}