<?php
class LoginForm extends CFormModel
{
    public $email;
    public $password;
    public $rememberMe = 1;
    public $captcha;
    
    private $_identity;
    
    public function rules()
    {
        return array(
            array('email', 'required'),
            array('email', 'email'),
            array('password', 'authenticate'),
            array('captcha', 'captcha'),
        );
    }
    
    public function authenticate($attribute, $params)
    {
        if ($this->hasErrors()) return false;
        
        $this->_identity = new UserIdentity($this->email, $this->password);

        if (!$this->_identity->authenticate()) {
            $this->addError($attribute, '邮箱或密码错误');
        }
    }
    
    public function attributeLabels()
    {
        return array(
            'password' => '密码',
            'captcha' => '验证码',
            'rememberMe' => '记住我',
            'email' => '邮箱',
        );
    }
    
    public function login()
    {
        if (empty($this->_identity))
            $this->_identity = new UserIdentity($this->email, $this->password);
        if ($this->_identity->authenticate()) {
            $duration = (user()->allowAutoLogin && $this->rememberMe) ? param('autoLoginDuration') : 0;
            user()->login($this->_identity, $duration);
        }
    }
    
    public static function incrementErrorLoginNums()
    {
        $errorNums = (int)$_COOKIE['loginErrorNums'];
        // @todo cookie login
        setcookie('loginErrorNums', ++$errorNums, $_SERVER['REQUEST_TIME'] + 3600, param('cookiePath'), param('cookieDomain'));
    }

    public static function clearErrorLoginNums()
    {
        // @todo clear error login nums
        return setcookie('loginErrorNums', null, null, param('cookiePath'), param('cookieDomain'));
    }

    public static function getEnableCaptcha()
    {
        $errorNums = (int)$_COOKIE['loginErrorNums'];
        return ($errorNums >= self::$_maxLoginErrorNums) ? true : false;
    }

    public function afterValidate()
    {
        parent::afterValidate();
        if ($this->getErrors())
            self::incrementErrorLoginNums();
        else
            self::clearErrorLoginNums();
    }
}