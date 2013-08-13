<?php

/**
 * This is the model class for table "{{Feedback}}".
 *
 * The followings are the available columns in table '{{Feedback}}':
 * @property integer $id
 * @property string $content
 * @property integer $create_time
 * @property string $create_ip
 * @property string $device_udid
 * @property string $device_model
 * @property integer $network_status
 */
class Feedback extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Feedback the static model class
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
		return TABLE_FEEDBACK;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('create_time, network_status', 'numerical', 'integerOnly'=>true),
			array('create_ip', 'length', 'max'=>15),
			array('device_udid, device_model', 'length', 'max'=>100),
			array('content', 'safe'),
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
			'id' => 'ID',
			'content' => '内容',
			'create_time' => '时间',
			'create_ip' => 'IP',
			'device_udid' => '设备ID',
			'device_model' => '设备型号',
			'network_status' => '网络状态',
		);
	}
	
	public function getCreateTime($format)
	{
	    if (empty($this->create_time)) return '';
	     
	    if (empty($format))
	        $format = param('formatShortDateTime');
	     
	    return date($format, $this->create_time);
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


