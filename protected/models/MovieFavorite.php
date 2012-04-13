<?php

/**
 * This is the model class for table "{{movie_favicon}}".
 *
 * The followings are the available columns in table '{{movie_favicon}}':
 * @property integer $id
 * @property integer $movie_id
 * @property string $movie_name
 * @property integer $user_id
 */
class MovieFavorite extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return MovieFavicon the static model class
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
		return '{{movie_favorite}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
	        array('movie_id, movie_name', 'required'),
	        array('movie_id', 'unique', 'message'=>'该电影已经收藏了'),
			array('movie_name', 'length', 'max'=>50),
			array('movie_id, user_id', 'numerical', 'integerOnly'=>true),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
	        'movie' => array(self::BELONGS_TO, 'Movie', 'movie_id'),
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
			'movie_id' => '电影ID',
			'movie_name' => '电影名称',
			'user_id' => '用户ID',
		);
	}
}