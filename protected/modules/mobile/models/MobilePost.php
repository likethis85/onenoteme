<?php
/**
 * MobilePost
 * @author chendong
 * @property string $url
 * @property string $filterSummary
 * @property string $titleLink
 * @property string $commentsUrl
 */
class MobilePost extends Post
{
    /**
     * Returns the static model of the specified AR class.
     * @return MobilePost the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    public function getUrl($absolute = true)
    {
        return $absolute ? aurl('mobile/post/show', array('id'=>$this->id)) : url('mobile/post/show', array('id'=>$this->id));
    }
    
    public function getFilterSummary($len = 300)
    {
        $content = strip_tags($this->content, param('mobile_summary_html_tags'));
        $summary = mb_substr($content, 0, $len, app()->charset);
        $moreCount = mb_strlen($content, app()->charset) - mb_strlen($summary, app()->charset);
         
        if ($moreCount > 0)
            $summary .= '<i class="cgreen">(剩余&nbsp;' . (int)$moreCount . '&nbsp;)</i>';
        
        $summary = nl2br($summary);
        return $summary;
    }
    
    public function getTitleLink($len = 50, $target = '_self')
    {
       return parent::getTitleLink($len, $target);
    }

    public function getCommentsUrl()
    {
        return aurl('mobile/comment/list', array('pid'=>$this->id));
    }
}