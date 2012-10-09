<?php

/**
 * This is the model class for table "{{post}}".
 *
 * The followings are the available columns in table '{{post}}':
 * @property integer $id
 * @property integer $channel_id
 * @property string $title
 * @property integer $create_time
 * @property integer $create_ip
 * @property integer $view_nums
 * @property integer $up_score
 * @property integer $down_score
 * @property integer $comment_nums
 * @property integer $user_id
 * @property string $user_name
 * @property integer $tags
 * @property integer $state
 * @property string $content
 * @property string $thumbnail_pic
 * @property string $bmiddle_pic
 * @property string $original_pic
 * @property string $thumbnail_width
 * @property string $thumbnail_height
 * @property string $bmiddle_width
 * @property string $bmiddle_height
 * @property string $original_width
 * @property string $original_height
 * @property string $weibo_id
 * @property string $url
 * @property string $filterContent
 * @property integer $score
 * @property string $tagArray
 * @property string $tagText
 * @property string $tagLinks
 * @property string $createTime
 * @property string $authorName
 * @property string $titleLink
 * @property string $bmiddlePic
 * @property string $originalPic
 * @property string $thumbnail
 * @property string $bmiddleLink
 * @property string $thumbnailLink
 * @property string $videoHtml
 * @property string $videoSourceUrl
 */
class Post extends CActiveRecord
{
    public static function channels()
    {
        return array(CHANNEL_DUANZI, CHANNEL_LENGTU, CHANNEL_GIRL, CHANNEL_VIDEO);
    }
    
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
			array('channel_id, view_nums, up_score, down_score, comment_nums, state, create_time, user_id, thumbnail_width, thumbnail_height, bmiddle_width, bmiddle_height, original_width, original_height', 'numerical', 'integerOnly'=>true),
			array('user_name', 'length', 'max'=>50),
			array('weibo_id', 'length', 'max'=>30),
			array('create_ip', 'length', 'max'=>15),
			array('title, tags', 'length', 'max'=>250),
			array('content, thumbnail_pic, bmiddle_pic, original_pic', 'safe'),
		    array('thumbnail_width, thumbnail_height, bmiddle_width, bmiddle_height, original_width, original_height', 'filter', 'filter'=>'intval'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
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
			'title' => '标题',
	        'view_nums' => '浏览',
	        'up_score' => '顶数',
		    'down_score' => '浏览',
			'comment_nums' => '评论',
		    'user_id' => '用户ID',
		    'user_name' => '名字',
			'state' => '状态',
		    'tags' => '标签',
		    'thumbnail_pic' => '缩略图',
		    'bmiddle_pic' => '图片',
		    'original_pic' => '原图',
			'create_time' => '发布时间',
			'content' => '内容',
            'weibo_id' => '新浪微博ID',
    		'thumbnail_width' => '缩略图宽度',
    		'thumbnail_height' => '缩略图高度',
    		'bmiddle_width' => '大图宽度',
    		'bmiddle_height' => '大图高度',
    		'original_width' => '原图宽度',
    		'original_height' => '原图调度',
		);
	}
	
	public function getUrl()
	{
	    return aurl('post/show', array('id' => $this->id));
	}
	
	public function getFilterContent()
	{
	    return strip_tags($this->content);
	}
	
	public function getScore()
	{
	    return (int)($this->up_score - $this->down_score);
	}

	/**
	 * 获取标签的数组形式
	 * @return array
	 */
	public function getTagArray()
	{
	    return Tag::filterTagsArray($this->tags);
	}
	
	public function getTagText($operator = '&nbsp;')
	{
	    $tagsArray = $this->getTagArray();
	     
	    return (empty($tagsArray)) ? '' : join($operator, $tagsArray);
	}
	
	public function getTagLinks($route = 'tag/posts', $operator = '&nbsp;', $target = '_blank', $class='beta-tag')
	{
	    $tags = $this->getTagArray();
	    if (empty($tags)) return '';
	
	    foreach ($tags as $tag) {
	        $tagHtml = '<strong>' . $tag . '</strong>';
	        $data[] = l($tagHtml, aurl($route, array('name'=>urlencode($tag))), array('target'=>$target, 'class'=>$class));
	    }
	    
	    return join($operator, $data);
	}

    public function getCreateTime($format = null)
    {
        if (empty($this->create_time))
            return '';
    
        $format = $format ? $format : param('formatDateTime');
        return date($format, $this->create_time);
    }
    
    public function getAuthorName()
    {
        return $this->user_name ? $this->user_name : user()->guestName;
    }
    
    public function getTitleLink($target = '_blank')
    {
        return l(h($this->title), $this->getUrl(), array('target'=>$target));
    }
    
    public function getBmiddlePic()
    {
        if ($this->bmiddle_pic)
            return $this->bmiddle_pic;
        else
            return '';
    }
    
    public function getOriginalPic()
    {
        if ($this->original_pic)
            return $this->original_pic;
        elseif ($this->bmiddle_pic)
            return $this->bmiddle_pic;
        else
            return '';
    }
    
    public function getThumbnail()
    {
        if ($this->thumbnail_pic)
            return $this->thumbnail_pic;
        elseif ($this->bmiddle_pic)
            return $this->bmiddle_pic;
        else
            return '';
    }
    
    public function getBmiddleLink($target = '_blank')
    {
        $html = '';
        if ($this->getBmiddlePic())
            $html = l(image($this->getBmiddlePic(), $this->title, array('class'=>'bmiddle')), $this->getUrl(), array('target'=>$target));
        
        return $html;
    }
    
    public function getThumbnailLink($target = '_blank')
    {
        $html = '';
        if ($this->getThumbnail())
            $html = l(image($this->getThumbnail(), $this->title, array('class'=>'thumbnail')), $this->getUrl(), array('target'=>$target));
        
        return $html;
    }

    public function getVideoHtml()
    {
        $html = '';
        if ($this->channel_id == CHANNEL_VIDEO && $this->bmiddle_pic)
            $html = $this->bmiddle_pic;
        
        return $html;
    }
    
    public function getVideoSourceUrl()
    {
        $url = '';
        if ($this->channel_id == CHANNEL_VIDEO && $this->original_pic)
            $url = $this->original_pic;
        
        return $url;
    }
    
    protected function beforeSave()
    {
        if ($this->getIsNewRecord()) {
            $this->create_time = $_SERVER['REQUEST_TIME'];
            $this->comment_nums = 0;
        }
        
        $this->user_name = strip_tags(trim($this->user_name));
        if (empty($this->title))
            $this->title = mb_substr($this->content, 0, 20, app()->charset);
        if ($this->tags) {
            $tags = join(',', Tag::filterTagsArray($this->tags));
            $this->tags = $tags;
        }
        return true;
    }
    
    protected function afterSave()
    {
        Tag::savePostTags($this->id, $this->tags);
    }
}


