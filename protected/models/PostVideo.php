<?php

/**
 * This is the model class for table "{{post_video}}".
 *
 * The followings are the available columns in table '{{post_video}}':
 * @property string $id
 * @property string $post_id
 * @property string $desc
 * @property string $html5_url
 * @property string $flash_url
 * @property string $source_url
 * @property integer $create_time
 * @property string $create_ip
 * @property string $video_time
 *
 * @property Post $post
 */
class PostVideo extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return PostVideo the static model class
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
		return TABLE_POST_VIDEO;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('create_time', 'numerical', 'integerOnly'=>true),
			array('post_id, video_time', 'numerical', 'integerOnly'=>true),
			array('desc, html5_url, flash_url, source_url', 'length', 'max'=>250),
			array('create_ip', 'length', 'max'=>15),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
	        'post' => array(self::BELONGS_TO, 'Post', 'post_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'post_id' => 'PID',
			'desc' => '描述',
			'html5_url' => 'HTML5格式URL',
			'flash_url' => 'FLASH格式URL',
			'source_url' => '来源URL',
			'create_time' => '添加时间',
			'create_ip' => '添加IP',
			'video_time' => '时长',
		);
	}

}

