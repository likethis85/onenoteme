<?php
class PostController extends RestController
{
    const HISTORY_COUNT = 50;
    
    public function filters()
    {
        return array(
            'postOnly + create',
            'putOnly + support, oppose, like, unlike',
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
    public function actionTimeline($channel_id, $lasttime = 0, $maxtime = 0, $media_type = 0)
    {
        $channel_id = (int)$channel_id;
        $lasttime = (float)$lasttime;
        $maxtime = (float)$maxtime;
        $media_type = (int)$media_type;
        $user_id = (int)$user_id;
        
        $criteria = new CDbCriteria();
        $criteria->select = self::selectColumns();
        $criteria->limit = $this->postRowCount();
        $criteria->order = 't.create_time desc';
        $criteria->with = array('user', 'user.profile');
        
        $columns = array('t.channel_id' => $channel_id);
        if ($user_id > 0)
            $columns['t.user_id'] = $user_id;
        if ($media_type)
            $columns['t.media_type'] = $media_type;
        $criteria->addColumnCondition($columns);
        
        if ($lasttime > 0) {
            $criteria->addCondition('t.create_time > :lasttime');
            $criteria->params[':lasttime'] = $lasttime;
        }
        elseif ($maxtime > 0) {
            $criteria->addCondition('t.create_time < :maxtime');
            $criteria->params[':maxtime'] = $maxtime;
        }
        
        $posts = RestPost::model()->published()->findAll($criteria);
        $rows = $this->formatPosts($posts);
        
        $this->output($rows);
    }
    
    /**
     * 获取以前的内容，服务器随机选取一天的内容返回固定条数，条数不够的，取前一天内容补足
     * @param integer $channel_id required，频道ID
     * @param integer $media_type optional，类型，MEDIA_TEXT | MEDIA_IMAGE | MEDIA_VIDEO
     * @return array 内容列表，数组结构
     */
    public function actionHistory($channel_id = 0, $media_type = 0)
    {
        $channel_id = (int)$channel_id;
        $media_type = (int)$media_type;
        
        $criteria = new CDbCriteria();
        $criteria->select = self::selectColumns();
        $criteria->limit = self::HISTORY_COUNT;
        $criteria->order = 't.create_time asc';
        $criteria->with = array('user', 'user.profile');
        
        if ($channel_id > 0)
            $criteria->addColumnCondition(array('channel_id' => $channel_id));
        if ($media_type > 0)
            $criteria->addColumnCondition(array('media_type' => $media_type));
        
        // 取随机一天，计算出此日期凌晨的时间戳
        $mmtime = self::getMaxMinCreatetime();
        $randtime = mt_rand($mmtime['mintime'], $mmtime['maxtime']);
        $randdate = getdate($randtime);
        $mintime = mktime(0, 0, 0, $randdate['mon'], $randdate['mday'], $randdate['year']);
        $criteria->addCondition('t.create_time >= :mintime');
        $criteria->params[':mintime'] = $mintime;
        
        $posts = RestPost::model()->published()->findAll($criteria);
        $rows = $this->formatPosts($posts);
        
        $this->output($rows);
    }
    
    public function actionFavorite($user_id, $page = 1)
    {
        $user_id = (int)$user_id;
        $criteria = new CDbCriteria();
        $criteria->select = 'id';
        $user = RestUser::model()->findByPk($user_id, $criteria);
        if ($user === null)
            throw new CDRestException('user is not exist');
        
        $offset = ($page - 1) *  $this->postRowCount();
        $posts = $user->favorites(array(
            'condition' => 'favorites.state = ' . POST_STATE_ENABLED,
            'select' => $this->selectColumns(),
            'limit' => $this->postRowCount(),
            'offset' => $offset,
            'with' => array('user', 'user.profile'),
        ));
        
        $data = $this->formatPosts($posts);
        $this->output($data);
    }
    
    public function actionBest($hours = 24, $channel_id = 0, $page = 1)
    {
        $hours = (int)$hours;
        $channel_id = (int)$channel_id;
        $page = (int)$page;
        $page = $page < 1 ? 1 : $page;
        
        $criteria = new CDbCriteria();
        if ($channel_id > 0)
            $criteria->addColumnCondition(array('t.channel_id' => $channel_id));
        
        if ($hours > 0) {
            $fromtime = $_SERVER['REQUEST_TIME'] - $hours * 3600;
            $criteria->addCondition('t.create_time > :fromtime');
            $criteria->params[':fromtime'] = $fromtime;
        }

        $criteria->order = 't.istop desc, (t.up_score-t.down_score) desc, t.create_time desc';
        $criteria->select = self::selectColumns();
        $criteria->limit = $this->postRowCount();
        $criteria->with = array('user', 'user.profile');
        $offset = ($page - 1) *  $this->postRowCount();
        $criteria->offset = $offset;
        
        $posts = RestPost::model()->published()->findAll($criteria);
        $rows = $this->formatPosts($posts);
        
        $this->output($rows);
    }
    
    public function actionMyshare($user_id, $channel_id = 0, $page = 1)
    {
        $channel_id = (int)$channel_id;
        $user_id = (int)$user_id;
        $page = (int)$page;
        $page = $page < 1 ? 1 : $page;
        
        $criteria = new CDbCriteria();
        $criteria->select = self::selectColumns();
        $criteria->limit = $this->postRowCount();
        $criteria->order = 't.create_time desc';
        $criteria->with = array('user', 'user.profile');
        $offset = ($page - 1) *  $this->postRowCount();
        $criteria->offset = $offset;
        
        $columns = array('t.user_id' => $user_id);
        if ($channel_id > 0)
            $columns['t.channel_id'] = $channel_id;
        $criteria->addColumnCondition($columns);
        
        $posts = RestPost::model()->published()->findAll($criteria);
        $rows = $this->formatPosts($posts);
        
        $this->output($rows);
    }
    
    public function actionFeedback($user_id, $channel_id = 0, $page = 1)
    {
        $user_id = (int)$user_id;
        $channel_id = (int)$channel_id;
        
        $criteria = new CDbCriteria();
        $criteria->select = 'id';
        $user = RestUser::model()->findByPk($user_id, $criteria);
        if ($user === null)
            throw new CDRestException('user is not exist');
        
        $offset = ($page - 1) *  $this->postRowCount();
        $cmd = db()->createCommand()
            ->selectDistinct('c.post_id')
            ->from(TABLE_COMMENT . ' c')
            ->limit($this->postRowCount(), $offset)
            ->order('c.create_time desc')
            ->where('c.user_id = :userID', array(':userID' => $user_id));
        
        if ($channel_id > 0)
            $cmd->join(TABLE_POST . ' p', 'p.channel_id = :channelID', array(':channelID' => $channel_id));
        
        $pids = $cmd->queryColumn();
        
        if (count($pids) > 0) {
            $criteria = new CDbCriteria();
            $criteria->scopes = array('published');
            $criteria->select = self::selectColumns();
            $criteria->with = array('user', 'user.profile');
            $criteria->order = 't.create_time desc';
            $criteria->addInCondition('t.id', $pids);
            
            $posts = RestPost::model()->findAll($criteria);
            $data = $this->formatPosts($posts);
        }
        else
            $data = array();
        
        $this->output($data);
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
        $post = RestPost::model()->findByPk($post_id, $criteria);
        
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
    
    public function actionSupport($post_id)
    {
        $post_id = (int)$post_id;
        if (empty($post_id))
            throw new CHttpException(500, 'request is invalid');
        
        $criteria = new CDbCriteria();
        $criteria->select = array('id', 'up_score');
        $post = RestPost::model()->published()->findByPk($post_id, $criteria);
        
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
    
    public function actionOppose($post_id)
    {
        $post_id = (int)$post_id;
        if (empty($post_id))
            throw new CHttpException(500, 'request is invalid');
        
        $criteria = new CDbCriteria();
        $criteria->select = array('id', 'down_score');
        $post = RestPost::model()->published()->findByPk($post_id, $criteria);
        
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
        $duration = 24*60*60;
        $count = db()->cache($duration)->createCommand()
            ->select('count(*)')
            ->from(TABLE_POST)
            ->where(array('and', 'media_type = :mediatype', 'state = :enabled'), array(':mediatype' => MEDIA_TYPE_TEXT, ':enabled'=>POST_STATE_ENABLED))
            ->order('create_time desc')
            ->queryScalar();
        
        $criteria = new CDbCriteria();
        $criteria->order = 't.create_time desc';
        $criteria->addColumnCondition(array('t.media_type'=>MEDIA_TYPE_TEXT, 't.state' => POST_STATE_ENABLED));
        $criteria->offset = mt_rand(0, $count-1);
        $model = RestPost::model()->find($criteria);
        $data = CDRestDataFormat::formatPost($model, false, false);
        $this->output($data);
    }
    
    public function actionLike($post_id)
    {
        $post_id = (int)$post_id;
        $criteria = new CDbCriteria();
        $criteria->select = array('id', 'favorite_count');
        $post = RestPost::model()->findByPk($post_id, $criteria);
        if (null === $post)
            throw new CHttpException(404, 'post is not exist');
        
        $userID = request()->getPut('user_id');
        if (empty($userID))
            $userID = $this->getUserID();
        $result = $post->addFavorite((int)$userID);
        if ($result === false)
            throw new CDRestException(CDRestError::CLASS_METHOD_EXECUTE_ERROR);
        else {
            $data = array('post_favorite_count' => (int)$post->favorite_count);
            $this->output($data);
        }
    }
    
    public function actionUnlike($post_id)
    {
        $post_id = (int)$post_id;
        $criteria = new CDbCriteria();
        $criteria->select = array('id', 'favorite_count');
        $post = RestPost::model()->findByPk($post_id, $criteria);
        if (null === $post)
            throw new CHttpException(404, 'post is not exist');
        
        $userID = request()->getPut('user_id');
        if (empty($userID))
            $userID = $this->getUserID();
        $result = $post->delFavorite((int)$userID);
        if ($result === false)
            throw new CDRestException(CDRestError::CLASS_METHOD_EXECUTE_ERROR);
        else {
            $data = array('post_favorite_count' => (int)$post->favorite_count);
            $this->output($data);
        }
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
    
    protected function postRowCount()
    {
        return DEFAULT_POST_FEEDBACK_COUNT;
    }
    
    protected function formatPosts(array $models, $includeUser = true, $includeComment = false)
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


