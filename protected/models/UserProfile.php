<?php

/**
 * This is the model class for table "{{user_profile}}".
 *
 * The followings are the available columns in table '{{user_profile}}':
 * @property integer $user_id
 * @property integer $province
 * @property integer $city
 * @property string $location
 * @property integer $gender
 * @property string $description
 * @property string $website
 * @property string $original_avatar
 * @property integer $weibo_uid
 * @property integer $qqt_uid
 * @property integer $score
 *
 * @property string $homeUrl
 * @property string $largeAvatarUrl
 * @property string $smallAvatarUrl
 * @property string $miniAvatarUrl
 * @property string $largeAvatar
 * @property string $smallAvatar
 * @property string $miniAvatar
 * @property string $genderLabel
 */
class UserProfile extends CActiveRecord
{
    public static function genders()
    {
        return array(GENDER_UNKOWN, GENDER_FEMALE, GENDER_MALE);
    }
    
    public static function genderLabel($gender = null)
    {
        $labels = array(
                GENDER_UNKOWN => '保密',
                GENDER_FEMALE => '妹纸',
                GENDER_MALE => '帅锅',
        );
    
        return ($gender === null) ? $labels : $labels[$gender];
    }
    
	/**
	 * Returns the static model of the specified AR class.
	 * @return UserProfile the static model class
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
		return TABLE_USER_PROFILE;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
    		array('user_id', 'unique'),
			array('user_id', 'required'),
			array('user_id, province, city, score', 'numerical', 'integerOnly'=>true),
			array('location', 'length', 'max'=>100),
			array('weibo_uid, qqt_uid', 'length', 'max'=>50),
			array('description, website, original_avatar', 'length', 'max'=>250),
	        array('gender', 'in', 'range'=>self::genders()),
			array('website', 'url'),
			array('description', 'safe'),
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
			'user_id' => '用户ID',
			'province' => '省份',
			'city' => '城市',
			'location' => '地址',
			'gender' => '性别',
			'description' => '简介',
			'website' => '网址',
		    'weibo_uid' => '新浪微博UID',
		    'qqt_uid' => '腾讯微博UID',
	        'original_avatar' => '原始头像',
	        'score' => '积分',
		);
	}
	
	public function getGenderLabel()
	{
	    return self::genderLabel($this->gender);
	}

	public function getHomeUrl()
	{
	    return CDBaseUrl::userHomeUrl($this->user_id);
	}
	
	/**
	 * 获取自定义缩略图对象
	 * @return Ambigous <NULL, string, CDImageThumb>
	 */
	public function getAvatarThumb()
	{
	    static $thumbs = array();
	    if (!array_key_exists($this->user_id, $thumbs)){
	        if ($this->original_avatar)
	            $thumbs[$this->user_id] = new CDImageThumb($this->original_avatar);
	        else
	            $thumbs[$this->user_id] = '';
	    }
	    
	    return $thumbs[$this->user_id];
	}
	
	public function getMiniAvatarUrl()
	{
	    $url = sbu(param('default_mini_avatar'));
	    $thumb = $this->getAvatarThumb();
	    if ($thumb)
	        $url = $thumb->miniAvatarUrl();
	    
	    return $url;
	}
	
	public function getSmallAvatarUrl()
	{
	    $url = sbu(param('default_small_avatar'));
	    $thumb = $this->getAvatarThumb();
	    if ($thumb)
	        $url = $thumb->smallAvatarUrl();
	    
	    return $url;
	}

	public function getLargeAvatarUrl()
	{
	    $url = sbu(param('default_large_avatar'));
	    $thumb = $this->getAvatarThumb();
	    if ($thumb)
	        $url = $thumb->largeAvatarUrl();
	    
	    return $url;
	}

	public function getLargeAvatar($alt = '', $htmlOptions = array())
	{
	    $html = '';
	    $url = $this->getLargeAvatarUrl();
	    if ($url) {
	        $htmlOptions += array('class'=>'large-avatar');
	        $html = image($url, $alt, $htmlOptions);
	    }
	
	    return $html;
	}
	
	public function getSmallAvatar($alt = '', $htmlOptions = array())
	{
	    $html = '';
	    $url = $this->getSmallAvatarUrl();
	    if ($url) {
	        $htmlOptions += array('class'=>'small-avatar');
	        $html = image($url, $alt, $htmlOptions);
	    }
	
	    return $html;
	}
	
	public function getMiniAvatar($alt = '', $htmlOptions = array())
	{
	    $html = '';
	    $url = $this->getMiniAvatarUrl();
	    if ($url) {
	        $htmlOptions += array('class'=>'mini-avatar');
	        $html = image($url, $alt, $htmlOptions);
	    }
	
	    return $html;
	}
}


