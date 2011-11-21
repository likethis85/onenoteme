<?php

/**
 * This is the model class for table "{{user}}".
 *
 * The followings are the available columns in table '{{user}}':
 * @property integer $id
 * @property string $email
 * @property string $name
 * @property string $password
 * @property integer $create_time
 * @property string $create_ip
 * @property integer $state
 * @property string $token
 */
class User extends CActiveRecord
{
    const STATE_DISABLED = 0;
    const STATE_ENABLED = 1;
    const STATE_EDITOR = 150;
    const STATE_ADMIN = 199;
    
    public $captcha;
    
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
		return '{{user}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('captcha', 'captcha', 'captchaAction'=>'bigCaptcha', 'on'=>'insert', 'message'=>'验证码不正确哦，仔细瞅瞅'),
		    array('email', 'required', 'message'=>'邮箱 必须要填写'),
		    array('name', 'required', 'message'=>'名字 必须要填写'),
		    array('password', 'required', 'message'=>'密码 必须要填写'),
		    array('email, name', 'unique'),
		    array('email', 'email', 'message'=>'请输入一个有效的email作为账号'),
			array('create_time, state', 'numerical', 'integerOnly'=>true),
			array('email', 'length', 'max'=>100),
			array('name', 'length', 'min'=>2, 'max'=>50),
			array('password', 'length', 'min'=>3, 'max'=>30),
			array('create_ip', 'length', 'max'=>15),
			array('token', 'length', 'max'=>32),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, email, name, password, create_time, create_ip, state, token', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'email' => '邮箱',
			'name' => '名字',
			'password' => '密码',
			'create_time' => '注册时间',
			'create_ip' => '注册IP',
			'state' => '状态',
		    'captcha' => '验证码',
		    'token' => '标识'
		);
	}
	
	protected function beforeSave()
	{
	    if ($this->getIsNewRecord()) {
	        $this->password = md5($this->password);
	        $this->create_time = $_SERVER['REQUEST_TIME'];
	        $this->create_ip = request()->getUserHostAddress();
	        $this->token = md5($_SERVER['REQUEST_TIME'] . uniqid());
	    }
	    
	    return true;
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);

		$criteria->compare('email',$this->email,true);

		$criteria->compare('name',$this->name,true);

		$criteria->compare('password',$this->password,true);

		$criteria->compare('create_time',$this->create_time,true);

		$criteria->compare('create_ip',$this->create_ip,true);

		$criteria->compare('state',$this->state);
		
		$criteria->compare('token',$this->token);

		return new CActiveDataProvider('User', array(
			'criteria'=>$criteria,
		));
	}
}