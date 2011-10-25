<?php
class SignupForm extends CFormModel
{
    public $email;
    public $name;
    public $password;
    public $captcha;
    
    private $_identity;
    
    public function rules()
    {
        return array(
            array('email, name, password', 'required'),
            array('email', 'email'),
            array('email', 'unique', 'className'=>'User', 'attribute'=>'email'),
            array('name', 'unique', 'className'=>'User', 'attribute'=>'name'),
            array('password', 'length', 'min'=>5, 'max'=>30),
            array('captcha', 'captcha'),
        );
    }
    
    public function attributeLabels()
    {
        return array(
            'name' => '大名',
            'password' => '密码',
            'captcha' => '验证码',
            'email' => '邮箱',
        );
    }
    
    public function createUser()
    {
        $user = new User();
        $user->email = $this->email;
        $user->name = $this->name;
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