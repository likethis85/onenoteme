<?php
/**
 * Post Api接口
 * @author Chris
 * @copyright cdcchen@gmail.com
 * @package api
 */

class Api_Post extends ApiBase
{
    const DEFAULT_TIMELINE_MAX_COUNT = 35;
    const DEFAULT_HISTORY_MAX_COUNT = 35;
    const DEFAULT_RANDOM_MAX_COUNT = 12;
    
    public static function formatRow($row)
    {
        unset($row['video_ur'], $row['state'], $row['tags']);
        if (isset($row['comment_nums']))
            $row['visit_count_text'] = '阅:' . $row['comment_nums'];
        if (isset($row['comment_nums']))
            $row['comment_count_text'] = '评:' . $row['comment_nums'];
        if (isset($row['up_score']))
            $row['support_count_text'] = '顶:' . $row['up_score'];
        if (isset($row['down_score']))
            $row['oppose_count_text'] = '踩:' . $row['down_score'];
        
        if (isset($row['create_time']) && $row['create_time'])
            $row['create_time_text'] = date(param('formatShortDateTime'), $row['create_time']);
        
        if (isset($row['thumbnail_pic']) || isset($row['bmiddle_pic'])) {
            // 这里应该是thumbnail_pic，客户端全部使用的是bmiddle_pic,若换成thumbnail_pic，点击图片后会非常不清楚，所以暂时不使用bmiddle_pic
            $pic = $row['bmiddle_pic'];
            if (empty($pic))
                $pic = $row['bmiddle_pic'];
            
            if (empty($pic))
                $thumbnail = '';
            else {
                if (filter_var($pic, FILTER_VALIDATE_URL) === false){
                    $pic = fbu($pic);
                    $thumbnail = (filter_var($pic, FILTER_VALIDATE_URL) === false) ? $pic : '';
                }
                else
                    $thumbnail = $pic;
            }
            $row['thumbnail'] = $thumbnail;
            unset($row['bmiddle_pic']);
        }
        
        return $row;
    }
    
    public static function formatRows($rows)
    {
        if (empty($rows))
            return array();
        
        foreach ($rows as $index => $row)
            $rows[$index] = self::formatRow($row);
        
        return $rows;
    }
    
    public function timeline()
    {
        self::requiredParams(array('channelid'));
        $params = $this->filterParams(array('channelid', 'count', 'fields', 'lastid', 'token'));
        $channelID = (int)$params['channelid'];
        
        try {
            $fields = empty($params['fields']) ? '*' : $params['fields'];
            $lastid = empty($params['lastid']) ? 0 : (int)$params['lastid'];
            $count = (int)$params['count'];
            if ($count <= 0 || $count > self::DEFAULT_TIMELINE_MAX_COUNT)
                $count = self::DEFAULT_TIMELINE_MAX_COUNT;
            
            $condition = array('and', 'state = :enabled', 'channel_id = :channelid', 'id > :lastid');
            $param = array(':enabled'=>POST_STATE_ENABLED, ':channelid' => $channelID, ':lastid'=>$lastid);
            $cmd = app()->getDb()->createCommand()
                ->select($fields)
                ->from(TABLE_POST . ' t')
                ->where($condition, $param)
                ->order('id desc')
                ->limit($count);
            
            $rows = $cmd->queryAll();
            
            foreach ($rows as $index => $row)
                $rows[$index] = self::formatRow($row);
            
            self::updateLastRequestTime($token);
            
            return $rows;
        }
        catch (Exception $e) {
            throw new ApiException('系统错误', ApiError::SYSTEM_ERROR, $params['debug']);
        }
    }
    
    public function history()
    {
        self::requiredParams(array('channelid', 'beforetime'));
        $params = $this->filterParams(array('channelid', 'count', 'fields', 'beforetime'));
        
        $channelID = (int)$params['channelid'];
        $beforeTime = (int)$params['beforetime'];

        try {
            $fields = empty($params['fields']) ? '*' : $params['fields'];
            $count = (int)$params['count'];
            if ($count <= 0 || $count > self::DEFAULT_HISTORY_MAX_COUNT)
                $count = self::DEFAULT_HISTORY_MAX_COUNT;
        
            $condition = array('and', 'state = :enabled', 'channel_id = :channelid', 'create_time < :beforetime');
            $param = array(':enabled'=>POST_STATE_ENABLED, ':channelid' => $channelID, ':beforetime'=>$beforeTime);
            $cmd = app()->getDb()->createCommand()
                ->select($fields)
                ->from(TABLE_POST . ' t')
                ->where($condition, $param)
                ->order('id desc')
                ->limit($count);
        
            $rows = $cmd->queryAll();
        
            foreach ($rows as $index => $row)
                $rows[$index] = self::formatRow($row);
        
            return $rows;
        }
        catch (Exception $e) {
            throw new ApiException('系统错误', ApiError::SYSTEM_ERROR, $params['debug']);
        }
    }
    
