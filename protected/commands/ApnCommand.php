<?php
class ApnCommand extends CConsoleCommand
{
    public function actionIndex()
    {
        $token = '7e88716c b7323807 515b8a12 03fe41e3 71382da5 77d55caa 991db8b4 f0610f44';
        $apn = app()->apn->createNote($token, '妈了个XX的', 1, '', $others)->connect()->send()->close();
        
    }
    
    public function actionSendpush()
    {
        echo __FILE__;
    }
}