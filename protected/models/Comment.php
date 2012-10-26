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
 * @property integer $up_score
 * @property integer $down_score
 * @property integer $state
 *
 * @property string $authorName
 * @property string $filterContent
 */
class Comment extends CActiveRecord
{
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
		return array(
	        array('content', 'required'),
			array('state, post_id, user_id, create_time, up_score, down_score', 'numerical', 'integerOnly'=>true),
			array('user_name', 'length', 'max'=>50),
			array('create_ip', 'length', 'max'=>15),
			array('content', 'safe'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
	        'post' => array(self::BELONGS_TO, 'Post', 'post_id'),
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
			'post_id' => '段子',
			'content' => '内容',
			'user_id' => '用户ID',
			'user_name' => '用户名',
			'create_time' => '创建时间',
			'create_ip' => '创建IP',
			'state' => '状态',
		);
	}

	public function getAuthorName()
	{
	    return $this->user_name ? $this->user_name : user()->guestName;
	}
	
	public function getAuthorHomeUrl()
	{
	     return aurl('user/home');
	}
	
	public function getFilterContent()
	{
	    return strip_tags(trim($this->content), '<fieldset><legend>');
	}
	
	public function getCreateTime($format = '')
	{
	    if (empty($this->create_time)) return '';
	    
	    if (empty($format))
	        $format = param('formatShortDateTime');
	    
	    return date($format, $this->create_time);
	}

	public function getScore()
	{
	    return (int)($this->up_score - $this->down_score);
	}
	
	public function fetchList($postid, $page = 1)
	{
	    $postid = (int)$postid;
	    $criteria = new CDbCriteria();
	    $criteria->order = 'create_time asc';
	    $criteria->limit = param('commentCountOfPage');
	    $offset = ($page - 1) * $criteria->limit;
	    $criteria->offset = $offset;
	    $criteria->addColumnCondition(array(
            'post_id' => $postid,
            'state' => COMMENT_STATE_ENABLED,
	    ));
	
	    $comments = $this->findAll($criteria);
	    return $comments;
	}
	
	protected function beforeSave()
	{
	    if ($this->getIsNewRecord()) {
	        $this->create_time = $_SERVER['REQUEST_TIME'];
	        $this->create_ip = CDBase::getClientIp();
	    }
	    $this->content = strip_tags(trim($this->content), '<fieldset><legend>');
	    
	    return true;
	}
	
	protected function afterSave()
	{
	    if ($this->getIsNewRecord()) {
	        $counters = array('comment_nums' => 1);
	        Post::model()->updateCounters($counters, 'id = :postid', array(':postid'=>$this->post_id));
	    }
	}
	
	protected function afterDelete()
	{
        $counters = array('comment_nums' => -1);
        Post::model()->updateCounters($counters, 'id = :postid', array(':postid'=>$this->post_id));
	}
}


