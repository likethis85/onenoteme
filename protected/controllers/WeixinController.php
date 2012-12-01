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
        $duration = 24 * 60 * 60;
        $maxID = app()->getDb()->cache($duration)->createCommand()
            ->select('max(id)')
            ->from(TABLE_POST)
            ->queryScalar();
    
        $random = mktime(mt_rand(0,23), mt_rand(0, 59), mt_rand(0, 59), mt_rand(0, 11), mt_rand(0, 30), mt_rand(2011, 2012));
        $cmd = app()->getDb()->createCommand()
            ->select('id')
            ->from(TABLE_POST)
            ->where(array('and', 'state = :enabled', 'channel_id = 0', 'create_time > :random'), array(':enabled' => POST_STATE_ENABLED, ':random' => $random));
        $id = $cmd->queryScalar();
    
        if (empty($id))
            echo 'null';
    
        echo $id;
    }
}



