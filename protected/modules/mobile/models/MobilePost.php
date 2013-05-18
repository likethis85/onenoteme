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
        $content = strip_tags($this->content);
        $summary = mb_substr($content, 0, $len, app()->charset);
	    $moreCount = mb_strlen($content, app()->charset) - mb_strlen($summary, app()->charset);
         
        $tags = param('summary_html_tags');
        if ($moreCount > 0) {
            $content = strip_tags($this->content, $tags);
            $summary = mb_strimwidth($content, 0, $len, '......', app()->charset);
            $summary .= '<i class="cgreen">(剩余&nbsp;' . (int)$moreCount . '&nbsp;字)</i>';
        }
        else
            $summary = strip_tags($this->content, $tags);
        return $summary;
    }
    
    public function getTitleLink($len = 50, $target = '_self')
    {
       return parent::getTitleLink($len, $target);
    }

    public function getCommentUrl()
    {
        return aurl('mobile/post/show', array('id'=>$this->id), '', 'comments');
    }

    	
	/**
	 * 获取标签链接列表
	 * @param string $route 标签段子列表的route，主要同时给手机版使用此方法
	 * @param string $operator 标签分隔符，默认&nbsp;
	 * @param string $target 链接打开页面，默认_blank
	 * @param string $class html标签 class name
	 * @return string
	 */
	public function getTagLinks($route = 'tag/posts', $operator = '&nbsp;', $target = '_self', $class='beta-tag')
	{
	    return parent::getTagLinks('mobile/tag/posts', $operator, $target, $class);
	}

}
