<?php

/**
 * This is the model class for table "{{post}}".
 *
 * The followings are the available columns in table '{{post}}':
 * @property integer $id
 * @property integer $channel_id
 * @property integer $category_id
 * @property string $title
 * @property string $content
 * @property string $pic
 * @property string $big_pic
 * @property integer $create_time
 * @property integer $up_score
 * @property integer $down_score
 * @property integer $comment_nums
 * @property integer $user_id
 * @property string $user_name
 * @property integer $tags
 * @property integer $state
 */
class DPost extends DModel
{
    const STATE_DISABLED = 0;
    const STATE_ENABLED = 1;
    const STATE_TOP = 2;
    
    /**
     * Returns the static model of the specified DModel class.
	 * @return DPost the static model class
     */
    public static function model($class = __CLASS__)
    {
        return parent::model($class);
    }
    
    public function table()
    {
        return '{{post}}';
    }
    
    public function pk()
    {
        return 'id';
    }
    
    public function columns()
    {
        return array('id', 'channel_id', 'category_id', 'title', 'content', 'pic', 'big_pic', 'create_time', 'up_score', 'down_score', 'comment_nums', 'user_id', 'user_name', 'tags', 'state');
    }
    
    protected function afterFind()
    {
        if (empty($this->title))
            $this->title = mb_substr($this->content, 0, 20, app()->charset);

        $this->content = nl2br(strip_tags($this->content, '<b><strong><img><span>'));
    }
    

    public static function fetchValidList($limit = 20, $page = 1, $conditions = '', $order = 'create_time desc, id desc')
    {
        $duration = 300;
        $limit = $limit ? $limit : param('countOfPage');
        $page = ($page > 0) ? $page : 1;
        $offset = ($page - 1) * $limit;
        $defaultWhere = 'state != ' . self::STATE_DISABLED;
        if ($conditions)
            $where = array('and', $defaultWhere, $conditions);
        
        $cmd = app()->db->cache($duration)->createCommand()
            ->order($order)
            ->limit($limit)
            ->offset($offset)
            ->where($where);
        
        return DPost::model()->findAll($cmd);
    }
    
    public function getCanShow()
    {
        return ($this->state == self::STATE_DISABLED
            && ($this->up_score / ($this->down_score ? $this->down_score : 1)) > param('pecentOfSetPostIsShow')
            && ($this->up_score + $this->down_score) > param('numsOfSetPostIsShow'));
    }
    
    public function updateIsShow()
    {
        if ($this->getCanShow()) {
            $this->state = self::STATE_ENABLED;
            return $this->update(array('state'));
        }
        return false;
    }
    
    public function getCanDelete()
    {
        return ($this->state == self::STATE_DISABLED
            && ($this->up_score / ($this->down_score ? $this->down_score : 1)) < param('pecentOfDeletePost')
            && ($this->up_score + $this->down_score) > param('numsOfDeletePost'));
    }
    
    /**
     * 获取标签的数组形式
     * @return array
     */
    public function getTagsArray()
    {
        static $tags = array();
        if ($tags[$this->id]) return $tags[$this->id];

        if (empty($this->tags))
            return array();
            
        $data = DTag::filterTags($this->tags);
        return $tags[$this->id] = explode(',', $data);
    }
    
    public function getTagsLinks($operator = '&nbsp;', $target = '_blank', $route = 'tag/posts')
    {
        if (empty($this->tagsArray))
            return '';

        foreach ($this->tagsArray as $tag)
            $data[] = CHtml::link($tag, aurl($route, array('name'=>urlencode($tag))), array('target'=>$target));
        return implode($operator, $data);
    }

    public function getCommentListUrl()
    {
        return aurl('comment/list', array('pid'=>$this->id));
    }
    
    public function getCreateTime($format = null)
    {
        if (empty($this->create_time))
            return '';
            
        $format = $format ? $format : param('formatDateTime');
        return date($format, $this->create_time);
    }
    
    public function getPostUserName()
    {
        return $this->user_name ? $this->user_name : user()->guestName;
    }
    
    public function getPicture()
    {
        if ($this->big_pic)
            return $this->big_pic;
        elseif ($this->pic)
            return $this->pic;
        else
            return 'javascript:void(0);';
    }
    
}
