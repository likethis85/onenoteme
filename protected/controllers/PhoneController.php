<?php
class PhoneController extends Controller
{
    
    public function actionNew($lastid, $cid = 0)
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
        self::output($rows);
    }
    
    public function actionNewTest($lastid, $cid = 0)
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
        self::output($rows);
    }
    
    public function actionLatest()
    {
        $page = $_GET['page'] ? (int)$_GET['page'] : 1;
        $limit = $_GET['limit'] ? (int)$_GET['limit'] : 10;
        $offset = ($page - 1) * $limit;
        $where = "t.state != :state and pic = ''";
        $params = array(':state' => DPost::STATE_DISABLED);
        $cmd = app()->db->createCommand()
            ->from('{{post}} t')
            ->order('t.create_time desc, t.id desc')
            ->limit($limit)
            ->offset($offset)
            ->where($where, $params);
        
        $rows = $cmd->queryAll();
        self::output($rows);
    }
    
    public function actionHottest($interval)
    {
        $page = $_GET['page'] ? (int)$_GET['page'] : 1;
        $limit = $_GET['limit'] ? (int)$_GET['limit'] : 10;
        $offset = ($page - 1) * $limit;
        
        $date = new DateTime();
        $date->sub(new DateInterval($interval));
        $time = $date->getTimestamp();
        
        $where = "t.state != :state and create_time > :createtime and pic = ''";
        $params = array(':state' => DPost::STATE_DISABLED, ':createtime' => $time);
        $cmd = app()->db->createCommand()
        ->from('{{post}} t')
        ->order('t.up_score desc, t.id desc')
        ->limit($limit)
        ->offset($offset)
        ->where($where, $params);
    
        $rows = $cmd->queryAll();
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
    
    public function actionDeviceToken()
    {
        if (request()->getIsPostRequest() && isset($_POST)) {
            $token = trim($_POST['device_token']);
            if (empty($token))
                $result = -1;
            else {
                $model = Token::model()->findByAttributes(array('device_token'=>$token));
                if ($model === null) {
                    $model = new Token();
                    $model->device_token = $token;
                    $result = (int)$model->save();
                }
                else
                    $result = 2;
            }
        }
        else
            $result = -2;
        
        $filename = app()->runtimePath . DS . 'result.log';
        file_put_contents($filename, $result);
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
}