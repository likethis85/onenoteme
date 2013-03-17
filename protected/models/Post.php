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
 * @property integer $favorite_count
 * @property integer $user_id
 * @property string $user_name
 * @property string $tags
 * @property integer $state
 * @property string $content
 * @property string $thumbnail_pic
 * @property string $bmiddle_pic
 * @property string $original_pic
 * @property integer $thumbnail_width
 * @property integer $thumbnail_height
 * @property integer $bmiddle_width
 * @property integer $bmiddle_height
 * @property integer $original_width
 * @property integer $original_height
 * @property string $gif_animation
 * @property string $weibo_id
 * @property integer $istop
 * @property integer $homeshow
 * @property integer $recommend
 * @property integer $hottest
 * @property integer $disable_comment
 *
 * @property User $user
 * @property UserProfile $profile
 * @property array $comments
 * @property string $stateLabel
 * @property string $url
 * @property string $filterSummary
 * @property string $filterContent
 * @property integer $score
 * @property string $downScore
 * @property string $tagArray
 * @property string $tagText
 * @property string $tagLinks
 * @property string $createTime
 * @property string $shortDate
 * @property string $shortTime
 * @property string $authorName
 * @property string $subTitle
 * @property string $titleLink
 * @property string $likeUrl
 * @property string $unlikeUrl
 * @property string $bmiddlePic
 * @property string $originalPic
 * @property string $thumbnail
 * @property string $bmiddleLink
 * @property string $thumbnailLink
 * @property string $videoHtml
 * @property string $videoSourceUrl
 * @property string $imageIsLong
 * @property string $lineCount
 * @property string $baiduShareData
 */
class Post extends CActiveRecord
{
    public static function channels()
    {
        return array(CHANNEL_DUANZI, CHANNEL_LENGTU, CHANNEL_GIRL, CHANNEL_VIDEO);
    }
    
    public static function states()
    {
        return array(POST_STATE_ENABLED, POST_STATE_DISABLED, POST_STATE_UNVERIFY);
    }
    
    public static function stateLabels($state = null)
    {
        $labels = array(
            POST_STATE_ENABLED => '已上线',
            POST_STATE_DISABLED => '未显示',
            POST_STATE_UNVERIFY => '未审核',
        );
        
        return $state === null ? $labels : $labels[$state];
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
			array('channel_id, view_nums, up_score, down_score, comment_nums, disable_comment, gif_animation, state, favorite_count, create_time, user_id, thumbnail_width, thumbnail_height, bmiddle_width, bmiddle_height, original_width, original_height, istop, homeshow, recommend, hottest', 'numerical', 'integerOnly'=>true),
			array('user_name', 'length', 'max'=>50),
			array('weibo_id', 'length', 'max'=>30),
			array('create_ip', 'length', 'max'=>15),
			array('title, tags', 'length', 'max'=>250),
			array('content, thumbnail_pic, bmiddle_pic, original_pic', 'safe'),
		    array('thumbnail_width, thumbnail_height, bmiddle_width, bmiddle_height, original_width, original_height, istop, homeshow, recommend, hottest, favorite_count', 'filter', 'filter'=>'intval'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
		    'user' => array(self::BELONGS_TO, 'User', 'user_id'),
		    'profile' => array(self::BELONGS_TO, 'UserProfile', 'user_id'),
		    'comments' => array(self::HAS_MANY, 'Comment', 'post_id'),
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
			'favorite_count' => '收藏',
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
	        'gif_animation' => 'GIF动画',
	        'istop' => '置顶',
	        'homeshow' => '首页显示',
	        'recommend' => '推荐',
	        'hottest' => '热门',
		    'disable_comment' => '评论',
		);
	}

	public function scopes()
	{
	    return array(
            'homeshow' => array(
                'condition' => 't.homeshow = ' . CD_YES,
            ),
            'published' => array(
                'condition' => 't.state = ' . POST_STATE_ENABLED,
            ),
            'hottest' => array(
                'condition' => 't.hottest = ' . CD_YES,
                'order' => 't.create_time desc',
            ),
            'recommend' => array(
                'condition' => 't.recommend = ' . CD_YES,
                'order' => 't.create_time desc',
            ),
            'recently' => array(
                'condition' => 't.state = ' . POST_STATE_ENABLED,
                'order' => 't.create_time desc',
                'limit' => 10,
            ),
	    );
	}
	
	public function getStateLabel()
	{
	    return self::stateLabels($this->state);
	}
	
