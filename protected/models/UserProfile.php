<?php

/**
 * This is the model class for table "{{user_profile}}".
 *
 * The followings are the available columns in table '{{user_profile}}':
 * @property integer $user_id
 * @property integer $province
 * @property integer $city
 * @property string $location
 * @property integer $gender
 * @property string $description
 * @property string $website
 * @property string $image_url
 * @property string $avatar_large
 * @property integer $weibo_uid
 */
class UserProfile extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return UserProfile the static model class
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
		return TABLE_USER_PROFILE;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('user_id, weibo_uid', 'required'),
			array('user_id, province, city', 'numerical', 'integerOnly'=>true),
			array('location', 'length', 'max'=>100),
			array('description, website, image_url, avatar_large', 'length', 'max'=>250),
			array('gender', 'safe'),
    		array('user_id', 'unique'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
		    'user' => array(self::BELONGS_TO, 'User', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'user_id' => '用户ID',
			'province' => '省份',
			'city' => '城市',
			'location' => '地址',
			'gender' => '性别',
			'description' => '简介',
			'website' => '网址',
			'image_url' => '头像',
			'avatar_large' => '头像大图',
		    'weibo_uid' => '新浪微博UID',
		);
	}

}