<?php
/**
 * 手机应用2.0专用接口
 * @author chendong
 *
 */
class Phone2Controller extends Controller
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
        $lastid = (int)$lastid;
        $channelid = (int)$channelid;
    
        $where = "t.state != :state and id > :lastid and channel_id = :channelid";
        $params = array(':state' => DPost::STATE_DISABLED, ':lastid'=>$lastid, ':channelid'=>$channelid);

        $cmd = app()->db->createCommand()
            ->from('{{post}} t')
            ->order('t.id desc')
            ->limit(self::DEFAULT_RECOMMEND_POST_COUNT)
            ->where($where, $params);
    
        $rows = $cmd->queryAll();
    
        // 更新最后请求时间
        self::updateLastRequestTime($device_token);
        $rows = self::processRows($rows);
        shuffle($rows);
        self::output($rows);
        exit;
        
        ////////////////////////////////////
        
        
        $limit = (int)$limit;
        $limit = $limit ? $limit : self::DEFAULT_RECOMMEND_POST_COUNT;
        
        $channelid = (int)$channelid;
        $maxIdMinId = app()->getDb()->createCommand()
            ->select(array('max(id) maxid', 'min(id) minid'))
            ->from(TABLE_NAME_POST)
            ->where(array('and', 't.state = :enalbed'), array(':enalbed' => Post::STATE_ENABLED))
            ->queryRow();
        $minid = $maxIdMinId['minid'];
        $maxid = $maxIdMinId['maxid'];
        
        if ($channelid === -1) {
            $conditoin = array('and', 't.state != :state', 'id = :randid');
            $param = array(':state' => DPost::STATE_DISABLED, ':randid'=>0);
        }
        else {
            $conditoin = array('and', 't.state != :state', 'channel_id = :channelid', 'id = :randid');
            $param = array(':state' => DPost::STATE_DISABLED, ':channelid'=>$channelid, ':randid'=>0);
        }
        
        $rows = array();
        for ($i=0; $i<$maxid; $i++) {
            $randid = mt_rand($minid, $maxid);
            $param['randid'] = $randid;
            $cmd = app()->getDb()->createCommand()
                ->from(TABLE_NAME_POST)
                ->where($conditoin, $param)
                ->limit(1);
        
            $row = $cmd->queryRow();
            if ($row === false || array_key_exists($row['id'], $rows))
                continue;
            else
                $rows[$row['id']] = $row;
        
            if (count($rows) >= $limit)
                break;
        }
        
        $rows = self::processRows($rows);
        $rows = array_values($rows);
        
        self::output($rows);
    }
    
    public function actionDeviceToken()
    {
        if (request()->isPostRequest && isset($_POST)) {
            $uuid = trim($_POST['device_uuid']);
            $token = trim($_POST['device_token']);
            $token = trim($token, '<>');
            $token = str_replace(' ', '', $token);
    
            if (empty($token))
                $data = array('errno'=>-1);
            else {
                $model = Device::model()->findByAttributes(array('device_token'=>$token));
                if ($model === null) {
                    $model = new Device();
                    $model->device_token = $token;
                    $model->uuid = '';
                    $model->last_time = $_SERVER['REQUEST_TIME'];
                    $result = (int)!$model->save();
                }
                else
                    $result = 2;
                
                $data = array('errno'=>$result);
            }
        }
        else
            $data = array('errno'=>-2);
        
        self::output($data);
    }
    
    public function actionSupport($id)
    {
        try {
            $id = (int)$id;
            $counters = array('up_score'=>1);
            $result = Post::model()->updateCounters($counters, 'id=:pid', array(':pid'=>$id));
            $data = array('errno'=>0);
        }
        catch (Exception $e) {
            $data = array('errno'=>1);
        }
        
        self::output($data);
    }
    
    public function actionOppose($id)
    {
        try {
            $id = (int)$id;
            $counters = array('down_score'=>1);
            $result = Post::model()->updateCounters($counters, 'id=:pid', array(':pid'=>$id));
            $data = array('errno'=>0);
        }
        catch (Exception $e) {
            $data = array('errno'=>1);
        }
        
        self::output($data);
    }
    
    public function actionCreateComment()
    {
        if (request()->isPostRequest && isset($_POST)) {
            $postid = (int)$_POST['postid'];
            $content = strip_tags(trim($_POST['content']));
            if (empty($postid))
                $data = array('errno'=>1, 'message'=>'非法操作');
            else {
                $columns = array(
                    'post_id' => $postid,
                    'content' => $content,
                    'create_time' => $_SERVER['REQUEST_TIME'],
                    'create_ip' => CDBase::getClientIp(),
                    'state' => DComment::STATE_ENABLED,
                    'user_id' => 0,
                    'user_name' => '',
                );
                $comment = new Comment();
                $comment->post_id = $postid;
                $comment->content = $content;
                $comment->state = Comment::STATE_ENABLED;
                $comment->user_id = 0;
                $comment->user_name = '';
                $result = $comment->save();
                
                if ($result) {
                    $data = array('errno'=>0);
                }
                else
                    $data = array(
                        'errno' => 1,
                        'message' => '数据库操作错误'
                    );
            }
        }
        else
            $data = array('errno'=>1, 'message'=>'非法操作');
        
        self::output($data);
    }
    
    public function actionComments($postid)
    {
        $commentCount = 10;
        $postid = (int)$postid;
        if (empty($postid)) {
            $data = array('errno' => 1);
            self::output($data);
        }
        else {
            $rows = app()->getDb()->createCommand()
                ->from('{{comment}}')
                ->limit($commentCount)
                ->order('id desc')
                ->where('post_id = :postid', array(':postid'=>$postid))
                ->queryAll();
            
            foreach ($rows as $key => $row) {
                $row['create_time_text'] = date(param('formatShortDateTime'), $row['create_time']);
                $row['content'] = strip_tags(trim($row['content']));
                $rows[$key] = $row;
            }
            self::output($rows);
        }
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
            header('Content-Type: application/javascript; charset=utf-8');
            echo $_GET['callback'] . '(' . CJSON::encode($rows) . ')';
        }
        else {
            header('Content-Type: application/json; charset=utf-8');
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



