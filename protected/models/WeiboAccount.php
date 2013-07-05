<?php

/**
 * This is the model class for table "{{weibo_account}}".
 *
 * The followings are the available columns in table '{{weibo_account}}':
 * @property integer $id
 * @property string $display_name
 * @property integer $last_time
 * @property string $last_pid
 * @property integer $user_id
 * @property string $user_name
 * @property integer $post_nums
 * @property string $desc
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
			array('last_time, user_id, post_nums', 'numerical', 'integerOnly'=>true),
			array('display_name, desc', 'length', 'max'=>250),
			array('last_pid, user_name', 'length', 'max'=>50),
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
			'display_name' => '微博名字',
			'last_time' => '最后抓取时间',
			'last_pid' => '最后微博ID',
			'user_id' => '绑定用户ID',
			'user_name' => '绑定用户名',
			'post_nums' => '数量',
			'desc' => '备注',
		);
	}

}