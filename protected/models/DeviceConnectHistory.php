<?php

/**
 * This is the model class for table "{{device_connect_history}}".
 *
 * The followings are the available columns in table '{{device_connect_history}}':
 * @property integer $id
 * @property integer $device_udid
 * @property string $sys_version
 * @property string $app_version
 * @property string $apikey
 * @property string $method
 * @property string $format
 * @property integer $create_time
 * @property string $create_ip
 */
class DeviceConnectHistory extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return DeviceConnectHistory the static model class
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
		return '{{device_connect_history}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
	        array('sys_version, app_version, apikey, method', 'required'),
			array('device_udid, method', 'length', 'max'=>100),
			array('sys_version, app_version, format', 'length', 'max'=>20),
			array('apikey', 'length', 'max'=>64),
			array('create_ip', 'length', 'max'=>15),
			array('create_time', 'numerical', 'integerOnly'=>true),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
	        'device' => array(self::BELONGS_TO, 'MobileDevice', 'device_udid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'device_udid' => '设备UDID',
			'sys_version' => '系统版本',
			'app_version' => '应用版本',
			'apikey' => 'ApiKey',
			'method' => '请求方法',
			'format' => '数据格式',
			'create_time' => '请求时间',
			'create_ip' => '设备IP',
		);
	}
	

	protected function beforeSave()
	{
	    if ($this->getIsNewRecord()) {
	        $this->create_time = time();
	        $this->create_ip = CDBase::getClientIp();
	    }
	
	    return true;
	}

}


