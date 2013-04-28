<?php

/**
 * This is the model class for table "{{Upload}}".
 *
 * The followings are the available columns in table '{{Upload}}':
 * @property integer $id
 * @property integer $post_id
 * @property integer $file_type
 * @property string $url
 * @property integer $create_time
 * @property string $create_ip
 * @property integer $user_id
 * @property string $desc
 * @property string $token
 * @property integer $width
 * @property integer $height
 * @property integer $frames
 * @property string $fileUrl
 * @property string $fileTypeText
 * @property string $createTimeText
 * @property boolean $isImageFile
 */
class Upload extends CActiveRecord
{
    const TYPE_IMAGE = 1;
    const TYPE_AUDIO = 2;
    const TYPE_VIDEO = 3;
    const TYPE_FILE = 9;
    const TYPE_UNKNOWN = 127;
    
    public static function typeLabels()
    {
        return array(
            self::TYPE_IMAGE => '图片',
            self::TYPE_FILE => '文件',
            self::TYPE_AUDIO => '音频',
            self::TYPE_VIDEO => '视频',
            self::TYPE_UNKNOWN => '未知',
        );
    }
    
	/**
	 * Returns the static model of the specified AR class.
	 * @return Upload the static model class
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
		return TABLE_UPLOAD;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
		    array('post_id, url', 'required'),
			array('post_id, file_type, create_time, user_id, width, height, frames', 'numerical', 'integerOnly'=>true),
			array('url, desc', 'length', 'max'=>250),
			array('create_ip', 'length', 'max'=>15),
			array('token', 'length', 'max'=>32),
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
		    array(self::BELONGS_TO, 'Post', 'post_id'),
		    array(self::BELONGS_TO, 'User', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'post_id' => '文章ID',
			'file_type' => '类型',
			'url' => 'URL',
			'create_time' => '上传时间',
			'create_ip' => 'IP',
			'user_id' => '用户',
			'desc' => '描述',
			'token' => 'TOKEN',
		);
	}

	public function getFileUrl()
	{
	    $url = $this->url;
	    if ($this->file_type == self::TYPE_IMAGE)
    	    $url = $this->getMiddlePic();
	    
	    return $url;
	}
	
	public function getFileTypeText()
	{
	    $types = self::typeLabels();
	    if (array_key_exists($this->file_type, $types))
	        $text = $types[$this->file_type];
	    else
	        $text = '';
	    
	    return $text;
	}
	
	public function getCreateTimeText($format = null)
	{
	    if  (null === $format)
	        $format = param('formatShortDateTime');
	
	    return date($format, $this->create_time);
	}

	/**
	 * 获取自定义缩略图对象
	 * @return Ambigous <NULL, string, CDImageThumb>
	 */
	public function getImageThumb()
	{
	    static $thumbs = array();
	    if (!array_key_exists($this->id, $thumbs)){
	        if ($this->url)
	            $thumbs[$this->id] = new CDImageThumb($this->url, $this->width, $this->height);
	        else
	            $thumbs[$this->id] = '';
	    }
	
	    return $thumbs[$this->id];
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
	    return $this->url;
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
	        $html = image($this->getThumbnail(), $this->desc, $htmlOptions);
	    }
	
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
	        $html = image($this->getFixThumb(), $this->desc, $htmlOptions);
	    }
	
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
	        $html = image($this->getSquareThumb(), $this->desc, $htmlOptions);
	    }
	
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
	        $htmlOptions = array('class'=>'cd-middle-image');
	        $html = image($this->getMiddlePic(), $this->desc, $htmlOptions);
	    }
	
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
            $html = image($this->getLargePic(), $this->desc, $htmlOptions);
        }
    
        return $html;
    }

    public function getIsImageFile()
    {
        return $this->file_type == self::TYPE_IMAGE;
    }
    
	protected function beforeSave()
	{
	    if ($this->getIsNewRecord()) {
	        $this->create_time = $_SERVER['REQUEST_TIME'];
	        $this->create_ip = CDBase::getClientIp();
	    }
	    
	    return true;
	}
	
	protected function afterDelete()
	{
	    try {
            $uploader = uploader($this->getIsImageFile());
            if ($file->url) {
                $uploader->delete($file->url);
            }
        }
        catch (Exception $e) {}
	}
}


