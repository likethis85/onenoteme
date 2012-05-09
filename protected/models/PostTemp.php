<?php

/**
 * This is the model class for table "{{post_temp}}".
 *
 * The followings are the available columns in table '{{post_temp}}':
 * @property integer $id
 * @property integer $channel_id
 * @property integer $category_id
 * @property string $thumbnail_pic
 * @property string $bmiddle_pic
 * @property string $original_pic
 * @property integer $create_time
 * @property string $content
 * @property string $video_url
 * @property integer $repost_count
 * @property integer $comment_count
 */
class PostTemp extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PostTemp the static model class
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
		return TABLE_NAME_POST_TEMP;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
		    array('content', 'required'),
			array('channel_id, category_id, create_time, repost_count, comment_count', 'numerical', 'integerOnly'=>true),
			array('thumbnail_pic, bmiddle_pic, original_pic', 'length', 'max'=>250),
			array('content, video_url', 'safe'),
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
			'channel_id' => 'Channel',
			'category_id' => 'Category',
			'pic' => 'Pic',
			'big_pic' => 'Big Pic',
			'thumbnail' => 'Thumbnail',
			'content' => 'Content',
			'create_time' => 'Create Time',
		);
	}
}

