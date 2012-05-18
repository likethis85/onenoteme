<?php

/**
 * This is the model class for table "{{comment}}".
 *
 * The followings are the available columns in table '{{comment}}':
 * @property integer $id
 * @property integer $post_id
 * @property string $content
 * @property integer $user_id
 * @property string $user_name
 * @property integer $create_time
 * @property string $create_ip
 * @property integer $state
 */
class Comment extends CActiveRecord
{
    const STATE_DISABLED = 0;
    const STATE_ENABLED = 1;
    
	/**
	 * Returns the static model of the specified AR class.
	 * @return Comment the static model class
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
		return TABLE_COMMENT;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('state, post_id, user_id, create_time', 'numerical', 'integerOnly'=>true),
			array('user_name', 'length', 'max'=>50),
			array('create_ip', 'length', 'max'=>15),
			array('content', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, post_id, content, user_id, user_name, create_time, create_ip, state', 'safe', 'on'=>'search'),
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
			'post_id' => 'Post',
			'content' => 'Content',
			'user_id' => 'User',
			'user_name' => 'User Name',
			'create_time' => 'Create Time',
			'create_ip' => 'Create Ip',
			'state' => 'State',
		);
	}

	protected function beforeSave()
	{
	    if ($this->getIsNewRecord()) {
	        $this->content = strip_tags(trim($this->content));
	        $this->create_time = $_SERVER['REQUEST_TIME'];
	        $this->create_ip = CDBase::getClientIp();
	    }
	    
	    return true;
	}
	
	protected function afterSave()
	{
	    if ($this->getIsNewRecord()) {
	        $counters = array('comment_nums' => 1);
	        Post::model()->updateCounters($counters, 'id = :postid', array(':postid'=>$this->post_id));
	    }
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

		$criteria->compare('post_id',$this->post_id,true);

		$criteria->compare('content',$this->content,true);

		$criteria->compare('user_id',$this->user_id,true);

		$criteria->compare('user_name',$this->user_name,true);

		$criteria->compare('create_time',$this->create_time,true);

		$criteria->compare('create_ip',$this->create_ip,true);

		$criteria->compare('state',$this->state);

		return new CActiveDataProvider('Comment', array(
			'criteria'=>$criteria,
		));
	}
}