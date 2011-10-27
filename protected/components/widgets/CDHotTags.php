<?php
class CDHotTags extends CWidget
{
    const TAG_NUMS = 20;
    
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
        shuffle($tags);
        foreach ($tags as $tag)
            $postNums[] = $tag->post_nums;
        
        if (!empty($tags)) {
            $max = max($postNums);
            $min = min($postNums);
            $level2 = (int)(($max - $min) / 2 + $min);
            $level3 = (int)(($max - $min) / 4 * 3 + $min);
            
            foreach ($tags as $tag) {
                $nums = $tag->post_nums;
                if ($nums >= $min && $nums < $level2) {
                    $tag_level = 'tag-level1';
                }
                elseif ($nums >= $level2 && $nums < $level3) {
                    $tag_level = 'tag-level2';
                }
                elseif ($nums >= $level3 && $nums <= $max) {
                    $tag_level = 'tag-level3';
                }
                $this->html .= CHtml::link($tag->name, $tag->getUrl(), array('target'=>'_blank', 'class'=>$tag_level));
            }
        }
        
        $this->html .= '</div>';
        echo $this->html;
    }
    
    private function fetchHotTags()
    {
        $cmd = app()->getDb()->createCommand();
        $count = DTag::model()->count(clone $cmd);
        $offset = ($count > self::TAG_NUMS) ? mt_rand(0,  $count - self::TAG_NUMS) : 0;
        $cmd->limit($this->tagsNums)
            ->offset($offset)
            ->order('post_nums desc');
        return DTag::model()->findAll($cmd);
    }
}

