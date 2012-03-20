<?php
class AppapiController extends Controller
{
    const DEFAULT_LATEST_POST_MAX_COUNT = 50;
    const DEFAULT_RECOMMEND_POST_COUNT = 15;

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
        $rows = self::processRows($rows);
        self::output($rows);
    }
    
    public function actionLatest($lastid = 0, $channelid = 0, $device_token = '')
    {
        $lastid = (int)$lastid;
        $channelid = (int)$channelid;
    
        $where = "t.state != :state and id > :lastid and channel_id = :channelid";
        $params = array(':state' => DPost::STATE_DISABLED, ':lastid'=>$lastid, ':channelid'=>$channelid);

        $cmd = app()->db->createCommand()
            ->from('{{post}} t')
            ->order('t.id desc')
            ->limit(self::DEFAULT_LATEST_POST_MAX_COUNT)
            ->where($where, $params);
    
        $rows = $cmd->queryAll();
    
        // 更新最后请求时间
        self::updateLastRequestTime($device_token);
        $rows = self::processRows($rows);
        self::output($rows);
    }
    
    public function actionChannel($channelid, $offset = 0, $limit = self::DEFAULT_RECOMMEND_POST_COUNT)
    {
        $channelid = (int)$channelid;
        $offset = (int)$offset;
        $limit = (int)$limit;
        $limit = $limit ? $limit : self::DEFAULT_RECOMMEND_POST_COUNT;
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
        $rows = self::processRows($rows);
        self::output($rows);
    }
    
    public function actionRandom($channelid = -1, $limit = self::DEFAULT_RECOMMEND_POST_COUNT)
    {
        $offset = (int)$offset;
        $limit = (int)$limit;
        $limit = $limit ? $limit : self::DEFAULT_RECOMMEND_POST_COUNT;
        $offset = $offset ? $offset : 0;
        
        $channelid = (int)$channelid;
        if ($channelid === -1) {
            $where = "t.state != :state";
            $params = array(':state' => DPost::STATE_DISABLED);
        }
        else {
            $where = "t.state != :state and channel_id = :channelid";
            $params = array(':state' => DPost::STATE_DISABLED, ':channelid'=>$channelid);
        }
        
        $maxIdMinId = app()->getDb()->createCommand()
            ->select(array('max(id) maxid', 'min(id) minid'))
            ->from('{{post}} t')
            ->queryRow();
        
//         print_r($maxIdMinId);
        
        $minid = $maxIdMinId['minid'];
        $maxid = $maxIdMinId['maxid'];
        for ($i=0; $i<50; $i++)
            $randomIds[] = mt_rand($minid, $maxid);
        
        $ids = array_unique($randomIds);
//         print_r($ids);exit;
        $where = array('and', array('in', 'id', $ids), $where);
        
        $cmd = app()->db->createCommand()
            ->from('{{post}} t')
            ->order('t.id desc')
            ->limit($limit)
            ->where($where, $params);
    
        $rows = $cmd->queryAll();
        $rows = self::processRows($rows);
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
    
    private static function processRows($rows)
    {
        if (empty($rows) || !is_array($rows))
            return $rows;
        
        foreach ($rows as $index => $row) {
            $row['create_time_text'] = date(param('formatShortDateTime'), (int)$row['create_time']);
            $row['visit_count'] = '阅:' . $row['comment_nums'];
            $row['comment_count'] = '评:' . $row['comment_nums'];
            $row['support_count'] = '顶:' . $row['up_score'];
            $row['oppose_count'] = '踩:' . $row['down_score'];
            
            if (!empty($row['pic']))
                $thumbnail = $row['pic'];
            elseif (!empty($row['big_pic']))
                $thumbnail = $row['big_pic'];
            else
                $thumbnail = '';
            $row['thumbnail'] = $thumbnail;
            $rows[$index] = $row;
            
        }
        
        return $rows;
    }
    
    
    private static function output($rows)
    {
        $format = strtolower(trim($_REQUEST['format']));
        
        if ($format === 'jsonp') {
            header('Content-Type: application/javascript');
            echo $_GET['callback'] . '(' . CJSON::encode($rows) . ')';
        }
        else {
            header('Content-Type: application/json');
            echo CJSON::encode($rows);
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



