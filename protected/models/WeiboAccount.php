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
		return '{{weibo_account}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('last_time, last_pid', 'numerical', 'integerOnly'=>true),
			array('display_name', 'length', 'max'=>250),
			array('last_pid', 'length', 'max'=>30),
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
			'id' => 'ID',
			'display_name' => 'Display Name',
			'last_time' => 'Last Time',
			'last_pid' => 'Last Pid',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('display_name',$this->display_name,true);
		$criteria->compare('last_time',$this->last_time);
		$criteria->compare('last_pid',$this->last_pid,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}