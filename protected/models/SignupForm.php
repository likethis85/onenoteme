<?php
class SignupForm extends CFormModel
{
    public $username;
    public $screen_name;
    public $password;
    public $captcha;
    
    private $_identity;
    
    public function rules()
    {
        return array(
            array('username, screen_name, password', 'required'),
            array('username', 'unique', 'className'=>'User', 'attributeName'=>'username'),
            array('screen_name', 'unique', 'className'=>'User', 'attributeName'=>'screen_name'),
            array('password', 'length', 'min'=>5, 'max'=>30),
            array('captcha', 'captcha', 'captchaAction'=>'bigCaptcha'),
        );
    }
    
    public function attributeLabels()
    {
        return array(
            'screen_name' => '大名',
            'password' => '密码',
            'captcha' => '验证码',
            'username' => '邮箱',
        );
    }
    
    public function createUser()
    {
        $user = new User();
        $user->username = $this->username;
        $user->screen_name = $this->screen_name;
        $user->password = $this->password;
        $user->state = param('userIsRequireEmailVerify') ? User::STATE_DISABLED : User::STATE_ENABLED;
        $result =  $user->save();
        return $result ? $user : false;
    }
}