<?php

/**
 * This is the model class for table "{{post}}".
 *
 * The followings are the available columns in table '{{post}}':
 * @property integer $id
 * @property integer $category_id
 * @property integer $topic_id
 * @property string $title
 * @property string $content
 * @property integer $create_time
 * @property integer $comment_nums
 * @property integer $state
 */
class Post extends CActiveRecord
{
    const STATE_DISABLED = 0;
    const STATE_ENABLED = 1;
    const STATE_TOP = 2;
    
	/**
	 * Returns the static model of the specified AR class.
	 * @return Post the static model class
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
		return '{{post}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('category_id, topic_id, comment_nums, state, create_time', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>200),
			array('content', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, category_id, topic_id, title, content, create_time, comment_nums, state', 'safe', 'on'=>'search'),
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
			'id' => 'Id',
			'category_id' => 'Category',
			'topic_id' => 'Topic',
			'title' => 'Title',
			'content' => 'Content',
			'create_time' => 'Create Time',
			'comment_nums' => 'Comment Nums',
			'state' => 'State',
		);
	}

	protected function beforeSave()
	{
	    if ($this->getIsNewRecord()) {
	        if (empty($this->content)) {
	            $this->addError('content', '内容必须填写');
    	        return false;
	        }
	        $this->create_time = $_SERVER['REQUEST_TIME'];
	        $this->title = mb_substr($this->content, 0, 20, app()->charset);
	        $this->comment_nums = 0;
	        $this->state = Post::STATE_DISABLED;
	    }
	    return true;
	}
	
	public function getUrl()
	{
	    return aurl('post/show', array('id' => $this->id));
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

		$criteria->compare('category_id',$this->category_id,true);

		$criteria->compare('topic_id',$this->topic_id,true);

		$criteria->compare('title',$this->title,true);

		$criteria->compare('content',$this->content,true);

		$criteria->compare('create_time',$this->create_time,true);

		$criteria->compare('comment_nums',$this->comment_nums,true);

		$criteria->compare('state',$this->state);

		return new CActiveDataProvider('Post', array(
			'criteria'=>$criteria,
		));
	}
}