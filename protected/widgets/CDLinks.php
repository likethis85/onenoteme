<?php
class CDLinks extends CWidget
{
    const DEFAULT_NAME_LEN = 15;
    
    public $ishome = null;
    
    /**
     * links count
     * @var integer
     */
    public $count = 0;

    /**
     * links list title
     * @var string
     */
    public $title;
    
    /**
     * name sub len
     * @var integer
     */
    public $nameLen;
    
    /**
     * 如果内容为空的话是否显示
     * @var boolean
     */
    public $allowEmpty = false;
    
    public function init()
    {
        $this->allowEmpty = (bool)$this->allowEmpty;
        $this->count = (int)$this->count;
        
        $title = trim($this->title);
        if (empty($title))
            $this->title = '友情链接';
        
        $this->nameLen = (int)$this->nameLen;
        if ($this->nameLen === 0)
            $this->nameLen = self::DEFAULT_NAME_LEN;
    }
        
    public function run()
    {
        $criteria = new CDbCriteria();
        if ($this->ishome !== null) {
            $ishome = (int)$this->ishome;
            $criteria->addColumnCondition(array('ishome' => $ishome));
        }
        
        if ($this->count > 0)
            $criteria->limit = $this->count;
        
        $models = Link::fetchLinks($criteria);
        
        if (empty($models) && !$this->allowEmpty) return ;
        $this->render('cd_friend_links', array('models'=>$models));
    }
    
}


