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
 * @property string $fileUrl
 * @property string $fileTypeText
 * @property string $createTimeText
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
			array('post_id, file_type, create_time, user_id', 'numerical', 'integerOnly'=>true),
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
	    $pos = stripos($this->url, 'http://');
	    $thumb = new CDImageThumb($this->url);
        return $thumb->middleImageUrl();
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
	    $filename = fbp($this->url);
	    if (is_file($filename) && file_exists($filename) && is_writable($filename))
    	    unlink($filename);
	}
}