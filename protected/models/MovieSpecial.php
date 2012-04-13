<?php

/**
 * This is the model class for table "{{movie_special}}".
 *
 * The followings are the available columns in table '{{movie_special}}':
 * @property integer $id
 * @property string $name
 * @property integer $movie_nums
 * @property integer $state
 * @property integer $orderid
 */
class MovieSpecial extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return MovieSpecial the static model class
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
		return '{{movie_special}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
	        array('name', 'required'),
	        array('name', 'unique'),
			array('movie_nums, state, orderid', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>100),
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
			'name' => '专题名称',
			'movie_nums' => '视频数量',
			'state' => '状态',
			'orderid' => '排序',
		);
	}

}