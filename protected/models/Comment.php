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
 * @property integer $recommend
 * @property integer $report_count
 * @property integer $source
 *
 * @property string $authorName
 * @property string $filterContent
 * @property string $stateLabel
 * @property Post $post
 * @property User $user
 */
class Comment extends CActiveRecord
{
    public static function states()
    {
        return array(COMMENT_STATE_ENABLED, COMMENT_STATE_DISABLED);
    }
    
    public static function stateLabels($state = null)
    {
        $labels = array(
            COMMENT_STATE_ENABLED => '已显示',
            COMMENT_STATE_DISABLED => '未显示',
        );
    
        return $state === null ? $labels : $labels[$state];
    }
    
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
	    $commentMinLen = param('comment_min_length') ? param('comment_min_length') : 2;
		return array(
	        array('content', 'required'),
			array('state, post_id, user_id, create_time, up_score, down_score, recommend, report_count, source', 'numerical', 'integerOnly'=>true),
			array('user_name', 'length', 'max'=>50),
			array('create_ip', 'length', 'max'=>15),
			array('content', 'length', 'min'=>$commentMinLen, 'max'=>2000),
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
	        'user' => array(self::BELONGS_TO, 'User', 'user_id',
	            'select' => array('id', 'username', 'screen_name', 'create_time', 'create_ip', 'state', 'token', 'token_time', 'source')),
		);
	}

	public function scopes()
	{
	    return array(
    	    'recently' => array(
        	    'order' => 't.create_time desc',
        	    'limit' => 10,
    	    ),
    	    'recommend' => array(
        	    'condition' => 't.recommend = ' .  CD_YES,
        	    'order' => 't.create_time desc',
    	    ),
    	    'noverify' => array(
        	    'condition' => 't.state = ' .  COMMENT_STATE_DISABLED,
    	    ),
    	    'published' => array(
        	    'condition' => 't.state = ' .  COMMENT_STATE_ENABLED,
        	    'order' => 't.create_time desc',
    	    ),
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
		    'recommend' => '推荐',
		    'report_count' => '举报次数',
	        'source' => '来源',
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
	
	public static function fetchListByPostID($postid, $page = 1, $count = null)
	{
	    $postid = (int)$postid;
	    $page = (int)$page;
	    $count = (int)$count;
	    if ($count === 0)
	        $count = param('commentCountOfPage');
	    
	    $criteria = new CDbCriteria();
	    $criteria->order = 'create_time asc';
	    $criteria->limit = $count;
	    $offset = ($page - 1) * $criteria->limit;
	    $criteria->offset = $offset;
	    $criteria->addColumnCondition(array(
            'post_id' => $postid,
            'state' => COMMENT_STATE_ENABLED,
	    ));
	
	    $comments = self::model()->findAll($criteria);
	    return $comments;
	}
	
	public function getStateLabel()
	{
	    return self::stateLabels($this->state);
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


