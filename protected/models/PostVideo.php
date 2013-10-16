<?php

/**
 * This is the model class for table "{{post_video}}".
 *
 * The followings are the available columns in table '{{post_video}}':
 * @property integer $id
 * @property integer $post_id
 * @property string $desc
 * @property string $html5_url
 * @property string $flash_url
 * @property string $source_url
 * @property string $iframe_url
 * @property string $screenshot_url
 * @property integer $screenshot_width
 * @property integer $screenshot_height
 * @property integer $create_time
 * @property string $create_ip
 * @property integer $video_time
 *
 * @property Post $post
 */
class PostVideo extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return PostVideo the static model class
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
		return TABLE_POST_VIDEO;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
	        array('post_id, flash_url, source_url', 'required'),
			array('post_id, create_time, video_time, screenshot_width, screenshot_height', 'numerical', 'integerOnly'=>true),
			array('desc, html5_url, flash_url, source_url, iframe_url, screenshot_url', 'length', 'max'=>250),
			array('create_ip', 'length', 'max'=>15),
	        array('flash_url, html5_url, source_url, iframe_url, screenshot_url', 'url'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
	        'post' => array(self::BELONGS_TO, 'Post', 'post_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'post_id' => 'PID',
			'desc' => '描述',
			'html5_url' => 'HTML5视频',
			'flash_url' => 'FLASH视频',
			'source_url' => '来源URL',
			'iframe_url' => 'IFRAME URL',
            'screenshot_url' => '截图',
            'screenshot_width' => '截图宽度',
            'screenshot_height' => '截图高度',
			'create_time' => '添加时间',
			'create_ip' => '添加IP',
			'video_time' => '时长',
		);
	}
	
	public function getDesktopVideoHTML($width = 600, $height = 400, $autoplay = false)
	{
	    try {
    	    $vk = new CDVideoKit();
    	    $vk->setAppKeysMap(CDBase::videoAppKeysMap());
    	    $vk->setVideoUrl($this->source_url);
    	    return $vk->getDesktopPlayerHTML($width, $height, $autoplay);
	    }
	    catch (Exception $e) {
	        return false;
	    }
	}
	
	public function getMobileVideoHTML($width = 280, $height = 180, $autoplay = false)
	{
	    try {
    	    $vk = new CDVideoKit();
    	    $vk->setAppKeysMap(CDBase::videoAppKeysMap());
    	    $vk->setVideoUrl($this->source_url);
    	    return $vk->getMobilePlayerHTML($width, $height, $autoplay);
	    }
	    catch (Exception $e) {
	        return false;
	    }
	}
	
	protected function beforeSave()
	{
	    if ($this->getIsNewRecord()) {
	        $this->create_time = time();
	        $this->create_ip = CDBase::getClientIPAddress();
	    }
	    
	    return true;
	}
	
	protected function afterSave()
	{
	    if ($this->screenshot_url && $this->post && empty($this->post->original_pic)) {
	        $this->post->original_pic = $this->screenshot_url;
	        $this->post->original_width = $this->screenshot_width;
	        $this->post->original_height = $this->screenshot_height;
	        $this->save(true, array('original_pic', 'original_width', 'original_height'));
	    }
	}

}