	public function getUrl($absolute = true)
	{
	    return $absolute ? aurl('post/show', array('id' => $this->id)) : url('post/show', array('id' => $this->id));
	}
	
	
	public function getSummary($len = 300)
	{
	    $content = strip_tags($this->content, param('summary_html_tags'));
	    return mb_strimwidth($content, 0, $len, '......', app()->charset);
	}
	
	public function getFilterSummary($len = 500)
	{
	    $html = $this->getSummary($len);
	    $content = strip_tags($this->content, param('summary_html_tags'));
	    $moreCount = mb_strlen($content, app()->charset) - $len;
	    // 这里的6是 "......"的长度
	    if ($moreCount > 0) {
    	    $text .= '<i class="cgray">(剩余&nbsp;' . (int)$moreCount . '&nbsp;)</i>&nbsp;&nbsp;<span class="cgreen">继续阅读全文&gt;&gt;&gt;</span>';
    	    $html .= '<br />' . l($text, $this->getUrl(), array('target'=>'_blank', 'class'=>'aright'));
	    }
	    return nl2br($html);
	}
	
	public function getFilterContent()
	{
	    return nl2br(strip_tags($this->content));
	}
	
	public function getScore()
	{
	    return (int)($this->up_score - $this->down_score);
	}

	public function getDownScore()
	{
	    return (int)$this->down_score ? '-' . $this->down_score : $this->down_score;
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
	
	    foreach ($tags as $tag)
	        $data[] = l($tag, aurl($route, array('name'=>$tag)), array('target'=>$target, 'class'=>$class));
	    
	    return join($operator, $data);
	}

    public function getCreateTime($format = null)
    {
        if (empty($this->create_time))
            return '';
    
        $format = $format ? $format : param('formatDateTime');
        return date($format, $this->create_time);
    }
    
    public function getShortDate()
    {
        $format = param('formatShortDate');
         
        return $this->getCreateTime($format);
    }
    
    public function getShortTime()
    {
        $format = param('formatShortTime');
         
        return $this->getCreateTime($format);
    }
    
    public function getAuthorName()
    {
        return $this->user_name ? $this->user_name : user()->guestName;
    }
    
    public function getAuthorNameLink($htmlOptions = array('target'=>'_blank'))
    {
        $html = $this->getAuthorName();
        if ($this->user_id && $this->user) {
            $url = CDBase::userHomeUrl($this->user_id);
            $html = l($this->getAuthorName(), $url, $htmlOptions);
        }
        
        return $html;
    }
    
    public function getAuthorAvatar($htmlOptions = array('target'=>'_blank'))
    {
        $imageUrl = sbu(USER_DEFAULT_AVATAR_URL);
        if ($this->user_id && $this->profile)
            $imageUrl = $this->profile->getSmallAvatarUrl();

        $html = image($imageUrl, $this->getAuthorName(), $htmlOptions);
        
        return $html;
    }
    
    public function getSubTitle($len = 35)
    {
        $title = $this->title;
        if ($len > 0)
            $title = mb_strimwidth($title, 0, $len, '...', app()->charset);
        
        return $title;
    }
    
    public function getTitleLink($len = 0, $target = '_blank')
    {
        $title = $this->getSubTitle($len);
        return l(h($title), $this->getUrl(), array('target'=>$target));
    }

    public function getLikeUrl()
    {
        return aurl('post/like', array('id'=>$this->id));
    }
    
