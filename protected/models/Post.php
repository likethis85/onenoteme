<?php

/**
 * This is the model class for table "{{post}}".
 *
 * The followings are the available columns in table '{{post}}':
 * @property integer $id
 * @property integer $channel_id
 * @property integer $category_id
 * @property string $title
 * @property string $thumbnail
 * @property string $pic
 * @property string $big_pic
 * @property integer $create_time
 * @property integer $up_score
 * @property integer $down_score
 * @property integer $comment_nums
 * @property integer $user_id
 * @property string $user_name
 * @property integer $tags
 * @property integer $state
 * @property string $content
 * @property string $video_url
 */
class Post extends CActiveRecord
{
    const STATE_UNVERIFY = -1;
    const STATE_DISABLED = 0;
    const STATE_ENABLED = 1;
    const STATE_TOP = 2;
    
    public $captcha;
    
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
		return TABLE_POST;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
		    array('content', 'required', 'message'=>'段子内容必须填写'),
			array('channel_id, category_id, up_score, down_score, comment_nums, state, create_time, user_id', 'numerical', 'integerOnly'=>true),
			array('title, tags', 'length', 'max'=>200),
			array('user_name', 'length', 'max'=>50),
			array('thumbnail, pic, big_pic', 'length', 'max'=>250),
			array('content, video_url', 'safe'),
			array('pic', 'file', 'allowEmpty'=>true),
			array('captcha', 'captcha', 'captchaAction'=>'captcha', 'on'=>'insert', 'allowEmpty'=>true),
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
		    'channel_id' => '频道',
			'category_id' => '分类',
			'title' => '标题',
		    'thumbnail' => '缩略图',
		    'pic' => '图片',
		    'big_pic' => '原图',
			'create_time' => '发布时间',
		    'up_score' => '顶数',
		    'down_score' => '浏览',
			'comment_nums' => '评论',
		    'user_id' => '用户ID',
		    'user_name' => '名字',
			'state' => '状态',
		    'tags' => '标签',
			'content' => '内容',
		    'video_url' => '视频URL',
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
            $this->tags = DTag::filterTags($this->tags);
            $this->user_name = strip_tags(trim($this->user_name));
	    }
	    return true;
	}
	
	protected function afterSave()
	{
	    if ($this->getIsNewRecord()) {
	        self::savePostTags($this->id, $this->tags);
	    }
	}
	
	public function getUrl()
	{
	    return aurl('post/show', array('id' => $this->id));
	}

	/**
     * 获取标签的数组形式
     * @return array
     */
    public function getTagsArray()
    {
        if (empty($this->tags))
            return array();
            
        $tags = DTag::filterTags($this->tags);
        return explode(',', $tags);
    }
    
    
    public static function savePostTags($postid, $tags)
    {
        $postid = (int)$postid;
        if (0 === $postid || empty($tags))
            return false;

        if (is_string($tags)) {
            $tags = Dtag::filterTags($tags);
            $tags = explode(',', $tags);
        }

        $count = 0;
        foreach ((array)$tags as $v) {
            $model = Tag::model()->findByAttributes(array('name'=>$v));
            if ($model === null) {
                $model = new Tag();
                $model->name = $v;
                $model->post_nums = 1;
                if ($model->save())
                    $count++;
            }
            else {
                $model->post_nums = $model->post_nums + 1;
                $model->save(array('post_nums'));
            }
            $columns = array('post_id'=>$postid, 'tag_id'=>$model->id);
            app()->getDb()->createCommand()->insert(TABLE_POST_TAG, $columns);
            unset($model);
        }
        return $count;
    }

    
    public function getCreateTime($format = null)
    {
        if (empty($this->create_time))
            return '';
    
        $format = $format ? $format : param('formatDateTime');
        return date($format, $this->create_time);
    }
    
    public function getPostUserName()
    {
        return $this->user_name ? $this->user_name : user()->guestName;
    }
    
    public function getTitleLink($target = '_blank')
    {
        return l(h($this->title), $this->getUrl(), array('target'=>$target));
    }
    
    public function getPicture()
    {
        if ($this->big_pic)
            return $this->big_pic;
        elseif ($this->pic)
            return $this->pic;
        else
            return '';
    }
}


