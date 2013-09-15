<?php

/**
 * This is the model class for table "{{app_union_log}}".
 *
 * The followings are the available columns in table '{{app_union_log}}':
 * @property integer $id
 * @property string $device_udid
 * @property string $wdz_version
 * @property string $mac_address
 * @property string $app_version
 * @property string $app_store_id
 * @property string $platform
 * @property string $bundle_identifier
 * @property string $provider
 * @property string $promoter
 * @property integer $create_time
 * @property string $create_ip
 *
 * @property string $createTime;
 */
class AppUnionLog extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @return AppUnionLog the static model class
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
		return TABLE_APP_UNION_LOG;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
	        array('device_udid, wdz_version, mac_address, bundle_identifier, app_store_id', 'required'),
			array('device_udid', 'length', 'max'=>100),
			array('wdz_version, app_version', 'length', 'max'=>20),
			array('mac_address, app_store_id, platform', 'length', 'max'=>50),
			array('bundle_identifier, provider, promoter', 'length', 'max'=>200),
    		array('create_time', 'numerical', 'integerOnly'=>true),
			array('create_ip', 'length', 'max'=>15),
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
			'device_udid' => '设备UDID',
			'wdz_version' => '挖段子版本',
			'mac_address' => 'MAC地址',
			'app_version' => '应用版本',
			'app_store_id' => '应用ID',
			'platform' => '平台',
			'bundle_identifier' => '应用标识符',
			'provider' => '供应商',
			'promoter' => '推广人',
			'create_time' => '点击时间',
			'create_ip' => '点击IP',
		);
	}
	
	public function getCreateTime($format = null)
	{
	    if (empty($this->create_time))
	        return '';
	
	    $format = $format ? $format : param('formatDateTime');
	    return date($format, $this->create_time);
	}
	
	protected function beforeSave()
	{
	    if ($this->getIsNewRecord()) {
	        $this->create_time = time();
	        $this->create_ip = CDBase::getClientIPAddress();
	    }
	    return true;
	}
}



