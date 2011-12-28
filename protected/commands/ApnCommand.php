<?php
class ApnCommand extends CConsoleCommand
{
    public function actionMessageNotice()
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
            try {
                $apn->createNote($token, $message, $count['all'], '', $others)->send();
                self::updateLastTime($token);
            }
            catch (Exception $e) {
                echo $e->getMessage() . "\n";
            }
        }
        $apn->close();
    }
    
    public function actionBadgeNotice()
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
            
            $others = array('category_count' => $count);
            try {
                $apn->createNote($token, '', $count['all'], '', $others)->send();
            }
            catch (Exception $e) {
                echo $e->getMessage() . "\n";
            }
        }
        $apn->close();
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
    
    private static function updateLastTime($token)
    {
        app()->getDb()->createCommand()
            ->update('{{device}}', array('last_time'=>$_SERVER['REQUEST_TIME']), 'device_token = :token', array(':token'=>$token));
    }

}