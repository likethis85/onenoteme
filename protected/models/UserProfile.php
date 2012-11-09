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
 * @property integer $qqt_uid
 *
 * @property string $genderLabel
 */
class UserProfile extends CActiveRecord
{
    public static function genders()
    {
        return array(GENDER_UNKOWN, GENDER_FEMALE, GENDER_MALE);
    }
    
    public static function genderLabel($gender = null)
    {
        $labels = array(
                GENDER_UNKOWN => '保密',
                GENDER_FEMALE => '妹纸',
                GENDER_MALE => '帅锅',
        );
    
        return ($gender === null) ? $labels : $labels[$gender];
    }
    
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
    		array('user_id', 'unique'),
			array('user_id', 'required'),
			array('user_id, province, city', 'numerical', 'integerOnly'=>true),
			array('location', 'length', 'max'=>100),
			array('weibo_uid, qqt_uid', 'length', 'max'=>50),
			array('description, website, image_url, avatar_large', 'length', 'max'=>250),
	        array('gender', 'in', 'range'=>self::genders()),
			array('website', 'url'),
			array('description', 'safe'),
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
		    'qqt_uid' => '腾讯微博UID',
		);
	}
	
	public function getGenderLabel()
	{
	    return self::genderLabel($this->gender);
	}

}