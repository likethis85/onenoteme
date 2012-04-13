<?php

/**
 * This is the model class for table "{{movie}}".
 *
 * The followings are the available columns in table '{{movie}}':
 * @property integer $id
 * @property integer $category_id
 * @property string $name
 * @property integer $sets_nums
 * @property string $icon
 * @property integer $create_time
 * @property integer $update_time
 * @property integer $current_nums
 * @property integer $mark
 * @property integer $mark_nums
 * @property integer $view_nums
 * @property integer $orderid
 * @property integer $state
 */
class Movie extends CActiveRecord
{
    const STATE_DISABLED = 0;
    const STATE_ENABLED = 1;
    
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Movie the static model class
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
		return '{{movie}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
	        array('category_id, name, sets_nums', 'required'),
	        array('name', 'unique'),
			array('name', 'length', 'max'=>50),
			array('icon', 'length', 'max'=>250),
			array('category_id, mark, mark_nums, view_nums, orderid, sets_nums, create_time, update_time, current_nums, state', 'numerical', 'integerOnly'=>true),
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
	        'category' => array(self::BELONGS_TO, 'MovieCategory', 'category_id'),
	        'sets' => array(self::HAS_MANY, 'MovieSets', 'movie_id', 'order'=>'id asc'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'category_id' => '分类',
			'name' => '电影名',
			'sets_nums' => '总集数',
			'icon' => '图标',
			'create_time' => '添加时间',
			'update_time' => '最后更新时间',
			'current_nums' => '当前更新集数',
			'mark' => '评分',
			'mark_nums' => '评分次数',
			'view_nums' => '查看次数',
			'orderid' => '排序',
			'state' => '状态',
		);
	}

}