<?php

/**
 * This is the model class for table "{{mobile_device}}".
 *
 * The followings are the available columns in table '{{mobile_device}}':
 * @property string $udid
 * @property string $model
 * @property string $sys_name
 * @property string $sys_version
 * @property string $name
 * @property string $language
 * @property string $country
 * @property string $app_version
 * @property integer $create_time
 * @property integer $last_time
 * @property integer $user_id
 * @property integer $connect_count
 */
class MobileDevice extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return MobileDevice the static model class
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
		return TABLE_MOBILE_DEVICE;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('udid, sys_name, sys_version, app_version', 'required'),
			array('udid', 'unique'),
			array('udid, model', 'length', 'max'=>100),
			array('sys_name, name, language, country, app_version', 'length', 'max'=>45),
			array('sys_version', 'length', 'max'=>20),
			array('create_time, last_time, user_id, connect_count', 'numerical', 'integerOnly'=>true),
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
			'udid' => 'UDID',
			'model' => '设备型号',
			'sys_name' => '系统名称',
			'sys_version' => '系统版本',
			'name' => '设备名称',
			'language' => '语言',
			'country' => '国家',
			'app_version' => '应用版本',
			'create_time' => '首次使用时间',
			'last_time' => '最后使用时间',
	        'user_id' => '绑定用户ID',
	        'connect_count' => '请求次数',
		);
	}

	public function getCreateTime($format = '')
	{
	    if (empty($this->create_time)) return '';
	     
	    if (empty($format))
	        $format = param('formatShortDateTime');
	     
	    return date($format, $this->create_time);
	}

	public function getLastTime($format = '')
	{
	    if (empty($this->last_time)) return '';
	     
	    if (empty($format))
	        $format = param('formatShortDateTime');
	     
	    return date($format, $this->last_time);
	}

    protected function beforeSave()
    {
        if ($this->getIsNewRecord()) {
            $this->create_time = time();
        }
        
        return true;
    }
}


