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
    
        $min = mt_rand(0, intval($maxID/2));
        $max = mt_rand(intval($maxID/2), $maxID);
        $randomID = mt_rand($min, $max);
        $cmd = app()->getDb()->createCommand()
            ->select('id')
            ->from(TABLE_POST)
            ->where(array('and', 'state = :enabled', 'channel_id = 0', 'id > :randomID'), array(':enabled' => POST_STATE_ENABLED, ':randomID' => $randomID));
        $id = $cmd->queryScalar();
    
        if (empty($id))
            echo 'null';
    
        echo $id;
    }
}



