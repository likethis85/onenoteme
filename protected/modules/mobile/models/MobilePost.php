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
    
    public function getFilterSummary()
    {
        $tags = param('mobile_summary_html_tags');
        $html = strip_tags($this->summary, $tags);
         
        return $html;
    }
    
    public function getTitleLink($len = 10, $target = '_self')
    {
       return parent::getTitleLink($len, $target);
    }

    public function getCommentsUrl()
    {
        return aurl('mobile/comment/list', array('pid'=>$this->id));
    }
}