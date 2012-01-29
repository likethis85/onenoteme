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
            $others = array('channel_count' => $count);
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
        
        if (empty($devices)) return false;
        
        $apn = app()->apn->connect();
        foreach ($devices as $device) {
            $token = $device['device_token'];
            $lasttime = (int)$device['last_time'];
            $count = self::fetchUpdateCount($lasttime);
            
            if ($count['all'] === 0) continue;
            
            $others = array('channel_count' => $count);
            try {
                $apn->createNote($token, '', $count['all'], '', $others)->send();
            }
            catch (Exception $e) {
                echo $e->getMessage() . "\n";
            }
        }
        $apn->close();
    }

    public function actionTest()
    {
//         exit;
        $token = '7e88716cb7323807515b8a1203fe41e371382da577d55caa991db8b4f0610f44';
    
        $others = array('channel_count' => array('1'=>'2', '2'=>'3', '3'=>'1', '4'=>'4', 'all'=>'12'));
        $apn = app()->apn->connect();
        try {
            $apn->createNote($token, '', 12, '', $others)->send();
        }
        catch (Exception $e) {
            echo $e->getMessage() . "\n";
        }
        $apn->close();
    }
    
    private static function fetchUpdateCount($lasttime)
    {
        $lasttime = (int)$lasttime;
        
        $channels = array(CHANNEL_DUANZI, CHANNEL_LENGTU, CHANNEL_GIRL, CHANNEL_VIDEO);
        foreach ($channels as $id) {
            $count[$id] = app()->getDb()->createCommand()
                ->select('count(*)')
                ->from('{{post}}')
                ->where('channel_id = :channelid and state != :invalid and create_time > :lasttime', array(
                    ':channelid'=>$id,
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

    public function actionOnce()
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
            $message = '挖段子祝所有段友们龙年吉祥，万事如意，笑口常开，身体健康，我们将会一如既往的为大家呈现更多更精彩的段子。';
            try {
                $apn->createNote($token, $message, 0, '', null)->send();
            }
            catch (Exception $e) {
                echo $e->getMessage() . "\n";
            }
        }
        $apn->close();
    }
}