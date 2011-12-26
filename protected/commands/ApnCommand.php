<?php
class ApnCommand extends CConsoleCommand
{
    public function actionIndex()
    {
        $devices = app()->getDb()->createCommand()
            ->from('{{device}}')
            ->order('last_time desc')
            ->queryAll();
        
        if (empty($devices))
            return false;
        
        $apn = app()->apn->connect();
        foreach ($devices as $device) {
            $token = $device['device_token'];
            $lasttime = (int)$device['last_time'];
            $count = self::fetchUpdateCount($lasttime);
            
            if ($count['all'] === 0) continue;
            
            $message = sprintf('挖段子刚刚更新%d个精品笑话段子，不要错过哦。', $count['all']);
            $others = array('category_count' => $count);
            $apn->createNote($token, $message, $count['all'], '', $others)->send();
        }
        $apn->close();
        
        
//         $token = '7e88716c b7323807 515b8a12 03fe41e3 71382da5 77d55caa 991db8b4 f0610f44';
//         $apn = app()->apn->createNote($token, '妈了个XX的', 1, '', $others)->connect()->send()->close();
        
    }
    
    private static function fetchUpdateCount($lasttime)
    {
        $lasttime = (int)$lasttime;
        
        $categories = array(1, 2, 3, 4);
        foreach ($categories as $id) {
            $count[$id] = app()->getDb()->createCommand()
                ->select('count(*)')
                ->from('{{post}}')
                ->where('category_id = :cid and state != :invalid and create_time > :lasttime', array(
                    ':cid'=>$id,
                    ':invalid'=>Post::STATE_DISABLED,
                    ':lasttime'=>$lasttime
                ))
                ->queryScalar();
        }
        
        $count['all'] = (int)array_sum($count);
        
        return $count;
    }
    
    public function actionSendpush()
    {
        echo __FILE__;
    }
}