<?php

/**
 * This is the model class for table "{{user}}".
 *
 * The followings are the available columns in table '{{user}}':
 * @property integer $id
 * @property string $username
 * @property string $screen_name
 * @property string $password
 * @property integer $create_time
 * @property string $create_ip
 * @property integer $state
 * @property string $token
 *
 * @property string $homeUrl
 * @property UserProfile $profile
 */
class User extends CActiveRecord
{
    const STATE_DISABLED = 0;
    const STATE_ENABLED = 1;
    const STATE_EDITOR = 95;
    const STATE_ADMIN = 100;
    
	/**
	 * Returns the static model of the specified AR class.
	 * @return User the static model class
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
		return TABLE_USER;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username', 'required', 'message'=>'用户名 必须要填写'),
		    array('screen_name', 'required', 'message'=>'大名 必须要填写'),
		    array('password', 'required', 'message'=>'密码 必须要填写'),
		    array('username', 'unique'),
			array('create_time, state', 'numerical', 'integerOnly'=>true),
			array('username, screen_name', 'length', 'min'=>2, 'max'=>50),
			array('password', 'length', 'min'=>3, 'max'=>32),
			array('create_ip', 'length', 'max'=>15),
			array('token', 'length', 'max'=>32),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
		    'profile' => array(self::HAS_ONE, 'UserProfile', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'username' => '账号',
			'screen_name' => '名字',
			'password' => '密码',
			'create_time' => '注册时间',
			'create_ip' => '注册IP',
			'state' => '状态',
		    'captcha' => '验证码',
		    'token' => '标识'
		);
	}

	public function getDisplayName()
	{
	    return empty($this->screen_name) ? $this->username : $this->screen_name;
	}
	
	public function getHomeUrl()
	{
	    return CDBase::userHomeUrl($this->id);
	}
	
	public function getNameLink($htmlOptions = array('target'=>'_blank'))
	{
	    return l($this->getDisplayName(), $this->getHomeUrl(), $htmlOptions);
	}
	
	public function getCreateTime($format = null)
	{
	    if (empty($this->create_time))
	        return '';
	
	    $format = $format ? $format : param('formatDateTime');
	    return date($format, $this->create_time);
	}

	public function encryptPassword()
	{
	    $this->password = CDBase::encryptPassword($this->password);
	}
	
	protected function beforeSave()
	{
	    if ($this->getIsNewRecord()) {
	        $this->create_time = $_SERVER['REQUEST_TIME'];
	        $this->create_ip = request()->getUserHostAddress();
	    }
	    
	    return true;
	}
	
	protected function afterSave()
	{
	    if ($this->getIsNewRecord()) {
	        $profile = new UserProfile();
	        $profile->user_id = $this->id;
	        $profile->save();
	    }
	}
	
	public function beforeDelete()
	{
	    throw new CException('用户不允许删除');
	}
}