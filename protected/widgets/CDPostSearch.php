<?php
class CDPostSearch extends CWidget
{
    const VIEW_RECOMMEND_TEXT = 'cd_post_search_text';
    const VIEW_RECOMMEND_IMAGE = 'cd_post_search_image';
    const VIEW_SIDEBAR_THUMBS = 'cd_post_search_thumb';
    
    public $channel = null;
    public $mediaType = null;
    public $count = 9;
    public $page = 1;
    public $order = 'create_time desc, id desc';
    public $hours = 0;
    public $recommend = false;
    public $hottest = false;
    public $title;
    public $duration = 0;
    public $models = array();
    public $linkTarget = '_blank';
    public $trace = '';
    public $view = self::VIEW_SIDEBAR_THUMBS;
    
    public function init()
    {
        $this->count = (int)$this->count;
        $this->page = (int)$this->page;
        $this->hours = (int)$this->hours;
        $this->recommend = (bool)$this->recommend;
        $this->hottest = (bool)$this->hottest;
        
        if ($this->channel !== null)
            $this->channel = (int)$this->channel;
        if ($this->mediaType !== null)
            $this->mediaType = (int)$this->mediaType;
        if ($this->duration > 0)
            $this->duration = (int)$this->duration;
    }
    
    public function run()
    {
        $models = $this->models;
        if (empty($models) || !is_array($models))
            $models = $this->fetchPosts();
        
        $this->render($this->view, array('models'=>$models));
    }
    
    private function fetchPosts()
    {
        static $staticModels = array();
        
        $cacheID = 'cd_post_search' . (int)$this->channel . $this->count . $this->page . $this->order . $this->duration . $this->mediaType . $this->linkTarget . $this->trace;
        if ($this->duration > 0 && array_key_exists($cacheID, $staticModels))
            return $staticModels[$cacheID];
        
        $models = array();
        if ($this->duration > 0) {
            $models = app()->cache->get($cacheID);
            if ($models !== false)
                return $models;
        }
        
        $conditions['t.state'] = POST_STATE_ENABLED;
        if ($this->channel !== null) $conditions['t.channel_id'] = $this->channel;
        if ($this->mediaType !== null) $conditions['t.media_type'] = $this->mediaType;
        if ($this->recommend) $conditions['t.recommend'] = 1;
        if ($this->hottest) $conditions['t.hottest'] = 1;
        
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition($conditions);
        
        if ($this->hours > 0){
            $criteria->addCondition('t.create_time > :createtime');
            $criteria->params[':createtime'] = time() - $this->hours * 3600;
        }
        
        $criteria->order = $this->order;
        $criteria->limit = $this->count;
        $criteria->offset = ($this->page - 1) * $this->count;
        $models = Post::model()->findAll($criteria);
        
        if ($this->duration > 0) {
            $staticModels[$cacheID] = $models;
            app()->cache->set($cacheID, $models);
        }
        
        return $models;
    }
}


