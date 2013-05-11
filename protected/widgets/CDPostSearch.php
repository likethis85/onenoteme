<?php
class CDPostSearch extends CWidget
{
    public $channel = null;
    public $mediaType = null;
    public $count = 9;
    public $page = 1;
    public $order = 'create_time desc, id desc';
    public $title;
    public $duration = 0;
    public $models = array();
    public $linkTarget = '_blank';
    
    public function init()
    {
        $this->count = (int)$this->count;
        $this->page = (int)$this->page;
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
        
        $this->render('cd_post_search_thumb', array('models'=>$models));
    }
    
    private function fetchPosts()
    {
        static $staticModels = null;
        
        $cacheID = 'cd_post_search' . (int)$this->channel . $this->count . $this->page . $this->order . $this->duration;
        if ($this->duration > 0 && array_key_exists($cacheID, $staticModels))
            return $staticModels[$cacheID];
        
        if ($this->duration > 0) {
            $models = app()->cache->get($cacheID);
            if ($models !== false)
                return $models;
        }
        
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(array('t.state' => POST_STATE_ENABLED));
        if ($this->channel !== null)
            $criteria->addColumnCondition(array('t.channel_id' => $this->channel));
        if ($this->mediaType !== null)
            $criteria->addColumnCondition(array('t.media_type' => $this->mediaType));
        
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


