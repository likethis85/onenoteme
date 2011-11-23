<?php
class PhoneController extends Controller
{
    public function actionLatest()
    {
        $offset = $_GET['start'] ? (int)$_GET['start'] : 0;
        $limit = $_GET['limit'] ? (int)$_GET['limit'] : 10;
        $where = 't.state != :state';
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
        $offset = $_GET['start'] ? (int)$_GET['start'] : 0;
        $limit = $_GET['limit'] ? (int)$_GET['limit'] : 10;
        
        $date = new DateTime();
        $date->sub(new DateInterval($interval));
        $time = $date->getTimestamp();
        
        $where = 't.state != :state and create_time > :createtime';
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
        $offset = $_GET['start'] ? (int)$_GET['start'] : 0;
        $limit = $_GET['limit'] ? (int)$_GET['limit'] : 10;
        $where = 't.state != :state and category_id = :cid';
        $params = array(':state' => DPost::STATE_DISABLED, ':cid'=>$cid);
        $cmd = app()->db->createCommand()
        ->from('{{post}} t')
        ->order('t.up_score desc, t.id desc')
        ->limit($limit)
        ->offset($offset)
        ->where($where, $params);
    
        $rows = $cmd->queryAll();
        self::output($rows);
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