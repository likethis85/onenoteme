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
            array('username', 'username'),
            array('username', 'unique', 'classscreen_name'=>'User', 'attribute'=>'username'),
            array('screen_name', 'unique', 'classscreen_name'=>'User', 'attribute'=>'screen_name'),
            array('password', 'length', 'min'=>5, 'max'=>30),
            array('captcha', 'captcha'),
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
        $result =  $user->save();
        return $result ? $user : false;
    }

    public function afterValidate()
    {
        parent::afterValidate();
        if (!$this->getErrors()) {
            if ($this->createUser()) {
                request()->redirect(user()->loginUrl);
            }
        }
            
    }
}