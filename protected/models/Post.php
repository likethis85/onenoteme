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
 * @property string $original_pic
 * @property integer $original_width
 * @property integer $original_height
 * @property integer $original_frames
 * @property string $weibo_id
 * @property integer $istop
 * @property integer $homeshow
 * @property integer $recommend
 * @property integer $hottest
 * @property integer $disable_comment
 * @property string $extra01
 * @property string $extra02
 * @property string $extra03
 * @property string $extra04
 *
 * @property User $user
 * @property UserProfile $profile
 * @property array $comments
 * @property string $stateLabel
 * @property string $url
 * @property string $filterTitle
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
 *
 * @property string $thumbnail
 * @property string $thumbnailImage
 * @property string $thumbnailLink
 * @property string $fixThumb
 * @property string $fixThumbImage
 * @property string $fixThumbLink
 * @property string $squareThumb
 * @property string $squareThumbImage
 * @property string $squareThumbLink
 * @property string $middlePic
 * @property string $middleImage
 * @property string $middleImageLink
 * @property string $largePic
 * @property string $largeImage
 * @property string $largeImageLink
 * @property string $originalPic
 * @property bool $imageIsAnimation
 *
 * @property string $videoHtml
 * @property string $videoSourceUrl
 * @property string $imageIsLong
 * @property string $lineCount
 * @property string $baiduShareData
 * @property string $channelLabel
 * @property string $channelLabel
 * @property bool $hasTitle
 * @property bool $isJoke
 * @property bool $isLengtu
 * @property bool $isGirl
 * @property bool $isVideo
 * @property bool $isGhost
 * @property bool $isTextType
 * @property bool $isImageType
 * @property bool $isVideoType
 */
