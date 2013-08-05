<?php

/**
 * This is the model class for table "{{config}}".
 *
 * The followings are the available columns in table '{{config}}':
 * @property integer $id
 * @property string $name
 * @property integer $category_id
 * @property string $config_name
 * @property string $config_value
 * @property string $desc
 */
class Config extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Config the static model class
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
		return TABLE_CONFIG;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
	        array('name, config_name', 'required'),
	        array('config_name', 'unique'),
	        array('config_name', 'match', 'pattern'=>'/^[a-z][\w\d\_]{4,99}/i', 'message'=>'参数名只能是字母数字下划线组合，且只能以字母开头'),
	        array('category_id', 'numerical', 'integerOnly'=>true),
			array('config_name', 'length', 'max'=>100),
			array('config_value, desc', 'safe'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
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
	        'name' => '参数名称',
			'category_id' => '分类',
			'config_name' => '参数变量名',
			'config_value' => '参数值',
			'desc' => '描述',
		);
	}


}

