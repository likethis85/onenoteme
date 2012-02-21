<?php

/**
 * This is the model class for table "{{tag}}".
 *
 * The followings are the available columns in table '{{topic}}':
 * @property integer $id
 * @property string $name
 * @property integer $post_nums
 */
class DTag extends DModel
{
    /**
     * Returns the static model of the specified DModel class.
	 * @return DTag the static model class
     */
    public static function model($class = __CLASS__)
    {
        return parent::model($class);
    }
    
    public function table()
    {
        return '{{tag}}';
    }

    public function pk()
    {
        return 'id';
    }
    
    public function columns()
    {
        return array('id', 'name', 'post_nums');
    }

	public static function filterTags($tags)
	{
	    if (empty($tags))
	        return '';

	    $tags = str_replace('，', ',', $tags);
        $tags = explode(',', $tags);
    	foreach ((array)$tags as $key => $tag) {
            $tags[$key] = strip_tags(trim($tag));
        }
        return implode(',', $tags);
	}
	
	public function getLink($target = '_blank')
	{
	    return CHtml::link($this->name, $this->getUrl(), array('target'=>$target));
	}
	
	public function getUrl()
	{
	    return aurl('tag/posts', array('name'=>urlencode($this->name)));
	}
}