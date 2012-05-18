<?php

/**
 * This is the model class for table "{{weibo_account}}".
 *
 * The followings are the available columns in table '{{weibo_account}}':
 * @property integer $id
 * @property string $display_name
 * @property integer $last_time
 * @property string $last_pid
 */
class WeiboAccount extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return WeiboAccount the static model class
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
		return TABLE_WEIBO_ACCOUNT;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
	        array('display_name', 'required'),
			array('last_time', 'numerical', 'integerOnly'=>true),
			array('display_name', 'length', 'max'=>250),
			array('last_pid', 'length', 'max'=>30),
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
			'display_name' => 'Display Name',
			'last_time' => 'Last Time',
			'last_pid' => 'Last Pid',
		);
	}

}