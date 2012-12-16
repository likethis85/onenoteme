<?php
class WeixinController extends Controller
{
    public function actionIndex()
    {
        $token = 'waduanzi.com';
        $weixin = new WdzWeixin($token);
        $weixin->run();
        exit(0);
    }
    
    public function actionRandom()
    {
        exit;
        $id = 'wxlastid_' . $data->FromUserName;
        $lastID = app()->fcache()->get($id);
        $cmd = app()->getDb()->createCommand()
            ->select('id')
            ->from(TABLE_POST)
            ->where(array('and', 'state = :enabled', 'channel_id = 0', 'id > :lastID'), array(':enabled' => POST_STATE_ENABLED, ':lastID' => $lastID));
        $pid = $cmd->queryScalar();
    
        if (empty($pid))
            echo 'null';
    
        app()->fcache()->set($id, $pid);
        
        echo $pid;
    }
}



