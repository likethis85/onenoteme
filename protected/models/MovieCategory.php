<?php

/**
 * This is the model class for table "{{movie_category}}".
 *
 * The followings are the available columns in table '{{movie_category}}':
 * @property integer $id
 * @property integer $type_id
 * @property string $name
 * @property string $icon
 * @property integer $video_nums
 * @property integer $view_nums
 * @property integer $orderid
 */
class MovieCategory extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return MovieCategory the static model class
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
		return '{{movie_category}}';
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
			array('type_id, view_nums, orderid, video_nums', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>50),
			array('icon', 'length', 'max'=>250),
			array('icon', 'file'),
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
			'type_id' => '类型',
			'name' => '名称',
			'icon' => '图标',
			'video_nums' => '视频数量',
			'view_nums' => '查看次数',
			'orderid' => '排序',
		);
	}
}