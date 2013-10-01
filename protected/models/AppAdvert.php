<?php

/**
 * This is the model class for table "{{app_advert}}".
 *
 * The followings are the available columns in table '{{app_advert}}':
 * @property string $slot_id
 * @property integer $width
 * @property integer $height
 * @property string $app_store_id
 * @property string $bundle_identifier
 * @property string $app_version
 * @property string $provider
 * @property string $icon
 * @property string $url
 * @property string $intro
 * @property integer $state
 */
class AppAdvert extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return TABLE_APP_ADVERT;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
	        array('slot_id, app_store_id, bundle_identifier, icon, url', 'required'),
			array('width, height, state', 'numerical', 'integerOnly'=>true),
			array('slot_id', 'length', 'max'=>19),
			array('app_store_id', 'length', 'max'=>50),
			array('bundle_identifier, provider', 'length', 'max'=>100),
			array('app_version', 'length', 'max'=>20),
			array('icon, url, intro', 'length', 'max'=>250),
	        array('icon, url', 'url'),
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
			'slot_id' => '广告位ID',
			'width' => '宽度',
			'height' => '高度',
			'app_store_id' => '应用ID',
			'bundle_identifier' => '应用标识符',
			'app_version' => '应用版本',
			'provider' => '提供者',
			'icon' => '图标',
			'url' => '链接地址',
			'intro' => '备注',
			'state' => '状态',
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * @return AppAdvert the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}