    public function latest()
    {
        self::requiredParams(array('channelid'));
        $params = $this->filterParams(array('channelid', 'count', 'fields', 'lasttime', 'token'));
        $channelID = (int)$params['channelid'];
        
        try {
            $fields = empty($params['fields']) ? '*' : $params['fields'];
            $lasttime = empty($params['lasttime']) ? 0 : (int)$params['lasttime'];
            $count = (int)$params['count'];
            if ($count <= 0 || $count > self::DEFAULT_TIMELINE_MAX_COUNT)
                $count = self::DEFAULT_TIMELINE_MAX_COUNT;
            
            $condition = array('and', 'state = :enabled', 'channel_id = :channelid', 'create_time > :lasttime');
            $param = array(':enabled'=>POST_STATE_ENABLED, ':channelid' => $channelID, ':lasttime'=>$lasttime);
            $cmd = app()->getDb()->createCommand()
                ->select($fields)
                ->from(TABLE_POST . ' t')
                ->where($condition, $param)
                ->order('create_time desc, id desc')
                ->limit($count);
            
            $rows = $cmd->queryAll();
            
            foreach ($rows as $index => $row)
                $rows[$index] = self::formatRow($row);
            
            self::updateLastRequestTime($token);
            
            return $rows;
        }
        catch (Exception $e) {
            throw new ApiException('系统错误', ApiError::SYSTEM_ERROR, $params['debug']);
        }
    }
    
    public function random()
    {
        self::requiredParams(array('channelid'));
        $params = $this->filterParams(array('channelid', 'count', 'fields'));
        $channelID = (int)$params['channelid'];

        try {
            $fields = empty($params['fields']) ? '*' : $params['fields'];
            
            $maxIdMinId = app()->getDb()->createCommand()
                ->select(array('max(id) maxid', 'min(id) minid'))
                ->from(TABLE_POST . ' t')
                ->where(array('and', 't.state = :enalbed',  'channel_id = :channelid'), array(':enalbed' => POST_STATE_ENABLED, ':channelid'=>$channelID))
                ->queryRow();
            
            $count = (int)$params['count'];
            if ($count <= 0 || $count > self::DEFAULT_RANDOM_MAX_COUNT)
                $count = self::DEFAULT_TIMELINE_MAX_COUNT;
            
            $minid = (int)$maxIdMinId['minid'];
            $maxid = (int)$maxIdMinId['maxid'];
            
            $conditoins = array('and', 't.state = :enalbed',  'channel_id = :channelid', 'id = :randid');
            $param = array(':enalbed' => POST_STATE_ENABLED, ':channelid'=>$channelID, ':randid'=>0);
            $rows = array();
            for ($i=0; $i<$maxid; $i++) {
                $randid = mt_rand($minid, $maxid);
                $param['randid'] = $randid;
                $cmd = app()->getDb()->createCommand()
                    ->select($fields)
                    ->from(TABLE_POST . ' t')
                    ->where($conditoins, $param)
                    ->limit(1);
                
                $row = $cmd->queryRow();
                if ($row === false || array_key_exists($row['id'], $rows))
                    continue;
                else
                    $rows[$row['id']] = $row;

                if (count($rows) >= $count)
                    break;
            }
            
            $rows = self::formatRows($rows);
            $rows = array_values($rows);
            
            return $rows;
        }
        catch (Exception $e) {
            echo $e->getMessage();
            throw new ApiException('系统错误', ApiError::SYSTEM_ERROR, $params['debug']);
        }
    }
   
    public function support()
    {
        self::requirePost();
        $this->requiredParams(array('postid'));
        $params = $this->filterParams(array('postid'));
        
        try {
            $id = (int)$params['postid'];
            $counters = array('up_score'=>1);
            $result = Post::model()->updateCounters($counters, 'id=:pid', array(':pid'=>$id));
            $data = array('errno'=>0);
        }
        catch (Exception $e) {
            $data = array('errno'=>1);
        }
        
        return $data;
    }
   
    public function oppose()
    {
        self::requirePost();
        $this->requiredParams(array('postid'));
        $params = $this->filterParams(array('postid'));
        
        try {
            $id = (int)$params['postid'];
            $counters = array('down_score'=>1);
            $result = Post::model()->updateCounters($counters, 'id=:pid', array(':pid'=>$id));
            $data = array('errno'=>0);
        }
        catch (Exception $e) {
            $data = array('errno'=>1);
        }
        
        return $data;
    }
    