class Post extends CActiveRecord
{
    public static function channels()
    {
        return array(CHANNEL_DUANZI, CHANNEL_LENGTU, CHANNEL_GIRL, CHANNEL_VIDEO, CHANNEL_GHOSTSTORY);
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
		$rules = array(
		    array('title, content', 'required', 'message'=>'段子内容必须填写'),
			array('channel_id, view_nums, up_score, down_score, comment_nums, disable_comment, state, favorite_count, create_time, user_id, original_width, original_height, original_frames, istop, homeshow, recommend, hottest', 'numerical', 'integerOnly'=>true),
			array('user_name', 'length', 'max'=>50),
			array('weibo_id', 'length', 'max'=>30),
			array('create_ip', 'length', 'max'=>15),
			array('title, tags', 'length', 'max'=>250),
			array('content, original_pic, extra01, extra02, extra03, extra04', 'safe'),
		    array('original_width, original_height, istop, homeshow, recommend, hottest, favorite_count', 'filter', 'filter'=>'intval'),
		);
		
		if ($this->getIsVideoType())
		    $rules[] = array('extra02, extra03', 'required');
		
		return $rules;
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
	    $extra01 = '备用01';
	    $extra02 = '备用02';
	    $extra03 = '备用03';
	    $extra04 = '备用04';
	    switch ($this->channel_id)
	    {
	        case CHANNEL_VIDEO:
	            $extra01 = '视频HTML5';
	            $extra02 = 'Flash代码';
	            $extra03 = '视频来源';
	            break;
	        default:
	            break;
	    }
	    
		return array(
			'id' => 'ID',
		    'channel_id' => '频道',
			'title' => '标题',
	        'view_nums' => '浏览',
	        'up_score' => '顶数',
		    'down_score' => '踩数',
			'comment_nums' => '评论',
			'favorite_count' => '收藏',
		    'user_id' => '用户ID',
		    'user_name' => '名字',
			'state' => '状态',
		    'tags' => '标签',
			'create_ip' => '发布IP',
			'create_time' => '发布时间',
			'content' => '内容',
            'weibo_id' => '新浪微博ID',
		    'original_pic' => '原图',
    		'original_width' => '原图宽度',
    		'original_height' => '原图调度',
	        'original_frames' => '动画帧数',
	        'istop' => '置顶',
	        'homeshow' => '首页显示',
	        'recommend' => '推荐',
	        'hottest' => '热门',
		    'disable_comment' => '评论',
		    'extra01' => $extra01,
		    'extra02' => $extra02,
		    'extra03' => $extra03,
		    'extra04' => $extra04,
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
	
	public function getFilterSummary($len = 300)
	{
	    $content = strip_tags($this->content, param('summary_html_tags'));
	    $summary = mb_substr($content, 0, $len, app()->charset);
	    $moreCount = mb_strlen($content, app()->charset) - mb_strlen($summary, app()->charset);
	    
	    if ($moreCount > 0) {
	        $summary = mb_strimwidth($content, 0, $len, '......', app()->charset);
    	    $text .= '<i class="cgray">(剩余&nbsp;' . (int)$moreCount . '&nbsp;字)</i>&nbsp;&nbsp;<span class="cgreen">继续阅读全文&gt;&gt;&gt;</span>';
    	    $summary .= '<br />' . l($text, $this->getUrl(), array('target'=>'_blank', 'class'=>'aright'));
	    }
	    $summary = nl2br($summary);
	    return $summary;
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
            $url = CDBaseUrl::userHomeUrl($this->user_id);
            $html = l($this->getAuthorName(), $url, $htmlOptions);
        }
        
        return $html;
    }
    
    public function getAuthorAvatar($htmlOptions = array('target'=>'_blank'))
    {
        $imageUrl = sbu(param('default_mini_avatar'));
        if ($this->user_id && $this->profile)
            $imageUrl = $this->profile->getSmallAvatarUrl();

        $html = image($imageUrl, $this->getAuthorName(), $htmlOptions);
        
        return $html;
    }
    
    public function getFilterTitle()
    {
        return strip_tags(trim($this->title));
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
    
    /**
     * 获取自定义缩略图对象
     * @return Ambigous <NULL, string, CDImageThumb>
     */
    public function getImageThumb()
    {
        static $thumbs = array();
        if (!array_key_exists($this->id, $thumbs)){
            if ($this->original_pic)
                $thumbs[$this->id] = new CDImageThumb($this->original_pic, $this->original_width, $this->original_height);
            else
                $thumbs[$this->id] = '';
        }
        
        return $thumbs[$this->id];
    }
    
    public function getThumbnail()
    {
        $url = '';
        $thumb = $this->getImageThumb();
        if ($thumb)
            $url = $thumb->thumbImageUrl();
        
        return $url;
    }
    
    public function getFixThumb()
    {
        $url = '';
        $thumb = $this->getImageThumb();
        if ($thumb)
            $url = $thumb->fixThumbImageUrl();
        
        return $url;
    }
    
    public function getSquareThumb()
    {
        $url = '';
        $thumb = $this->getImageThumb();
        if ($thumb)
            $url = $thumb->squareThumbImageUrl();
        
        return $url;
    }
    
    public function getMiddlePic()
    {
        $url = '';
        $thumb = $this->getImageThumb();
        if ($thumb)
            $url = $thumb->middleImageUrl();
        
        return $url;
    }
    
    public function getLargePic()
    {
        $url = '';
        $thumb = $this->getImageThumb();
        if ($thumb)
            $url = $thumb->largeImageUrl();
        
        return $url;
    }
    
    public function getOriginalPic()
    {
        return $this->original_pic;
    }
    
    public function getImageIsAnimation()
    {
        return $this->original_frames > 1;
    }
    
    public function getThumbHeightByWidth($width = IMAGE_THUMB_WIDTH)
    {
        $height = 0;
        $thumb = $this->getImageThumb();
        if ($thumb)
            $height = $thumb->heightByWidth($width);
    
        return $height;
    }
    
    public function getThumbWidthByHeight($heigth)
    {
        $width = 0;
        $thumb = $this->getImageThumb();
        if ($thumb)
            $width = $thumb->heightByWidth($heigth);
    
        return $width;
    }
    
    public function getThumbnailImage($width = 0)
    {
        $html = '';
        if ($this->getThumbnail()) {
            $htmlOptions = array('class'=>'cd-thumbnail');
            $width = (int)$width;
            if ($width > 0) {
                $htmlOptions['width'] = $width;
                $htmlOptions['height'] = $this->getThumbHeightByWidth($width);
            }
            $html = image($this->getThumbnail(), $this->title, $htmlOptions);
        }
    
        return $html;
    }
    
    public function getThumbnailLink($target = '_blank', $width = 0)
    {
        $html = '';
        if ($this->getThumbnail())
            $html = l($this->getThumbnailImage($width), $this->getUrl(), array('target'=>$target, 'title'=>$this->getFilterTitle()));
        
        return $html;
    }
    
    public function getFixThumbImage($width = 0)
    {
        $html = '';
        if ($this->getFixThumb()) {
            $htmlOptions = array('class'=>'cd-fixthumb');
            $width = (int)$width;
            if ($width > 0) {
                $htmlOptions['width'] = $width;
                $htmlOptions['height'] = $this->getThumbHeightByWidth($width);
            }
            $html = image($this->getFixThumb(), $this->title, $htmlOptions);
        }
    
        return $html;
    }
    
    public function getFixThumbLink($target = '_blank', $width = 0)
    {
        $html = '';
        if ($this->getFixThumb())
            $html = l($this->getFixThumbImage($width), $this->getUrl(), array('target'=>$target, 'title'=>$this->getFilterTitle()));
        
        return $html;
    }
    
    public function getSquareThumbImage($width = 0)
    {
        $html = '';
        if ($this->getSquareThumb()) {
            $htmlOptions = array('class'=>'cd-fixthumb');
            $width = (int)$width;
            if ($width > 0) {
                $htmlOptions['width'] = $width;
                $htmlOptions['height'] = $this->getThumbHeightByWidth($width);
            }
            $html = image($this->getSquareThumb(), $this->title, $htmlOptions);
        }
    
        return $html;
    }
    
    public function getSquareThumbLink($target = '_blank', $width = 0)
    {
        $html = '';
        if ($this->getSquareThumb())
            $html = l($this->getSquareThumbImage($width), $this->getUrl(), array('target'=>$target, 'title'=>$this->getFilterTitle()));
        
        return $html;
    }
    
    public function getMiddleImage()
    {
        $html = '';
        if ($this->getMiddlePic()) {
            $htmlOptions = array('class'=>'cd-middle-image');
            $html = image($this->getMiddlePic(), $this->title, $htmlOptions);
        }
    
        return $html;
    }
    
    public function getMiddleImageLink($target = '_blank')
    {
        $html = '';
        if ($this->getMiddlePic())
            $html = l($this->getMiddleImage(), $this->getUrl(), array('target'=>$target));
        
        return $html;
    }
    
    public function getLargeImage()
    {
        $html = '';
        if ($this->getLargePic()) {
            $htmlOptions = array('class'=>'cd-large-image');
            $html = image($this->getLargePic(), $this->title, $htmlOptions);
        }
    
        return $html;
    }
    
    public function getLargeImageLink($target = '_blank')
    {
        $html = '';
        if ($this->getLargePic())
            $html = l($this->getLargeImage(), $this->getUrl(), array('target'=>$target));
        
        return $html;
    }

    public function getVideoHtml5Url()
    {
        $url = '';
        if ($this->channel_id == CHANNEL_VIDEO && $this->extra01)
            $url = $this->extra02;
        
        return $url;
    }
    
    public function getVideoHtml()
    {
        $html = '';
        if ($this->channel_id == CHANNEL_VIDEO && $this->extra02)
            $html = $this->extra02;
        
        return $html;
    }
    
    public function getVideoSourceUrl()
    {
        $url = '';
        if ($this->channel_id == CHANNEL_VIDEO && $this->extra03)
            $url = $this->extra03;
        
        return $url;
    }
    
    public function getImageIsLong($width = IMAGE_MIDDLE_WIDTH)
    {
        $middleHeight = (int)$this->getThumbHeightByWidth($width);
        if (($this->channel_id == CHANNEL_GIRL || $this->channel_id == CHANNEL_LENGTU)
            && ($middleHeight > IMAGE_THUMBNAIL_HEIGHT) && ($middleHeight > IMAGE_MAX_HEIGHT_FOLDING) && $this->getMiddlePic())
            return true;
        else
            return false;
    }
    
    public function getLineCount($width = IMAGE_MIDDLE_WIDTH)
    {
        $count = 0;
        if ($this->getImageIsLong($width)) {
            $count = ($this->getThumbHeightByWidth($width) - IMAGE_MAX_HEIGHT_FOLDING) / IMAGE_MAX_HEIGHT_FOLDING;
        }
        return (int)$count;
    }

    public function getBaiduShareJsonData()
    {
        $data = array(
            'url' => $this->getUrl(),
            'text' => '转自@挖段子网：' . strip_tags(trim($this->content)),
        );
        
        $pic = $this->getMiddlePic();
        if (CDBase::isHttpUrl($pic))
            $data['pic'] = $pic;
        
        return json_encode($data);
    }
    
    public function getChannelLabel()
    {
        return CDBase::channelLabels((int)$this->channel_id);
    }

    public function getHasTitle()
    {
        return (mb_stripos($this->content, $this->title, 0, app()->charset) !== 0);
    }
    
    public function getIsJoke()
    {
        return $this->channel_id == CHANNEL_DUANZI;
    }
    
    public function getIsLengtu()
    {
        return $this->channel_id == CHANNEL_LENGTU;
    }
    
    public function getIsGirl()
    {
        return $this->channel_id == CHANNEL_GIRL;
    }
    
    public function getIsVideo()
    {
        return $this->channel_id == CHANNEL_VIDEO;
    }
    
    public function getIsGhost()
    {
        return $this->channel_id == CHANNEL_GHOSTSTORY;
    }
    
    public function getIsTextType()
    {
        return $this->channel_id == CHANNEL_DUANZI || $this->channel_id == CHANNEL_GHOSTSTORY;
    }
    
    public function getIsImageType()
    {
        return $this->channel_id == CHANNEL_LENGTU || $this->channel_id == CHANNEL_GIRL;
    }
    
    public function getIsVideoType()
    {
        return $this->channel_id == CHANNEL_VIDEO;
    }
    
    
    public function fetchRemoteImagesBeforeSave($referer = '', $opts = array())
    {
        $url = strip_tags(trim($this->original_pic));
        if (!empty($url) && CDBase::externalUrl($url)) {
            $image = CDUploadedFile::saveImage(upyunEnabled(), $url, $referer, $opts, 'pics');
            if ($image) {
                $this->original_pic = $image['url'];
                $this->original_width = $image['width'];
                $this->original_height = $image['height'];
                $this->original_frames = (int)$image['frames'];
                return true;
            }
            else
                return false;
        }
        else
            return true;
    }
    
    public function fetchRemoteImagesAfterSave($referer = '')
    {
        if ($this->fetchRemoteImagesBeforeSave($referer)) {
            $attributes = array('original_pic', 'original_width', 'original_height', 'original_frames');
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
    
    public function trash()
    {
        if ($this->getIsNewRecord())
            throw new CException('this is a new record');
        else {
            $this->state = POST_STATE_TRASH;
            $result = $this->save(true, array('state'));
            return $result;
        }
    }
    
    protected function beforeValidate()
    {
        if (empty($this->title))
            $this->title = mb_substr($this->content, 0, 30, app()->charset);
        
        return true;
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
            
	    try {
	        $uploader = uploader(true);
	        if ($this->original_pic) {
	        	$imgPath = str_replace($uploader->domain, '', $this->original_pic);
	        	$imgPath = '/' . ltrim($imgPath, '/');
	        	$uploader->delete($imgPath);
	        }
        }
        catch (Exception $e) {}
    }
}


