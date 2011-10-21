<?php
/**
 * This is the model class for table "{{category}}".
 *
 * The followings are the available columns in table '{{category}}':
 * @property integer $id
 * @property string $name
 * @property integer $create_time
 * @property string $post_nums
 * @property integer $orderid
 */
class DCategory extends DModel
{
    /**
     * Returns the static model of the specified DModel class.
	 * @return DCategory the static model class
     */
    public static function model($class = __CLASS__)
    {
        return parent::model($class);
    }
    
    public function table()
    {
        return '{{category}}';
    }

    public function pk()
    {
        return 'id';
    }
    
    public function columns()
    {
        return array('id', 'name', 'create_time', 'post_nums', 'orderid');
    }
    
    public function getCreateTime($format = null)
    {
        $format = (null === $format) ? param('formatDateTime') : $format;
        return date($format, $this->create_time);
    }
    
    public function getPostUrl()
    {
        return aurl('post/list', array('cid'=>$this->id));
    }
    
    public function getPostLink($htmlOptions = array())
    {
        return CHtml::link($this->name, $this->getPostUrl(), $htmlOptions);
    }
}