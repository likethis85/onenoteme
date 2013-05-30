<?php
/**
 * Post Api接口
 * @author chendong
 * @copyright cdcchen@gmail.com
 * @package api
 * @version 1.0
 */

class CDApi_Post extends ApiBase
{
    const HISTORY_COUNT = 50;
    
    public function init()
    {
        $this->saveDeviceConnectHistory();
    }
    
    /**
     * 获取最新内容，如果lasttime>0表示获取最新的，如果maxtime>0表示获取更多内容，lasttime优先
     * @param integer $channel_id required，频道ID
     * @param integer $lasttime optional，用户最后更新时间，取应用内容列表最新一条内容的create_time
     * @param integer $maxtime optional，段子截止发布时间，取应用内容列表最后一条内容的create_time
     * @param integer $media_type optional，类型，MEDIA_TEXT | MEDIA_IMAGE | MEDIA_VIDEO
     * @return array 内容列表，数组结构
     */
    public function timeline()
    {
        $this->requiredParams(array('channel_id'));
        $params = $this->filterParams(array('lasttime', 'maxtime', 'channel_id', 'media_type'));
        
        $criteria = new CDbCriteria();
        $criteria->select = self::selectColumns();
        $criteria->limit = $this->timelineRowCount();
        $criteria->order = 't.create_time desc';
        $criteria->with = array('user', 'user.profile');
        
        $columns = array('channel_id' => (int)$params['channel_id']);
        if ($params['media_type'])
            $columns['media_type'] = (int)$params['media_type'];
        $criteria->addColumnCondition($columns);
        
        $lasttime = (int)$params['lasttime'];
        $maxtime = (int)$params['maxtime'];
        if ($lasttime > 0) {
            $criteria->addCondition('t.create_time > :lasttime');
            $criteria->params[':lasttime'] = $lasttime;
        }
        elseif ($maxtime > 0) {
            $criteria->addCondition('t.create_time < :maxtime');
            $criteria->params[':maxtime'] = $maxtime;
        }
        
        $posts = ApiPost::model()->findAll($criteria);
        $rows = $this->formatRows($posts);
        
        return $rows;
    }
    
    /**
     * 获取以前的内容，服务器随机选取一天的内容返回固定条数，条数不够的，取前一天内容补足
     * @param integer $channel_id required，频道ID
     * @param integer $media_type optional，类型，MEDIA_TEXT | MEDIA_IMAGE | MEDIA_VIDEO
     * @return array 内容列表，数组结构
     */
    public function history()
    {
        $this->requiredParams(array('channel_id'));
        $params = $this->filterParams(array('channel_id', 'media_type'));
        
        $criteria = new CDbCriteria();
        $criteria->select = self::selectColumns();
        $criteria->limit = self::HISTORY_COUNT;
        $criteria->order = 't.create_time desc';
        $criteria->with = array('user', 'user.profile');
        
        $columns = array('channel_id' => (int)$params['channel_id']);
        if ($params['media_type'])
            $columns['media_type'] = (int)$params['media_type'];
        $criteria->addColumnCondition($columns);
        
        // 取随机一天，计算出此日期凌晨的时间戳
        $mmtime = self::getMaxMinCreatetime();
        $randtime = mt_rand($mmtime['mintime'], $mmtime['maxtime']);
        $randdate = getdate($randtime);
        $mintime = mktime(0, 0, 0, $randdate['mon'], $randdate['mday'], $randdate['year']);
        $criteria->addCondition('t.create_time >= ' . $mintime);
        
        $posts = ApiPost::model()->findAll($criteria);
        $rows = $this->formatRows($posts);
        
        return $rows;
    }
    
    /**
     * 发布段子内容
     * @param string $content 段子内容
     */
    public function create()
    {
        // @todo 上传图片功能未确定，稍候开发
    }
    
    public function user_timeline()
    {
        // @todo 二期，获取某一个用户发布的内容
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    /**
     * 获取当前段子的最早发布时间和最晚发布时间
     * @return array，有两个元素，mintime和maxtime
     */
    private static function getMaxMinCreatetime()
    {
        $duration = 3600*24*7;
        $row = app()->getDb()->cache($duration)->createCommand()
            ->from(TABLE_POST)
            ->select(array('min(create_time) mintime', 'max(create_time) maxtime'))
            ->where('create_time > 0')
            ->queryRow();
        
        return $row;
    }
    
    protected function timelineRowCount()
    {
        return 20;
    }
    
    protected function formatRows(array $models)
    {
        $rows = array();
        foreach ($models as $index => $model)
            $rows[$index] = CDDataFormat::formatPost($model);;
        
        $models = null;
        return $rows;
    }

    /**
     * 返回字段列表
     */
    public static function selectColumns()
    {
        return array('id', 'channel_id', 'title', 'content', 'create_time', 'up_score', 'down_score', 'comment_nums',
            'favorite_count', 'user_id', 'user_name', 'tags', 'original_pic', 'original_pic', 'original_pic',
        );
    }
    
}



 
 
 