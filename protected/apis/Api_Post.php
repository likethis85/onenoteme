<?php
/**
 * Post Api接口
 * @author Chris
 * @copyright cdcchen@gmail.com
 * @package api
 */

class Api_Post extends ApiBase
{
    const DEFAULT_TIMELINE_MAX_COUNT = 50;
    const DEFAULT_HISTORY_MAX_COUNT = 50;
    const DEFAULT_RANDOM_MAX_COUNT = 15;
    
    public function show()
    {
        $params = $this->filterParams(array('postid', 'fields'));
        
        try {
            $postid = (int)$params['postid'];
            $fields = empty($params['fields']) ? '*' : $params['fields'];
            $cmd = app()->getDb()->createCommand()
                ->select($fields)
                ->from(TABLE_NAME_POST)
                ->where('id = :postid and state = :enabled', array(':postid' => $postid, ':enabled'=>Post::STATE_ENABLED));
            $row = $cmd->queryRow();
            $row = ($row === false) ? array() : self::formatRow($row);
	        return $row;
        }
        catch (Exception $e) {
        	throw new ApiException('系统错误', ApiError::SYSTEM_ERROR, $params['debug']);
        }
    }
    
    private static function formatRow($row)
    {
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
        
        if (isset($row['pic'])) {
            $pic = $row['pic'];
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
            unset($row['pic']);
        }
        
        if (isset($row['big_pic'])) {
            $bigPic = $row['big_pic'];
            if (empty($bigPic))
                $originalPic = '';
            else {
                if (filter_var($bigPic, FILTER_VALIDATE_URL) === false){
                    $bigPic = fbu($bigPic);
                    $originalPic = (filter_var($bigPic, FILTER_VALIDATE_URL) === false) ? $bigPic : '';
                }
                else
                    $originalPic = $pic;
            }
            if (empty($originalPic))
                $originalPic = $thumbnail;
            $row['original_pic'] = $originalPic;
            unset($row['big_pic']);
        }
        
        return $row;
    }
    
    private static function formateRows($rows)
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
        $params = $this->filterParams(array('channelid', 'count', 'fields', 'lastid'));
        $channelID = (int)$params['channelid'];
        
