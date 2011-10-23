<?php

/**
 * This is the model class for table "{{post}}".
 *
 * The followings are the available columns in table '{{post}}':
 * @property integer $id
 * @property integer $category_id
 * @property integer $topic_id
 * @property string $title
 * @property string $content
 * @property integer $create_time
 * @property integer $up_score
 * @property integer $down_score
 * @property integer $comment_nums
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
        return array('id', 'category_id', 'topic_id', 'title', 'content', 'create_time', 'up_score', 'down_score', 'comment_nums', 'tags', 'state');
    }
    

    public static function fetchValidList($limit = 20, $page = 1, $conditions = '', $order = 'id desc')
    {
        $limit = $limit ? $limit : param('countOfPage');
        $page = ($page > 0) ? $page : 1;
        $offset = ($page - 1) * $limit;
        $defaultWhere = 'state != ' . self::STATE_DISABLED;
        if ($conditions)
            $where = array('and', $defaultWhere, $conditions);
        $cmd = app()->db->createCommand()
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
    
    /**
     * 获取标签的数组形式
     * @return array
     */
    public function getTagsArray()
    {
        static $tags;
        if ($tags[$this->id]) return $tags[$this->id];

        if (empty($this->tags))
            return array();
            
        $data = DTag::filterTags($this->tags);
        return $tags[$this->id] = explode(',', $data);
    }
    
    public function getTagsLinks($operator = '&nbsp;', $target = '_blank')
    {
        if (empty($this->tagsArray))
            return '';

        foreach ($this->tagsArray as $tag)
            $data[] = CHtml::link($tag, aurl('tag/posts', array('tag'=>urlencode($tag))), array('target'=>$target));
        return implode($operator, $data);
    }
}
