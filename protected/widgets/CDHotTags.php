<?php
class CDHotTags extends CWidget
{
    const TAG_NUMS = 28;
    const CACHE_DURATION = 86400;
    
    public $title = null;
    public $tagsNums = self::TAG_NUMS;
    public $className;
    public $html;

    public function init()
    {
        $this->className = empty($this->className) ? 'content-block hot-tags' : 'content-block hot-tags ' . $this->className;
        $this->html = '<div class="' . $this->className . '">';
        if (!empty($this->title))
            $this->html .= '<h2 class="content-title">' . $this->title . '</h2>';
            
        if (empty($this->tagsNums))
            $this->tagsNums = self::TAG_NUMS;
    }
    
    public function run()
    {
        $tags = $this->fetchHotTags();
        
        if (!empty($tags)) {
            foreach ($tags as $key => $tag) {
                if ($key < 3)
                    $tag_level = 'tag-level3';
                elseif ($key < 10)
                    $tag_level = 'tag-level2';
                else
                    $tag_level = 'tag-level1';
                
                $links[] = CHtml::link($tag->name, $tag->getUrl(), array('target'=>'_blank', 'class'=>$tag_level));
            }
            shuffle($links);
            $this->html .= implode('', $links);
        }
        
        $this->html .= '</div>';
        echo $this->html;
    }
    
    private function fetchHotTags()
    {
        $criteria = new CDbCriteria();
        $criteria->select = 'id, name';
        $criteria->limit = $this->tagsNums;
        $criteria->order = 'post_nums desc';
        $models = Tag::model()->cache(self::CACHE_DURATION)->findAll($criteria);
        return $models;
    }
}

