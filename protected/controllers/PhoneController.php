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
        self::versionUpdateAlert();
        exit;
        
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
        self::versionUpdateAlert();
        exit;
        
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
        array_unshift($rows, self::versionALertArray((int)$rows[0]['id'] + 1));
    
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
        array_unshift($rows, self::versionALertArray((int)$rows[0]['id'] + 1));
        
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

    private static function versionUpdateAlert()
    {
        $str = '[{"id":"20000","channel_id":"0","category_id":"20","title":"\u5bf9\u4e0d\u8d77\uff0c\u6211\u4eec\u5bf91.0\u7248\u672c\u5df2\u7ecf\u4e0d\u518d\u652f\u6301\uff0c\u5f53\u524d\u6700\u65b0\u7248\u672c\u4e3a2.1.0\uff0c\u6700\u65b0\u7248\u672c\u5185\u5bb9\u66f4\u591a\u3001\u66f4\u65b0\u901f\u5ea6\u66f4\u5feb\uff0c\u3001\u4f7f\u7528\u8d77\u6765\u66f4\u52a0\u65b9\u4fbf\uff0c\u6211\u4eec\u5f3a\u70c8\u63a8\u8350\u60a8\u9a6c\u4e0a\u66f4\u65b0","pic":"","big_pic":"","create_time":"1334455200","up_score":"17","down_score":"2","comment_nums":"0","user_id":"0","user_name":"","tags":"","state":"1"}]';
        echo $str;
        exit(0);
    }
    
    private static function versionALertArray($lastid)
    {
        return array (
          'id' => "$lastid",
          'channel_id' => '0',
          'category_id' => '20',
          'title' => '对不起，我们对1.0版本已经不再支持，当前最新版本为2.1.0，最新版本内容更多、更新速度更快，、使用起来更加方便，我们强烈推荐您马上更新',
          'pic' => '',
          'big_pic' => '',
          'create_time' => '1334455200',
          'up_score' => '17',
          'down_score' => '2',
          'comment_nums' => '0',
          'user_id' => '0',
          'user_name' => '',
          'tags' => '',
          'state' => '1',
        );
        
    }
}



