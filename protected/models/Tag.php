<?php

/**
 * This is the model class for table "{{Tag}}".
 *
 * The followings are the available columns in table '{{Tag}}':
 * @property integer $id
 * @property string $name
 * @property integer $post_nums
 */
class Tag extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Tag the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return TABLE_TAG;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
		    array('post_nums', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>50),
			array('name', 'unique'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => '名称',
			'post_nums' => '段子数',
		);
	}
	
	public static function filterTagsArray($tags)
	{
	    if (empty($tags)) return array();
	
	    $tags = str_replace('，', ',', $tags);
	    $tags = explode(',', $tags);
	    $tagsArray = array();
	    foreach ((array)$tags as $tag) {
	        if (!empty($tag))
	            $tagsArray[] = strip_tags(trim($tag));
	    }
	
	    $tags = $tag = null;
	    return $tagsArray;
	}
	
	public static function savePostTags($postid, $tags)
	{
	    $postid = (int)$postid;
	    if (0 === $postid || empty($tags))
	        return false;
	
	    if (is_string($tags))
	        $tags = self::filterTagsArray($tags);
	
	    $count = 0;
	    foreach ((array)$tags as $v) {
	        $model = self::model()->findByAttributes(array('name'=>$v));
	        if ($model === null) {
	            $model = new Tag();
	            $model->name = $v;
	            if ($model->save()) $count++;
	        }
	
	        $row = app()->getDb()->createCommand()
    	        ->select('id')
    	        ->from(TABLE_POST_TAG)
    	        ->where(array('and', 'post_id = :postid', 'tag_id = :tagid'), array(':postid'=>$postid, ':tagid'=>$model->id))
    	        ->queryScalar();
	
	        if ($row === false) {
	            $columns = array('post_id'=>$postid, 'tag_id'=>$model->id);
	            $count = app()->getDb()->createCommand()->insert(TABLE_POST_TAG, $columns);
	            if ($count > 0) {
	                $model->post_nums = $model->post_nums + 1;
	                $model->save(true, array('post_nums'));
	            }
	        }
	        $model = null;
	    }
	    return (int)$count;
	}
	
	public static function deletePostTags($postid)
	{
	    $postid = (int)$postid;
	    $tags = db()->createCommand()
	        ->from(TABLE_POST_TAG)
	        ->select('tag_id')
	        ->where('post_id = :postid', array(':postid' => $postid))
	        ->queryColumn();
	    
	    if (empty($tags)) return true;
	    
	    $count = app()->getDb()->createCommand()
    	    ->delete(TABLE_POST_TAG, 'post_id = :postid', array(':postid'=>$postid));
	    
	    $count = 0;
	    foreach ((array)$tags as $tagid) {
	        $model = self::model()->findByPk($tagid);
	        if ($model !== null) {
	            $model->post_nums = $model->post_nums - 1;
	            if ($model->post_nums <= 0)
	                $model->delete() && $count++;
	            else
    	            $model->save(true, array('post_nums')) && $count++;
	            
	            $model = null;
	        }
	    }
	    return (int)$count;
	}

	
	public function getNameLink($target = '_blank')
	{
	    return CHtml::link($this->name, $this->getUrl(), array('target'=>$target));
	}
	
	public function getUrl()
	{
	    return aurl('tag/posts', array('name'=>$this->name));
	}
}