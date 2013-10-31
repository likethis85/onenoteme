<?php
class PushController extends RestController
{
    public function filters()
    {
        return array(
                'postOnly + bind',
        );
    }
    
    public function actionBind()
    {
        $result = array('errcode' => 0);
        
        $this->output($result);
    }
}