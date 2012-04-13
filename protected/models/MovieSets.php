<?php

/**
 * This is the model class for table "{{movie_sets}}".
 *
 * The followings are the available columns in table '{{movie_sets}}':
 * @property integer $id
 * @property integer $movie_id
 * @property string $name
 * @property string $url
 * @property string $story
 * @property integer $create_time
 * @property integer $view_nums
 */
class MovieSets extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return MovieSets the static model class
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
		return '{{movie_sets}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
	        array('movie_id, name', 'required'),
	        array('url', 'unique', 'message'=>'此地址的视频已经添加过了'),
	        array('name', 'checkMovieNameUnique'),
			array('name', 'length', 'max'=>50),
			array('movie_id, view_nums, create_time', 'numerical', 'integerOnly'=>true),
			array('url, story', 'safe'),
		);
	}
	
	private function checkMovieNameUnique($attribute, $params)
	{
	    $count = app()->getDb()->createCommand()
	        ->select('count(*)')
	        ->from(TABLE_NAME_MOVIE_SETS)
	        ->where(array('and', 'movie_id = :movieid', 'name = :setname'), array(':movieid'=>$this->movie_id, ':setname'=>$this->name))
	        ->queryScalar();
	    
	    if ($count > 0)
	        $this->addError($attribute, $this->name . '已经添加过了');
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
	        'movie' => array(self::BELONGS_TO, 'Movie', 'movie_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'movie_id' => '电影ID',
			'name' => '单集名称',
			'url' => 'URL地址',
			'story' => '剧情',
			'create_time' => '添加时间',
			'view_nums' => '观看次数',
		);
	}

}