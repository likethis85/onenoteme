<?php
class PostController extends RestController
{
    const HISTORY_COUNT = 50;
    
    public function filters()
    {
        return array(
            'postOnly + create',
            'putOnly + up, down',
        );
    }
    
    /**
     * 获取最新内容，如果lasttime>0表示获取最新的，如果maxtime>0表示获取更多内容，lasttime优先
     * @param integer $channel_id required，频道ID
     * @param integer $lasttime optional，用户最后更新时间，取应用内容列表最新一条内容的create_time
     * @param integer $maxtime optional，段子截止发布时间，取应用内容列表最后一条内容的create_time
     * @param integer $media_type optional，类型，MEDIA_TEXT | MEDIA_IMAGE | MEDIA_VIDEO
     * @return array 内容列表，数组结构
     */
    public function actionTimeline($channel_id, $lasttime = 0, $maxtime = 0, $media_type = 0, $user_id = 0)
    {
        $channel_id = (int)$channel_id;
        $lasttime = (float)$lasttime;
        $maxtime = (float)$maxtime;
        $media_type = (int)$media_type;
        $user_id = (int)$user_id;
        
        $criteria = new CDbCriteria();
        $criteria->select = self::selectColumns();
        $criteria->limit = $this->timelineRowCount();
        $criteria->order = 't.create_time desc';
        $criteria->with = array('user', 'user.profile');
//         $criteria->addCondition('t.original_frames <= 1');
        
        $columns = array('t.channel_id' => $channel_id);
        if ($user_id > 0)
            $columns['t.user_id'] = $user_id;
        if ($media_type)
            $columns['t.media_type'] =$media_type;
        $criteria->addColumnCondition($columns);
        
        if ($lasttime > 0) {
            $criteria->addCondition('t.create_time > :lasttime');
            $criteria->params[':lasttime'] = $lasttime;
        }
        elseif ($maxtime > 0) {
            $criteria->addCondition('t.create_time < :maxtime');
            $criteria->params[':maxtime'] = $maxtime;
        }
        
        $posts = ApiPost::model()->published()->findAll($criteria);
        $rows = $this->formatRows($posts);
        
        $this->output($rows);
    }
    
    /**
     * 获取以前的内容，服务器随机选取一天的内容返回固定条数，条数不够的，取前一天内容补足
     * @param integer $channel_id required，频道ID
     * @param integer $media_type optional，类型，MEDIA_TEXT | MEDIA_IMAGE | MEDIA_VIDEO
     * @return array 内容列表，数组结构
     */
    public function actionHistory($channel_id, $media_type = 0)
    {
        $channel_id = (int)$channel_id;
        $media_type = (int)$media_type;
        
        $criteria = new CDbCriteria();
        $criteria->select = self::selectColumns();
        $criteria->limit = self::HISTORY_COUNT;
        $criteria->order = 't.create_time desc';
        $criteria->with = array('user', 'user.profile');
        
        $columns = array('t.channel_id' => $channel_id);
        if ($media_type > 0)
            $columns['t.media_type'] = $media_type;
        $criteria->addColumnCondition($columns);
        
        // 取随机一天，计算出此日期凌晨的时间戳
        $mmtime = self::getMaxMinCreatetime();
        $randtime = mt_rand($mmtime['mintime'], $mmtime['maxtime']);
        $randdate = getdate($randtime);
        $mintime = mktime(0, 0, 0, $randdate['mon'], $randdate['mday'], $randdate['year']);
        $criteria->addCondition('t.create_time >= ' . $mintime);
        
        $posts = ApiPost::model()->published()->findAll($criteria);
        $rows = $this->formatRows($posts);
        
        $this->output($rows);
    }
    
    /**
     * 获取一个段子的信息
     * @param integer $post_id
     */
    public function actionShow($post_id)
    {
        $post_id = (int)$post_id;
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(array('t.state' => POST_STATE_ENABLED));
        $criteria->with = array('user', 'user.profile');
        $post = ApiPost::model()->findByPk($post_id, $criteria);
        
        if ($post === null)
            throw new CHttpException(404, 'post is not found');
        else {
            $row = CDRestDataFormat::formatPost($post, true, false);
            $this->output($row);
        }
    }
    
    /**
     * 发布段子内容
     * @param string $content 段子内容
     */
    public function actionCreate()
    {
        if (!request()->getIsPutRequest());
        // @todo 上传图片功能未确定，稍候开发
    }
    
    public function actionUp()
    {
        $postID = request()->getPut('post_id');
        if (empty($postID))
            throw new CHttpException(500, 'request is invalid');
        
        $criteria = new CDbCriteria();
        $criteria->select = array('id', 'up_score');
        $post = ApiPost::model()->published()->findByPk($postID, $criteria);
        
        if ($post === null)
            throw new CHttpException(404, 'post is not found');
        
        $post->up_score++;
        $result = $post->save(true, array('up_score'));
        $data = array(
            'post_id' => $post->id,
            'up_count' => $post->up_score,
        );
        $this->output($data);
    }
    
    public function actionDown()
    {
        $postID = request()->getPut('post_id');
        if (empty($postID))
            throw new CHttpException(500, 'request is invalid');
        
        $criteria = new CDbCriteria();
        $criteria->select = array('id', 'down_score');
        $post = ApiPost::model()->published()->findByPk($postID, $criteria);
        
        if ($post === null)
            throw new CHttpException(404, 'post is not found');
        
        $post->down_score++;
        $result = $post->save(true, array('down_score'));
        $data = array(
            'post_id' => $post->id,
            'down_count' => $post->down_score,
        );
        $this->output($data);
    }
    
    public function actionRandom()
    {
        $criteria = new CDbCriteria();
        $criteria->order = 't.create_time desc';
        $criteria->addColumnCondition(array('t.media_type'=>MEDIA_TYPE_TEXT, 't.state' => POST_STATE_ENABLED));
        $model = ApiPost::model()->find($criteria);
        $this->output(CDDataFormat::formatPost($model));
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
    
    protected function formatRows(array $models, $includeUser = true, $includeComment = false)
    {
        $rows = array();
        foreach ($models as $index => $model)
            $rows[$index] = CDRestDataFormat::formatPost($model, $includeUser, $includeComment);;
    
        $models = null;
        return $rows;
    }
    
    /**
     * 返回字段列表
     */
    public static function selectColumns()
    {
        return array('id', 'channel_id', 'title', 'content', 'create_time', 'up_score', 'down_score', 'comment_nums',
                'favorite_count', 't.user_id', 'user_name', 'tags', 'original_pic', 'original_pic', 'original_pic', 'original_frames',
        );
    }
    
}