    public function create()
    {
    	self::requirePost();
//        	$this->requireLogin();
    	$this->requiredParams(array('content', 'token', 'channel_id'));
    	$params = $this->filterParams(array('content', 'tags', 'channel_id', 'category_id', 'pic', 'token'));
    	
    	$post = new Post('api');
    	$post->channel_id = (int)$params['channel_id'];
    	$post->category_id = (int)$params['category_id'];
    	$post->content = $params['content'];
    	$post->tags = $params['tags'];
    	$post->create_time = $_SERVER['REQUEST_TIME'];
    	$post->state = POST_STATE_ENABLED;
    	$post->up_score = mt_rand(3, 15);
    	$post->down_score = mt_rand(0, 2);
    	
    	try {
    	    $url = trim($params['pic']);
        	if (!empty($url)) {
        	    $path = CDBase::makeUploadPath('pics');
        	    $info = parse_url($url);
                $extensionName = pathinfo($info['path'], PATHINFO_EXTENSION);
                $file = CDBase::makeUploadFileName('');
                $bigFile = 'big_' . $file;
                $filename = $path['path'] . $file;
                $bigFilename = $path['path'] . $bigFile;
                
        	    $curl = new CdCurl();
        	    $curl->get($url);
        	    $data = $curl->rawdata();
        	    $curl->close();
        	    $im = new CdImage();
        	    $im->load($data);
        	    unset($data, $curl);
        	    $im->saveAsJpeg($filename, 50);
        	    $post->pic = fbu($path['url'] . $im->filename());
        	    $im->revert()->saveAsJpeg($bigFilename);
        	    $post->big_pic = fbu($path['url'] . $im->filename());
        	}
        	else
        	    $post->pic = $post->big_pic = '';
    	}
        catch (CException $e) {
            var_dump($e);
        }
    	
    	try {
    		return (int)$post->save();
    	}
    	catch (ApiException $e) {
    		throw new ApiException('系统错误', ApiError::SYSTEM_ERROR);
    	}
    }
    
    public function tofavorite()
    {
        self::requirePost();
        $this->requiredParams(array('user_id', 'token', 'post_id'));
        $params = $this->filterParams(array('user_id', 'token', 'post_id'));
        $userID = (int)$params['user_id'];
        $postID = (int)$params['post_id'];
        $cmd = app()->getDb()->createCommand()
            ->select('id')
            ->from(TABLE_POST_FAVORITE)
            ->where('user_id = :userid and post_id = :postid', array(':userid' => $userID, ':postid' => $postID));

        $row = $cmd->queryRow();
        if ($row === false) {
            $columns = array(
                'user_id' => $userID,
                'post_id' => $postID,
            );
            $result = app()->getDb()->createCommand()
                ->insert(TABLE_POST_FAVORITE, $columns);
            $errno = (int)($result == 0);
        }
        else
            $errno = -1;

        $data['errno'] = $errno;
        return $data;
    }

    public function favorite()
    {
        $count = 5;
        
        $this->requiredParams(array('userid', 'maxid'));
        $params = $this->filterParams(array('userid', 'email', 'token', 'fields', 'maxid'));
    
    
        $uid = (int)$params['userid'];
        $maxid = (int)$params['maxid'];
        
        
        if ($maxid > 0) {
            $cmd = app()->getDb()->createCommand()
                ->select('id')
                ->from(TABLE_POST_FAVORITE)
                ->where(array('and', 'user_id = :userid', 'post_id = :postid'), array(':userid' => $uid, ':postid' => $maxid));
                
//             echo $cmd->text;
            $rowID = $cmd->queryScalar();
//             var_dump($rowID);
        }
        
        $cmd = app()->getDb()->createCommand()
            ->select('post_id')
            ->from(TABLE_POST_FAVORITE)
            ->order('id desc')
            ->limit($count);
        
        if ($rowID)
            $cmd->where(array('and', 'user_id = :userid', 'id < :maxid'), array(':userid' => $uid, ':maxid' => $rowID));
        else
            $cmd->where('user_id = :userid', array(':userid' => $uid));

        $ids = $cmd->queryColumn();

        if (empty($ids)) return array();

        $fields = empty($params['fields']) ? '*' : $params['fields'];
        $conditions = array('and', array('in', 'id', $ids), 'state = :enabled');
        $conditionParams = array(':enabled' => POST_STATE_ENABLED);
        $cmd = app()->getDb()->createCommand()
            ->select($fields)
            ->from(TABLE_POST)
            ->limit($count)
            ->where($conditions, $conditionParams);

        $rows = $cmd->queryAll();
        $rows = self::formatRows($rows);

        return $rows;
    
    }

    private static function updateLastRequestTime($token)
    {
        if (empty($token))
            return false;
    
        $token = Device::convertToken($token);
        Device::model()->updateAll(array('last_time'=>$_SERVER['REQUEST_TIME']), 'device_token = :token', array(':token'=>$token));
    }
}


