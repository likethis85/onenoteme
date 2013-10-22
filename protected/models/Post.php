<?php

/**
 * This is the model class for table "{{post}}".
 *
 * The followings are the available columns in table '{{post}}':
 * @property integer $id
 * @property integer $channel_id
 * @property integer $category_id
 * @property integer $media_type
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
 *
 * @property User $user
 * @property UserProfile $profile
 * @property array $comments
 * @property array $videos
 * @property integer $videoCount
 * @property string $stateLabel
 * @property string $url
 * @property string $filterTitle
 * @property string $plainSummary
 * @property string $filterSummary
 * @property string $filterContent
 * @property string $contentImages
 * @property integer $score
 * @property string $downScore
 * @property string $tagArray
 * @property string $tagText
 * @property string $tagLinks
 * @property string $createTime
 * @property string $shortDate
 * @property string $shortTime
 * @property string $shortDateTime
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
 * @property string $imageIsLong
 * @property string $lineCount
 * @property string $baiduShareData
 * @property string $channelLabel
 * @property string $channelLabel
 * @property bool $hasTitle
 * @property bool $isJoke
 * @property bool $isLengtu
 * @property bool $isTextType
 * @property bool $isImageType
 * @property array $uploadImages
 */
class Post extends CActiveRecord
{
    /**
     * 所有频道列表
     * @deprecated 为了兼容保留，可以直接使用CDBase::channels
     * @see CDBase
     * @return array
     */
    public static function channels()
    {
        return CDBase::channels();
    }
    
    /**
     * 前端能用到的状态列表
     * @return array
     */
    public static function states()
    {
        return array(POST_STATE_ENABLED, POST_STATE_DISABLED, POST_STATE_UNVERIFY);
    }
    
