<?php
class PushController extends RestController
{
    public function actionBind($app_id, $user_id, $channel_id, $request_id)
    {
        $result = array('errcode' => 0);
        
        $this->output($result);
    }
}