<?php
class PhoneController extends Controller
{
    public function actionNew($lastid, $cid = 0, $device_token = '')
    {
        if (empty($lastid))
            self::output(array());
        
        $where = "t.state != :state and id > :lastid and pic = ''";
        $params = array(':state' => DPost::STATE_DISABLED, ':lastid'=>$lastid);
        if ($cid > 0) {
            $where .= ' and category_id = :cid';
            $params[':cid'] = $cid;
        }
        $cmd = app()->db->createCommand()
        ->from('{{post}} t')
        ->order('t.id desc')
        ->where($where, $params);
        
        $rows = $cmd->queryAll();
        
        // 更新最后请求时间
        self::updateLastRequestTime($device_token);
        
        self::output($rows);
    }
    
    public function actionNewtest($lastid, $cid = 0, $device_token = '')
    {
        if (empty($lastid))
            self::output(array());
        
        $where = "t.state != :state and id > :lastid";
        $params = array(':state' => DPost::STATE_DISABLED, ':lastid'=>$lastid);
        if ($cid > 0) {
            $where .= ' and category_id = :cid';
            $params[':cid'] = $cid;
        }
        $cmd = app()->db->createCommand()
            ->from('{{post}} t')
            ->order('t.id desc')
            ->where($where, $params);
        
        $rows = $cmd->queryAll();
        
        // 更新最后请求时间
        self::updateLastRequestTime($device_token);
        
        self::output($rows);
    }

    public function actionCategory($cid)
    {
        $cid = (int)$cid;
        $page = $_GET['page'] ? (int)$_GET['page'] : 1;
        $limit = $_GET['limit'] ? (int)$_GET['limit'] : 10;
        $offset = ($page - 1) * $limit;
        
        $where = "t.state != :state and category_id = :cid  and pic = ''";
        $params = array(':state' => DPost::STATE_DISABLED, ':cid'=>$cid);
        $cmd = app()->db->createCommand()
        ->from('{{post}} t')
        ->order('t.id desc')
        ->limit($limit)
        ->offset($offset)
        ->where($where, $params);
    
        $rows = $cmd->queryAll();
        self::output($rows);
    }
    
    public function actionLatest($lastid, $channelid = 0, $device_token = '')
    {
        $lastid = (int)$lastid;
        $channelid = (int)$channelid;
        
        if (empty($lastid))
            self::output(array());
    
        $where = "t.state != :state and id > :lastid and channel_id = :channelid";
        $params = array(':state' => DPost::STATE_DISABLED, ':lastid'=>$lastid, ':channelid'=>$channelid);
        $cmd = app()->db->createCommand()
            ->from('{{post}} t')
            ->order('t.id desc')
            ->where($where, $params);
    
        $rows = $cmd->queryAll();
    
        // 更新最后请求时间
        self::updateLastRequestTime($device_token);
    
        self::output($rows);
    }
    
    public function actionChannel($channelid)
    {
        $channelid = (int)$channelid;
        $page = $_GET['page'] ? (int)$_GET['page'] : 1;
        $limit = $_GET['limit'] ? (int)$_GET['limit'] : 10;
        $offset = ($page - 1) * $limit;
        
        $where = "t.state != :state and channel_id = :channelid";
        $params = array(':state' => DPost::STATE_DISABLED, ':channelid'=>$channelid);
        $cmd = app()->db->createCommand()
            ->from('{{post}} t')
            ->order('t.id desc')
            ->limit($limit)
            ->offset($offset)
            ->where($where, $params);
    
        $rows = $cmd->queryAll();
        self::output($rows);
    }
    
    public function actionLatest16($lastid, $channelid = 0, $device_token = '')
    {
        $lastid = (int)$lastid;
        $channelid = (int)$channelid;
    
        if (empty($lastid))
            self::output(array());
    
        $where = "t.state != :state and id > :lastid and channel_id = :channelid";
        $params = array(':state' => DPost::STATE_DISABLED, ':lastid'=>$lastid, ':channelid'=>$channelid);

        $cmd = app()->db->createCommand()
            ->from('{{post}} t')
            ->order('t.id desc')
            ->where($where, $params);
    
        $rows = $cmd->queryAll();
    
        // 更新最后请求时间
        self::updateLastRequestTime($device_token);
    
        self::output($rows);
    }
    
    public function actionChannel16($channelid, $offset, $limit)
    {
        $channelid = (int)$channelid;
        $offset = (int)$offset;
        $limit = (int)$limit;
        $limit = $limit ? $limit : 10;
        $offset = $offset ? $offset : 0;
        
        $where = "t.state != :state and channel_id = :channelid";
        $params = array(':state' => DPost::STATE_DISABLED, ':channelid'=>$channelid);
        
        $cmd = app()->db->createCommand()
            ->from('{{post}} t')
            ->order('t.id desc')
            ->limit($limit)
            ->offset($offset)
            ->where($where, $params);
    
        $rows = $cmd->queryAll();
        self::output($rows);
    }
    
    public function actionDeviceToken()
    {
        if (request()->getIsPostRequest() && isset($_POST)) {
            $token = trim($_POST['device_token']);
            $token = trim($token, '<>');
            $token = str_replace(' ', '', $token);
            if (empty($token))
                $result = -1;
            else {
                $model = Device::model()->findByAttributes(array('device_token'=>$token));
                if ($model === null) {
                    $model = new Device();
                    $model->device_token = $token;
                    $model->last_time = $_SERVER['REQUEST_TIME'];
                    $result = (int)$model->save();
                }
                else
                    $result = 2;
            }
        }
        else
            $result = -2;
        
        self::output($result);
    }
    
    private static function output($rows)
    {
        $format = strtolower(trim($_REQUEST['format']));
        
        if ($format === 'jsonp') {
            header('Content-Type: text/javascript');
            echo $_GET['callback'] . '(' . json_encode($rows) . ')';
        }
        else {
            header('Content-Type: application/x-json');
            echo json_encode($rows);
        }
        exit(0);
    }

    private static function updateLastRequestTime($deviceToken)
    {
        if (empty($deviceToken))
            return false;
        
        $token = trim($deviceToken, '<>');
        $token = str_replace(' ', '', $token);
        Device::model()->updateAll(array('last_time'=>$_SERVER['REQUEST_TIME']), 'device_token = :token', array(':token'=>$token));
    }
}



