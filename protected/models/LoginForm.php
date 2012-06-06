<?php
class LoginForm extends CFormModel
{
    public $username;
    public $password;
    public $rememberMe = 1;
    public $captcha;
    
    public $identity;
    
    public function rules()
    {
        return array(
            array('captcha', 'captcha', 'captchaAction'=>'bigCaptcha', 'message'=>'验证码不正确哦，仔细瞅瞅'),
            array('username', 'required', 'message'=>'嫩还没有写账号名称，怎么能登录哦'),
            array('password', 'authenticate'),
        );
    }
    
    public function authenticate($attribute, $params)
    {
        if ($this->hasErrors()) return false;
        
        $this->identity = new UserIdentity($this->username, $this->password);

        if (!$this->identity->authenticate()) {
            $this->addError($attribute, '账号不存在或密码错误');
        }
    }
    
    public function attributeLabels()
    {
        return array(
            'password' => '密码',
            'captcha' => '验证码',
            'rememberMe' => '记住我',
            'username' => '账号',
        );
    }
    
    public function login()
    {
        if (empty($this->identity))
            $this->identity = new UserIdentity($this->username, $this->password);
        if ($this->identity->authenticate()) {
            $duration = (user()->allowAutoLogin && $this->rememberMe) ? param('autoLoginDuration') : 0;
            user()->login($this->identity, $duration);
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