        try {
            $fields = empty($params['fields']) ? '*' : $params['fields'];
            $lastid = empty($params['lastid']) ? 0 : (int)$params['lastid'];
            $count = (int)$params['count'];
            if ($count <= 0 || $count > self::DEFAULT_TIMELINE_MAX_COUNT)
                $count = self::DEFAULT_TIMELINE_MAX_COUNT;
            
            $condition = array('and', 'state = :enabled', 'channel_id = :channelid', 'id > :lastid');
            $param = array(':enabled'=>Post::STATE_ENABLED, ':channelid' => $channelID, ':lastid'=>$lastid);
            $cmd = app()->getDb()->createCommand()
                ->select($fields)
                ->from(TABLE_NAME_POST)
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
    
    public function randomTest()
    {
        self::requiredParams(array('channelid'));
        $params = $this->filterParams(array('channelid', 'count', 'fields', 'lastid'));
        $channelID = (int)$params['channelid'];
    
        try {
            $fields = empty($params['fields']) ? '*' : $params['fields'];
            $lastid = empty($params['lastid']) ? 0 : (int)$params['lastid'];
            $count = (int)$params['count'];
            if ($count <= 0 || $count > self::DEFAULT_TIMELINE_MAX_COUNT)
                $count = self::DEFAULT_TIMELINE_MAX_COUNT;
    
            $condition = array('and', 'state = :enabled', 'channel_id = :channelid', 'id > :lastid');
            $param = array(':enabled'=>Post::STATE_ENABLED, ':channelid' => $channelID, ':lastid'=>$lastid);
            $cmd = app()->getDb()->createCommand()
            ->select($fields)
            ->from(TABLE_NAME_POST)
            ->where($condition, $param)
            ->order('id desc')
            ->limit($count);
    
            $rows = $cmd->queryAll();
    
            foreach ($rows as $index => $row)
                $rows[$index] = self::formatRow($row);
            shuffle($rows);
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
            $param = array(':enabled'=>Post::STATE_ENABLED, ':channelid' => $channelID, ':beforetime'=>$beforeTime);
            $cmd = app()->getDb()->createCommand()
                ->select($fields)
                ->from(TABLE_NAME_POST)
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
        $params = $this->filterParams(array('channelid', 'count', 'fields', 'lasttime'));
        $channelID = (int)$params['channelid'];
        
        try {
            $fields = empty($params['fields']) ? '*' : $params['fields'];
            $lasttime = empty($params['lasttime']) ? 0 : (int)$params['lasttime'];
            $count = (int)$params['count'];
            if ($count <= 0 || $count > self::DEFAULT_TIMELINE_MAX_COUNT)
                $count = self::DEFAULT_TIMELINE_MAX_COUNT;
            
            $condition = array('and', 'state = :enabled', 'channel_id = :channelid', 'create_time > :lasttime');
            $param = array(':enabled'=>Post::STATE_ENABLED, ':channelid' => $channelID, ':lasttime'=>$lasttime);
            $cmd = app()->getDb()->createCommand()
                ->select($fields)
                ->from(TABLE_NAME_POST)
                ->where($condition, $param)
                ->order('create_time desc, id desc')
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
    
    public function random()
    {
        self::requiredParams(array('channelid'));
        $params = $this->filterParams(array('channelid', 'count', 'fields'));
        $channelID = (int)$params['channelid'];

        try {
            $fields = empty($params['fields']) ? '*' : $params['fields'];
            
            $maxIdMinId = app()->getDb()->createCommand()
                ->select(array('max(id) maxid', 'min(id) minid'))
                ->from(TABLE_NAME_POST)
                ->where(array('and', 't.state = :enalbed',  'channel_id = :channelid'), array(':enalbed' => Post::STATE_ENABLED, ':channelid'=>$channelID))
                ->queryRow();
            
            $count = (int)$params['count'];
            if ($count <= 0 || $count > self::DEFAULT_RANDOM_MAX_COUNT)
                $count = self::DEFAULT_TIMELINE_MAX_COUNT;
            
            $minid = (int)$maxIdMinId['minid'];
            $maxid = (int)$maxIdMinId['maxid'];
            
            $conditoins = array('and', 't.state = :enalbed',  'channel_id = :channelid', 'id = :randid');
            $param = array(':enalbed' => Post::STATE_ENABLED, ':channelid'=>$channelID, ':randid'=>0);
            $rows = array();
            for ($i=0; $i<$maxid; $i++) {
                $randid = mt_rand($minid, $maxid);
                $param['randid'] = $randid;
                $cmd = app()->getDb()->createCommand()
                    ->select($fields)
                    ->from(TABLE_NAME_POST)
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
            
            $rows = self::formateRows($rows);
            $rows = array_values($rows);
            
            return $rows;
        }
        catch (Exception $e) {
            echo $e->getMessage();
            throw new ApiException('系统错误', ApiError::SYSTEM_ERROR, $params['debug']);
        }
    }
    
    public function delete()
    {
    	self::requirePost();
    	$this->requireLogin();
        $this->requiredParams(array('postid'));
        $params = $this->filterParams(array('postid'));
        
        try {
	        return Post::model()->findByPk($params['postid'])->delete();
        }
        catch (Exception $e) {
        	throw new ApiException('系统错误', ApiError::SYSTEM_ERROR);
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
    	$post->state = Post::STATE_DISABLED;
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
    
    private function test($channelID)
    {
        $agent = strtolower($_SERVER['User-Agent']);
        if (strpos($agent, 'android') !== false) {
            if ($channelID == CHANNEL_DUANZI)
                return self::fetchTestRows();
        }
        else
            return null;
    }
    
    private static function fetchTestRows()
    {
        $ids = array(14079,14078,14077,14071,14061,14060,14053,14049,14046,14044,14043,14042,14041,14038,14036,14035,14034,14033,14032,14031,14030,14029,14027,14026,14025,14024,14023,14022,14021,14020,14019,14018,14017,14016,14015,14014,14013,14012,14011,13995,13994,13993,13992,13991,13990,13989,13988,13987,13986,13985,13984,13983,13982,13980,13979);
        shuffle($ids);
        $cmd = app()->getDb()->createCommand()
            ->from(TABLE_NAME_POST)
            ->where(array('in', 'id', $ids));
        $rows = $cmd->queryAll();
        
        foreach ($rows as $index => $row)
            $rows[$index] = self::formatRow($row);
        
        shuffle($rows);
        return $rows;
    }
}