    /**
     * 前端能用到的状态文字描述
     * @param string $state
     * @return Ambigous <multitype:string , string>
     */
    public static function stateLabels($state = null)
    {
        $labels = array(
            POST_STATE_ENABLED => '已上线',
            POST_STATE_DISABLED => '未显示',
            POST_STATE_UNVERIFY => '未审核',
            POST_STATE_TRASH => '回收站',
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
		$rules = array(
		    array('channel_id, media_type, title, content', 'required', 'message'=>'段子内容必须填写'),
			array('channel_id, category_id, media_type, view_nums, up_score, down_score, comment_nums, disable_comment, state, favorite_count, create_time, user_id, original_width, original_height, original_frames, istop, homeshow, recommend, hottest', 'numerical', 'integerOnly'=>true),
			array('user_name', 'length', 'max'=>50),
			array('weibo_id', 'length', 'max'=>30),
			array('create_ip', 'length', 'max'=>15),
			array('title, tags', 'length', 'max'=>250),
			array('content, original_pic', 'safe'),
		    array('original_width, original_height, istop, homeshow, recommend, hottest, favorite_count', 'filter', 'filter'=>'intval'),
		);
		
		return $rules;
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
		    'category' => array(self::BELONGS_TO, 'Category', 'category_id'),
		    'user' => array(self::BELONGS_TO, 'User', 'user_id',
		        'select' => array('id', 'username', 'screen_name', 'create_time', 'create_ip', 'state', 'token', 'token_time', 'source')),
		    'profile' => array(self::BELONGS_TO, 'UserProfile', 'user_id'),
		    'comments' => array(self::HAS_MANY, 'Comment', 'post_id'),
	        'commentCount' => array(self::STAT, 'Comment', 'post_id',),
		    'video' => array(self::HAS_ONE, 'PostVideo', 'post_id'),
		    'videos' => array(self::HAS_MANY, 'PostVideo', 'post_id'),
		    'videoCount' => array(self::STAT, 'PostVideo', 'post_id'),
		    'uploadImages' => array(self::HAS_MANY, 'Upload', 'post_id',
                'condition' => 'file_type = :filetype',
	            'params' => array(':filetype' => Upload::TYPE_IMAGE),
		    ),
	        'uploadImagesCount' => array(self::STAT, 'Upload', 'post_id',
                'condition' => 'file_type = :filetype',
	            'params' => array(':filetype' => Upload::TYPE_IMAGE),
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
		    'channel_id' => '频道',
		    'category_id' => '分类',
		    'media_type' => '类型',
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
		);
	}

	/**
	 * Yii scopes
	 * @see CActiveRecord::scopes()
	 * @return array
	 */
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
	
	/**
	 * 获取状态描述
	 * @return string
	 */
	public function getStateLabel()
	{
	    return self::stateLabels($this->state);
	}
	
	/**
	 * 段子url
	 * @param boolean $absolute 是否返回绝对地址，默认为true
	 * @return string
	 */
	public function getUrl($absolute = true, $trace = null)
	{
	    $params = array('id' => $this->id);
	    $trace = trim(strip_tags($trace));
	    if ($trace)
	        $params['trace'] = $trace;
	    
	    return $absolute ? aurl('post/show', $params) : url('post/show', $params);
	}
	
	/**
	 * 返回纯文字概述
	 * @param integer $len 内容截取长度
	 * @return string
	 */
	public function getPlainSummary($len = 300)
	{
	    $content = trim(strip_tags($this->content));
	    $content = mb_strimwidth($content, 0, $len, '......', app()->charset);
	    return trim($content);
	}
	
	/**
	 * 获取过滤之后的内容概述，末尾添加剩余多少字
	 * @param integer $len 内容截取长度
	 * @return string
	 */
	public function getFilterSummary($len = 300)
	{
	    $content = strip_tags($this->content);
	    $summary = mb_substr($content, 0, $len, app()->charset);
	    $moreCount = mb_strlen($content, app()->charset) - mb_strlen($summary, app()->charset);
	    
	    $tags = param('summary_html_tags');
	    if ($moreCount > 0) {
	    	$content = strip_tags($this->content, $tags);
	        $summary = mb_strimwidth($content, 0, $len, '......', app()->charset);
    	    $text .= '<i class="cgray">(剩余' . (int)$moreCount . '字)</i>&nbsp;&nbsp;<span class="cgreen">继续阅读全文&gt;&gt;&gt;</span>';
    	    $summary .= '<br />' . l($text, $this->getUrl(), array('target'=>'_blank', 'class'=>'aright'));
	    }
	    else
	    	$summary = strip_tags($this->content, $tags);
	    return trim($summary);
	}
	
	/**
	 * 获取过滤之后的内容
	 * @return string
	 */
	public function getFilterContent()
	{
	    $tags = param('content_html_tags');
	    $content = strip_tags($this->content, $tags);
	    return trim($content);
	}
	
	/**
	 * 获取内容中的图片列表
	 * @return boolean|array 图片列表，如果正则匹配出错，返回false
	 */
	public function getContentImages($cache = true)
	{
	    static $data = array();
	    if ($cache && array_key_exists($this->id, $data))
    	    $urls = $data[$this->id];
	    else {
    	    $matches = array();
    	    $pattern = '/<img.*?src="?(.+?)["\s]{1}?.*?>/is';
    	    $result = preg_match_all($pattern, $this->content, $matches);
    	    if ($result === false)
    	        return false;
    	    elseif ($result > 0) {
        	    array_shift($matches);
        	    $urls = $data[$this->id] = (array)array_unique($matches[0]);
    	    }
	    }
	    return $urls;
	}
	
	/**
	 * 获取段子分数
	 * @return integer 分数，笑脸 - 哭脸
	 */
	public function getScore()
	{
	    return (int)($this->up_score - $this->down_score);
	}

	/**
	 * 获取哭脸数
	 * @return integer 哭脸数前面加负号
	 */
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
	
	/**
	 * 获取段子标签列表
	 * @param string $operator 标签分隔，默认为&nbsp;
	 * @return string 标签组成的字符串
	 */
	public function getTagText($operator = '&nbsp;')
	{
	    $tagsArray = $this->getTagArray();
	     
	    return (empty($tagsArray)) ? '' : join($operator, $tagsArray);
	}
	
	/**
	 * 获取标签链接列表
	 * @param string $route 标签段子列表的route，主要同时给手机版使用此方法
	 * @param string $operator 标签分隔符，默认&nbsp;
	 * @param string $target 链接打开页面，默认_blank
	 * @param string $class html标签 class name
	 * @return string
	 */
	public function getTagLinks($route = 'tag/posts', $operator = '&nbsp;', $target = '_blank', $class='beta-tag')
	{
	    $tags = $this->getTagArray();
	    if (empty($tags)) return '';
	
	    foreach ($tags as $tag)
	        $data[] = l($tag, aurl($route, array('name'=>$tag)), array('target'=>$target, 'class'=>$class));
	    
	    return join($operator, $data);
	}

	/**
	 * 发布时间格式化
	 * @param string $format 时间格式，参考date方法
	 * @return string
	 */
    public function getCreateTime($format = null)
    {
        if (empty($this->create_time))
            return '';
    
        $format = $format ? $format : param('formatDateTime');
        return date($format, $this->create_time);
    }
    
    /**
     * 段子发布时间，只包括月和日
     * @return string
     */
    public function getShortDate()
    {
        $format = param('formatShortDate');
         
        return $this->getCreateTime($format);
    }
    
    /**
     * 段子发布时间，只包括月和日
     * @return string
     */
    public function getShortDateTime()
    {
        $format = param('formatShortDateTime');
         
        return $this->getCreateTime($format);
    }
    
    /**
     * 段子发布时间，只包括小时和分钟
     * @return string
     */
    public function getShortTime()
    {
        $format = param('formatShortTime');
         
        return $this->getCreateTime($format);
    }
    
    /**
     * 获取段子发布者的名字
     * @return string 如果发布者未登录，则显示匿名
     */
    public function getAuthorName()
    {
        return $this->user_name ? $this->user_name : user()->guestName;
    }
    
    /**
     * 段子发布者名称链接
     * @param array $htmlOptions 链接A的html属性
     * @return string
     */
    public function getAuthorNameLink($htmlOptions = array('target'=>'_blank'))
    {
        $html = $this->getAuthorName();
        if ($this->user_id && $this->user) {
            $url = CDBaseUrl::userHomeUrl($this->user_id);
            $html = l($this->getAuthorName(), $url, $htmlOptions);
        }
        
        return $html;
    }
    
    /**
     * 段子发布者头像
     * @param array $htmlOptions img标签的html属性
     * @return string 头像的<img>代码
     */
    public function getAuthorAvatar($htmlOptions = array('target'=>'_blank'))
    {
        $imageUrl = sbu(param('default_mini_avatar'));
        if ($this->user_id && $this->profile)
            $imageUrl = $this->profile->getSmallAvatarUrl();

        $html = image($imageUrl, $this->getAuthorName(), $htmlOptions);
        
        return $html;
    }
    
    /**
     * 获取过滤html标签的标题
     * @return string
     */
    public function getFilterTitle()
    {
        $title = strip_tags(trim($this->title));
        if ($this->getIsImageType() && $this->uploadImagesCount > 1)
            $title .= '(' . $this->uploadImagesCount . 'P)';
        return trim($title);
    }
    
    /**
     * 获取子标题
     * @param integer $len 截取长度
     * @return string
     */
    public function getSubTitle($len = 35)
    {
        $title = strip_tags(trim($this->title));
        if ($len > 0)
            $title = mb_strimwidth($title, 0, $len, '...', app()->charset);
        
        if ($this->getIsImageType() && $this->uploadImagesCount > 1)
            $title .= '(' . $this->uploadImagesCount . 'P)';
        
        return $title;
    }
    
    /**
     * 标题链接
     * @param integer $len 截取长度
     * @param string $target 链接打开页面
     * @return string
     */
    public function getTitleLink($len = 0, $target = '_blank')
    {
        if ($this->channel_id == CHANNEL_FOCUS)
            $prepend = '<strong class="corange">【挖热点】</span>';
        else
            $prepend = '';
            
        $title = $this->getSubTitle($len);
        return l($prepend . h($title), $this->getUrl(), array('target'=>$target));
    }

    /**
     * 返回段子喜欢url
     * @return string
     */
    public function getLikeUrl()
    {
        return aurl('post/like', array('id'=>$this->id));
    }
    
    /**
     * 返回段子讨厌url
     * @return string
     */
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
    

    public function getAppThumb()
    {
        $url = '';
        $thumb = $this->getImageThumb();
        if ($thumb)
            $url = $thumb->appThumbUrl();
        
        return $url;
    }

    public function getAppMiddle()
    {
        $url = '';
        $thumb = $this->getImageThumb();
        if ($thumb)
            $url = $thumb->appMiddleUrl();
        
        return $url;
    }
    
    /**
     * 获取缩略图地址，限定宽度，高度自适应
     * @return string
     */
    public function getThumbnail()
    {
        $url = '';
        $thumb = $this->getImageThumb();
        if ($thumb)
            $url = $thumb->thumbImageUrl();
        
        return $url;
    }
    
    /**
     * 获取缩略图，固定长宽，自动放大剪切
     * @return string
     */
    public function getFixThumb()
    {
        $url = '';
        $thumb = $this->getImageThumb();
        if ($thumb)
            $url = $thumb->fixThumbImageUrl();
        
        return $url;
    }
    
    /**
     * 获取正方形缩略图
     * @return string
     */
    public function getSquareThumb()
    {
        $url = '';
        $thumb = $this->getImageThumb();
        if ($thumb)
            $url = $thumb->squareThumbImageUrl();
        
        return $url;
    }
    
    /**
     * 获取中等大小图片
     * @return string
     */
    public function getMiddlePic()
    {
        $url = '';
        $thumb = $this->getImageThumb();
        if ($thumb)
            $url = $thumb->middleImageUrl();
        
        return $url;
    }
    
    /**
     * 获取大图片url
     * @return string
     */
    public function getLargePic()
    {
        $url = '';
        $thumb = $this->getImageThumb();
        if ($thumb)
            $url = $thumb->largeImageUrl();
        
        return $url;
    }
    
    /**
     * 获取原始图片url
     * @return string
     */
    public function getOriginalPic()
    {
        return $this->original_pic;
    }
    
    /**
     * 段子图片是否是gif动图
     * @return boolean
     */
    public function getImageIsAnimation()
    {
        return $this->original_frames > 1;
    }
    
    /**
     * 根据宽度计算缩略图的高度
     * @param integer $width 图片宽度
     * @return integer 图片高度
     */
    public function getThumbHeightByWidth($width = IMAGE_THUMB_WIDTH)
    {
        $height = 0;
        $thumb = $this->getImageThumb();
        if ($thumb)
            $height = $thumb->heightByWidth($width);
    
        return $height;
    }
    
    /**
     * 根据高度计算宽度
     * @param integer $heigth 图片高度
     * @return integer 图片宽度
     */
    public function getThumbWidthByHeight($heigth)
    {
        $width = 0;
        $thumb = $this->getImageThumb();
        if ($thumb)
            $width = $thumb->heightByWidth($heigth);
    
        return $width;
    }
    
    /**
     * 获取图片缩略图<img>代码
     * @param integer $width 图片宽度，如果不为，img标签中不添加width,height属性
     * @return string
     */
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
    
    /**
     * 获取缩略图链接html代码
     * @param string $target 链接打开页面
     * @param integer $width 图片宽度
     * @return string
     */
    public function getThumbnailLink($target = '_blank', $width = 0)
    {
        $html = '';
        if ($this->getThumbnail())
            $html = l($this->getThumbnailImage($width), $this->getUrl(), array('target'=>$target, 'title'=>$this->getFilterTitle()));
        
        return $html;
    }
    
    /**
     * 获取固定宽高的缩略图<img>代码
     * @param integer $width 图片宽度
     * @return string
     */
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
    
    /**
     * 获取固定宽高的缩略图html代码
     * @param string $target 链接打开页面
     * @param integer $width 图片宽度
     * @return string
     */
    public function getFixThumbLink($target = '_blank', $width = 0)
    {
        $html = '';
        if ($this->getFixThumb())
            $html = l($this->getFixThumbImage($width), $this->getUrl(), array('target'=>$target, 'title'=>$this->getFilterTitle()));
        
        return $html;
    }
    
    /**
     * 获取正方形缩略图<img>代码
     * @param integer $width 图片宽度
     * @return string
     */
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
    
    /**
     * 获取正方形缩略图链接代码
     * @param string $target 链接打开页面
     * @param integer $width 图片宽度
     * @return string
     */
    public function getSquareThumbLink($target = '_blank', $width = 0, $trace = '')
    {
        $html = '';
        if ($this->getSquareThumb())
            $html = l($this->getSquareThumbImage($width), $this->getUrl(true, $trace), array('target'=>$target, 'title'=>$this->getFilterTitle()));
        
        return $html;
    }
    
    /**
     * 获取中等大小图片<img>代码
     * @return string
     */
    public function getMiddleImage()
    {
        $html = '';
        if ($this->getMiddlePic()) {
            $htmlOptions = array('class'=>'cd-middle-image bmiddle');
            $html = image($this->getMiddlePic(), $this->title, $htmlOptions);
        }
    
        return $html;
    }
    
    /**
     * 获取中等大小图片链接html代码
     * @param string $target
     * @return string 链接html代码，url为段子url详情页
     */
    public function getMiddleImageLink($target = '_blank')
    {
        $html = '';
        if ($this->getMiddlePic())
            $html = l($this->getMiddleImage(), $this->getUrl(), array('target'=>$target));
        
        return $html;
    }
    
    /**
     * 获取大图片<img>代码
     * @return string
     */
    public function getLargeImage()
    {
        $html = '';
        if ($this->getLargePic()) {
            $htmlOptions = array('class'=>'cd-large-image');
            $html = image($this->getLargePic(), $this->title, $htmlOptions);
        }
    
        return $html;
    }
    
    /**
     * 获取大图片链接html代码
     * @param string $target 页面打开页面
     * @return string
     */
    public function getLargeImageLink($target = '_blank')
    {
        $html = '';
        if ($this->getLargePic())
            $html = l($this->getLargeImage(), $this->getUrl(), array('target'=>$target));
        
        return $html;
    }

    /**
     * 判断图片长度是否超过最大折叠地址
     * @param integer $width
     * @return boolean
     */
    public function getImageIsLong($width = POST_LIST_IMAGE_MAX_WIDTH)
    {
        $middleHeight = (int)$this->getThumbHeightByWidth($width);
        if (($this->getIsImageType())
            && ($middleHeight > IMAGE_THUMBNAIL_HEIGHT)
            && ($middleHeight > IMAGE_MAX_HEIGHT_FOLDING)
            && $this->getMiddlePic())
            return true;
        else
            return false;
    }
    
    /**
     * 如果图片长度超过最大折叠地址，计算长度的位数，显示几条折叠线
     * @param integer $width 图片宽度
     * @return integer
     */
    public function getLineCount($width = POST_LIST_IMAGE_MAX_WIDTH)
    {
        $count = 0;
        if ($this->getImageIsLong($width)) {
            $count = $this->getThumbHeightByWidth($width) / IMAGE_MAX_HEIGHT_FOLDING;
        }
        return (int)$count;
    }

    /**
     * 获取百度分享数据，JSON编码
     * @return string
     */
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
    
    /**
     * 获取段子频道名字
     * @return string
     */
    public function getChannelLabel()
    {
        return CDBase::channelLabels((int)$this->channel_id);
    }
    
    /**
     * 获取段子类型名字
     * @return string
     */
    public function getMediaTypeLabel()
    {
        return CDBase::mediaTypeLabels((int)$this->media_type);
    }

    /**
     * 判断是否有单独的标题，还是从内容截取的
     * @return boolean 如果有单独的标题则返回true，截取的返回false
     */
    public function getHasTitle()
    {
        return (mb_stripos($this->content, $this->title, 0, app()->charset) !== 0);
    }
    
    /**
     * 判断是不是笑话
     * @return boolean
     */
    public function getIsJoke()
    {
        return $this->channel_id == CHANNEL_FUNNY && $this->getIsTextType();
    }
    
    /**
     * 判断是不是趣图
     * @return boolean
     */
    public function getIsLengtu()
    {
        return $this->channel_id == CHANNEL_FUNNY && $this->getIsImageType();
    }
    
    /**
     * 判断是否是纯文字内容
     * @return boolean
     */
    public function getIsTextType()
    {
        return $this->media_type == MEDIA_TYPE_TEXT;
    }
    
    /**
     * 判断是否为图片内容
     * @return boolean
     */
    public function getIsImageType()
    {
        return $this->media_type == MEDIA_TYPE_IMAGE;
    }
    
    /**
     * 判断是否为视频
     * @return boolean
     */
    public function getIsVideoType()
    {
        return $this->media_type == MEDIA_TYPE_VIDEO;
    }
    
    /**
     * 在保存之前把original_pic远程图片保存到本地
     * @param string $referer 抓取图片时的referer
     * @param array $opts UpYun writeFile方法参数
     * @return boolean
     */
    public function fetchRemoteImagesBeforeSave($referer = '', $opts = array())
    {
        $url = strip_tags(trim($this->original_pic));
        if (!empty($url) && CDBase::externalUrl($url, CDBase::localDomains())) {
            $image = CDUploadedFile::saveImage(upyunEnabled(), $url, 'pics', $referer, $opts);
            if ($image) {
                $this->original_pic = $image['url'];
                $this->original_width = $image['width'];
                $this->original_height = $image['height'];
                $this->original_frames = (int)$image['frames'];
                $this->content = '<p>' . $this->getMiddleImage() . '</p>' . $this->content;
                return true;
            }
            else
                return false;
        }
        else
            return true;
    }
    
    /**
     * 在保存段子后把original_pic远程图片保存到本地
     * @param string $referer 抓取图片时的referer
     * @return boolean
     */
    public function fetchRemoteImagesAfterSave($referer = '')
    {
        if ($this->fetchRemoteImagesBeforeSave($referer)) {
            $attributes = array('original_pic', 'original_width', 'original_height', 'original_frames');
            return $this->save(true, $attributes);
        }
        else
            return false;
    }
    
    /**
     * 将内容中的远程图片本地化
     * @param string $referer 抓取图片时的referer
     * @return Ambigous <integer, array> 如果没有图片返回0，否则返回一个数据，有两个元素，0为替换图片url后的内容，1为url数组
     */
    public function fetchContentRemoteImages($referer = '')
    {
        $urls = $this->getContentImages();
        if (count($urls) > 0) {
            $fetch = new CDFileLocal(uploader(true), 'pics');
            $fetch->referer($referer)->setLocalDomains(CDBase::localDomains());
            $cnfont = yii::getPathOfAlias('application.fonts') . DS . 'Hiragino_Sans_GB_W6.otf';
            $enfont = yii::getPathOfAlias('application.fonts') . DS . 'HelveticaNeueLTPro-Hv.otf';
            $fetch->addWaterMark(CDWaterMark::TYPE_TEXT, CDWaterMark::POS_BOTTOM_LEFT, '挖段子网', $cnfont, 20, '#F0F0F0', '#333333', IMAGE_WATER_SITENAME_SIZE, IMAGE_WATER_SITENAME_SIZE);
            $fetch->addWaterMark(CDWaterMark::TYPE_TEXT, CDWaterMark::POS_BOTTOM_RIGHT, 'waduanzi.com', $enfont, 12, '#F0F0F0', '#333333', IMAGE_WATER_URL_SIZE, IMAGE_WATER_URL_SIZE);
            $data = $fetch->fetchReplacedHtml($this->content);
        }
        else
            $data = 0;
        return $data;
    }
    
    /**
     * 保存段子后本地化内容中的图片，并且保存内容
     * @param string $referer 抓取图片时的referer
     * @return integer|boolean 如果内容中没有图片返回0，如果出错返回false
     */
    public function fetchContentRemoteImagesAfterSave($referer = '')
    {
        try {
            $data = $this->fetchContentRemoteImages($referer);
            if ($data) {
                $this->content = $data[0];
                $first = $data[1][0];
                if ($first) {
                    $this->original_pic = $first['url'];
                    $this->original_width = $first['width'];
                    $this->original_height = $first['height'];
                    $this->original_frames = (int)$first['frames'];
                }
                $result = $this->save(true, array('content', 'original_pic', 'original_width', 'original_height', 'original_frames'));
                if ($result && $data[1]) {
                    $this->saveUploadFile($data[1]);
                }
            }
            else
                return 0;
        }
        catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * 保存内容图片本地化后的图片
     * @param array $files
     */
    private function saveUploadFile($files)
    {
        if (empty($files)) return;
        
        foreach ($files as $file) {
            try {
                $model = new Upload();
                $model->post_id = $this->id;
                $model->file_type  = Upload::TYPE_IMAGE;
                $model->url = $file['url'];
                $model->desc = $this->filterTitle;
                $model->user_id = $this->user_id;
                $model->width = $file['width'];
                $model->height = $file['height'];
                $model->frames = $file['frames'];
                $model->save();
            }
            catch (Exception $e) {
                continue;
            }
        }
    }
    
    /**
     * 计算24小时内的段子数据
     * @return integer 24小时内发表的段子数量，有缓存，缓存有效期300秒
     */
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
    
    /**
     * 所有段子内容的数量
     * @return integer 有缓存，缓存有效期12小时
     */
    public static function allCount()
    {
        $duration = 60*60*12;
        $count = app()->getDb()->cache($duration)->createCommand()
            ->select('count(*)')
            ->from(TABLE_POST)
            ->queryScalar();
        
        return (int)$count;
    }
    
    /**
     * 获取段子所属的图片列表
     * @return array()
     */
    public function getUploadImageSquareThumbUrls()
    {
        $uploads = $this->uploadImages;
        $urls = array();
        foreach ((array)$uploads as $image) {
            $urls[$image->id] = $image->getSquareThumb();
        }
        return $urls;
    }
    
    /**
     * 获取段子所属的图片列表
     * @return array()
     */
    public function getUploadImageSquareThumbs($columns = 5, $rows = 1, $imgOptions = array(), $includeLink = true, $linkOptions = array('target'=>'_blank'))
    {
        $urls = $this->getUploadImageSquareThumbUrls();
        $images = array();
        $count = count($urls);
        $maxCount = $columns * $rows;
        if ($count < $maxCount)
            $count = $count - $count % $columns;
        else
            $count = $maxCount;
        
        $urls = array_slice($urls, 0, $count);
        foreach ($urls as $index => $url) {
            if ($imgOptions)
                $imgOptions['class'] = 'cd-thumb-list';
            $images[$index] = image($url, $this->filterTitle, $imgOptions);
            if ($includeLink)
                $images[$index] = l($images[$index], $this->getUrl(), $linkOptions);
        }
        
        return $images;
    }

    public function getNextChannelPost($columns = null)
    {
        static $data = array();
        if (array_key_exists($this->id, $data))
            return $data[$this->id];
        
        $duration = 60*60*24;
        $criteria = new CDbCriteria();
        $criteria->order = 'create_time desc, id desc';
        $criteria->with = array('uploadImagesCount');
        if (empty($columns))
            $criteria->select = array('id', 'title', 'create_time');
        $criteria->addColumnCondition(array('channel_id'=>$this->channel_id, 'state'=>POST_STATE_ENABLED))
            ->addCondition('create_time < ' . (int)$this->create_time);
        
        $data[$this->id] = self::model()->cache($duration)->find($criteria);
    
        return $data[$this->id];
    }
    
    public function getPrevChannelPost($columns = null)
    {
        static $data = array();
        if (array_key_exists($this->id, $data))
            return $data[$this->id];
        
        $duration = 60*60*24;
        $criteria = new CDbCriteria();
        $criteria->order = 'create_time asc, id asc';
        $criteria->with = array('uploadImagesCount');
        if (empty($columns))
            $criteria->select = array('id', 'title', 'create_time');
        $criteria->addColumnCondition(array('channel_id'=>$this->channel_id, 'state'=>POST_STATE_ENABLED))
            ->addCondition('create_time > ' . (int)$this->create_time);
        
        $data[$this->id] = self::model()->cache($duration)->find($criteria);
    
        return $data[$this->id];
    }

    public function addFavorite($userid)
    {
        $userid = (int)$userid;
        $row = app()->getDb()->createCommand()
            ->from(TABLE_POST_FAVORITE)
            ->select('id')
            ->where(array('and', 'user_id = :userid', 'post_id = :postid'), array(':userid'=>$userid, ':postid'=>$this->id))
            ->queryScalar();
        
        if ($row !== false)
            return $this->favorite_count;
        
        $columns = array(
            'user_id' => $userid,
            'post_id' => $this->id,
            'create_time' => $_SERVER['REQUEST_TIME'],
            'create_ip' => CDBase::getClientIp(),
        );
        $result = app()->getDb()->createCommand()
            ->insert(TABLE_POST_FAVORITE, $columns);
    
        if ($result > 0) {
            $this->favorite_count++;
            $result = $this->save(true, array('favorite_count'));
            return $this->favorite_count;
        }
        else
            return false;
    }
    
    public function delFavorite($userid)
    {
        try {
            $result = db()->createCommand()
                ->delete(TABLE_POST_FAVORITE,
                        array('and', 'user_id = :userid', 'post_id = :postid'),
                        array(':userid'=>$userid, ':postid'=>$this->id));
            
            if ($result > 0) {
                $this->favorite_count--;
                $result = $this->save(true, array('favorite_count'));
            }
            return $this->favorite_count;
        }
        catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * 将段子放入回收站
     * @throws CException
     * @return boolean
     */
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
            $this->title = mb_substr(strip_tags($this->content), 0, 30, app()->charset);
        
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
            $this->title = mb_substr(strip_tags($this->content), 0, 30, app()->charset);
            
        if ($this->tags) {
            $tags = join(',', Tag::filterTagsArray($this->tags));
            $this->tags = $tags;
        }
        
        return true;
    }
    
    protected function afterSave()
    {
        if ($this->getIsNewRecord()) {
            if ($this->original_pic && !CDBase::externalUrl($this->original_pic)) {
                $upload = new Upload();
                $upload->post_id = $this->id;
                $upload->file_type = Upload::TYPE_IMAGE;
                $upload->url = $this->original_pic;
                $upload->width = $this->original_width;
                $upload->height = $this->original_height;
                $upload->frames = $this->original_frames;
                $upload->desc = strip_tags(trim($this->title));
                $upload->user_id = $this->user_id;
                $upload->save();
            }
            
            if ($this->profile && $this->profile instanceof UserProfile) {
                $this->profile->score += PUBLISH_SCORE;
                $this->profile->save(true, array('score'));
            }
        }
        
        Tag::savePostTags($this->id, $this->tags);
    }

    protected function afterDelete()
    {
        foreach ($this->comments as $model) $model->delete();
        app()->getDb()->createCommand()
            ->delete(TABLE_POST_FAVORITE, 'post_id = :postid', array(':postid' => $this->id));
        
        Tag::deletePostTags($this->id);

        if ($this->profile && $this->profile instanceof UserProfile) {
            $this->profile->score -= PUBLISH_SCORE;
            $this->profile->save(true, array('score'));
        }
        
	    foreach ((array)$this->uploadImages as $file) {
	        if ($file instanceof Upload)
    	        $file->delete();
	    }
	    
	    foreach ((array)$this->videos as $video)
	        $video->delete();
    }
}