    public function getUnlikeUrl()
    {
        return aurl('post/unlike', array('id'=>$this->id));
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
    
    public function getThumbnailImage()
    {
        $html = '';
        if ($this->getThumbnail()) {
            $htmlOptions = array('class'=>'cd-thumbnail');
            if ($this->thumbnail_width) $htmlOptions['width'] = $this->thumbnail_width;
            if ($this->thumbnail_height > 0) $htmlOptions['height'] = $this->thumbnail_height;
            $html = image($this->getThumbnail(), $this->title, $htmlOptions);
        }
    
        return $html;
    }
    
    public function getBmiddleLink($target = '_blank')
    {
        $html = '';
        if ($this->getBmiddlePic())
            $html = l(image($this->getBmiddlePic(), $this->title, array('class'=>'cd-bmiddle')), $this->getUrl(), array('target'=>$target));
        
        return $html;
    }
    
    public function getThumbnailLink($target = '_blank')
    {
        $html = '';
        if ($this->getThumbnail()) {
            $htmlOptions = array('class'=>'cd-thumbnail');
            if ($this->thumbnail_width) $htmlOptions['width'] = $this->thumbnail_width;
            if ($this->thumbnail_height > 0) $htmlOptions['height'] = $this->thumbnail_height;
            $html = l(image($this->getThumbnail(), $this->title, $htmlOptions), $this->getUrl(), array('target'=>$target));
        }
        
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
    
    public function getImageIsLong()
    {
        if (($this->channel_id == CHANNEL_GIRL || $this->channel_id == CHANNEL_LENGTU)
            && ($this->bmiddle_height > IMAGE_THUMBNAIL_HEIGHT) && ($this->bmiddle_height > IMAGE_MAX_HEIGHT_FOLDING) && $this->getBmiddlePic())
            return true;
        else
            return false;
    }
    
    public function getLineCount()
    {
        $count = 0;
        if ($this->getImageIsLong()) {
            $count = ($this->bmiddle_height - IMAGE_MAX_HEIGHT_FOLDING) / IMAGE_MAX_HEIGHT_FOLDING;
        }
        return (int)$count;
    }

    public function getBaiduShareJsonData()
    {
        $data = array(
            'url' => $this->getUrl(),
            'text' => '转自@挖段子网：' . strip_tags(trim($this->content)),
        );
        
        $pic = $this->getBmiddlePic();
        if (CDBase::isHttpUrl($pic))
            $data['pic'] = $pic;
        
        return json_encode($data);
    }
    
    public function fetchRemoteImagesBeforeSave()
    {
        $url = strip_tags(trim($this->original_pic));
        if (!empty($url) && stripos($url, fbu()) === false) {
            $thumbWidth = IMAGE_THUMBNAIL_WIDTH;
            $thumbHeight = IMAGE_THUMBNAIL_HEIGHT;
            if ($this->channel_id == CHANNEL_GIRL) {
                $thumbWidth = GIRL_THUMBNAIL_WIDTH;
                $thumbHeight = GIRL_THUMBNAIL_HEIGHT;
            }
        
            $images = CDBase::saveRemoteImages($url, $thumbWidth, $thumbHeight, $this->channel_id == CHANNEL_GIRL);
            
            if ($images) {
                $this->thumbnail_pic = $images[0]['url'];
                $this->thumbnail_width = $images[0]['width'];
                $this->thumbnail_height = $images[0]['height'];
                $this->bmiddle_pic = $images[1]['url'];
                $this->bmiddle_width = $images[1]['width'];
                $this->bmiddle_height = $images[1]['height'];
                $this->original_pic = $images[2]['url'];
                $this->original_width = $images[2]['width'];
                $this->original_height = $images[2]['height'];
                $this->gif_animation = $images[3];
                return true;
            }
            else
                return false;
        }
        else
            return true;
    }
    
    public function fetchRemoteImagesAfterSave()
    {
        if ($this->fetchRemoteImagesBeforeSave()) {
            $attributes = array('thumbnail_pic', 'thumbnail_width', 'thumbnail_height', 'bmiddle_pic', 'bmiddle_width', 'bmiddle_height', 'original_pic', 'original_width', 'original_height');
            return $this->save(true, $attributes);
        }
        else
            return false;
    }
    
    public static function todayUpdateCount()
    {
        $duration = 300;
        $time = $_SERVER['REQUEST_TIME'] - 60*60*24;
        $count = app()->getDb()->cache($duration)->createCommand()
            ->select('count(*)')
            ->from(TABLE_POST)
            ->where('create_time > :today', array(':today'=>$time))
            ->queryScalar();
        
        return (int)$count;
    }
    
    public static function allCount()
    {
        $duration = 300;
        $count = app()->getDb()->cache($duration)->createCommand()
            ->select('count(*)')
            ->from(TABLE_POST)
            ->queryScalar();
        
        return (int)$count;
    }
    
    protected function beforeSave()
    {
        if ($this->getIsNewRecord()) {
            $this->create_time = $_SERVER['REQUEST_TIME'];
	        $this->create_ip = CDBase::getClientIp();
            $this->comment_nums = 0;
        }
        
        $this->user_name = strip_tags(trim($this->user_name));
        if (empty($this->title))
            $this->title = mb_substr($this->content, 0, 30, app()->charset);
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

    protected function afterDelete()
    {
        foreach ($this->comments as $model) $model->delete();
        app()->getDb()->createCommand()
            ->delete(TABLE_POST_FAVORITE, 'post_id = :postid', array(':postid' => $this->id));
        app()->getDb()->createCommand()
            ->delete(TABLE_POST_TAG, 'post_id = :postid', array(':postid' => $this->id));
    }
}


