<?php

/**
 * This is the model class for table "{{token}}".
 *
 * The followings are the available columns in table '{{token}}':
 * @property integer $id
 * @property integer $user_id
 * @property string $uuid
 * @property string $device_token
 * @property integer $last_time
 * @property integer $close_push
 */
class IOSDevice extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Device the static model class
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
		return TABLE_IOS_DEVICE;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('device_token', 'required'),
			array('user_id, last_time, close_push', 'numerical', 'integerOnly'=>true),
			array('device_token, uuid', 'length', 'max'=>100),
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
			'id' => 'ID',
			'user_id' => '用户ID',
			'device_token' => 'Device Token',
	        'last_time' => '最后使用时间',
		    'close_push' => '关闭PUSH推送',
		);
	}

    public static function convertToken($token)
    {
        $token = trim($token);
        if (empty($token))
            return '';
        
        $token = trim($token, '<>');
        $token = str_replace(' ', '', $token);
        
        return $token;
    }
}