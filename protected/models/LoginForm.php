<?php
class LoginForm extends CFormModel
{
    const COOKIE_LOGIN_ERROR = 'sd7fh328fhs';
    
    public $username;
    public $screen_name;
    public $password;
    public $captcha;
    public $rememberMe = 1;
    public $agreement = 1;
    public $returnUrl;

    private $_identity;
    private static $_maxLoginErrorNums = 3;

    public function rules()
    {
        return array(
            array('username', 'required', 'message'=>'请输入您的邮箱/手机'),
            array('username', 'unique', 'className'=>'User', 'attributeName'=>'username', 'on'=>'signup', 'message'=>'用户名已经存在'),
            array('username', 'checkUserName', 'on'=>'signup'),
            array('screen_name', 'required', 'message'=>'请输入您的大名', 'on'=>'signup'),
            array('screen_name', 'unique', 'className'=>'User', 'attributeName'=>'screen_name', 'on'=>'signup', 'message'=>'大名已经存在'),
            array('screen_name', 'checkReserveWords', 'on'=>'signup'),
            array('password', 'required', 'on'=>'signup', 'message'=>'请输入密码'),
            array('password', 'authenticate', 'on'=>array('login', 'quicklogin')),
            array('captcha', 'captcha', 'allowEmpty'=>!$this->getEnableCaptcha(), 'on'=>'login'),
            array('captcha', 'captcha', 'allowEmpty'=>false, 'on'=>array('signup')),
            array('rememberMe', 'boolean', 'on'=>array('login', 'quicklogin')),
            array('screen_name, password', 'length', 'min'=>3, 'max'=>50, 'message'=>'大名最少3个字符'),
            array('username, returnUrl', 'length', 'max'=>255),
            array('agreement', 'compare', 'compareValue'=>true, 'on'=>'signup', 'message'=>'请同意服务条款和协议'),
            array('rememberMe', 'in', 'range'=>array(0, 1), 'on'=>'login'),
        );
    }
    
    public function checkUserName($attribute, $params)
    {
        $value = $this->$attribute;
        if (CDBase::checkEmail($value) || CDBase::checkMobilePhone($value))
            return true;
        else
            $this->addError($attribute, '用户名必须为邮箱或手机号');
    }
    
    public function checkReserveWords($attribute, $params)
    {
        if ($this->hasErrors($attribute)) return false;
        $words = (array)param('reservedWords');
        foreach ($words as $v) {
            $pos = stripos($this->$attribute, $v);
            if (false !== $pos) {
                $this->addError($attribute, '此大名已经存在');
                break;
            }
        }
        return true;
    }

    public function authenticate($attribute, $params)
    {
        if ($this->hasErrors('username')) return false;
        $this->_identity = new UserIdentity($this->username, $this->password);

        if (!$this->_identity->authenticate()) {
            if ($this->_identity->errorCode == UserIdentity::ERROR_USER_FORBIDDEN)
                $this->addError('state', $this->_identity->errorMessage);
            else
                $this->addError($attribute, '用户名密码错误');
        }
    }

    public function attributeLabels()
    {
        return array(
            'username' => '邮箱/手机',
            'password' => '密码',
            'captcha' => '验证码',
            'rememberMe' => '下次自动登录',
            'screen_name' => '大名',
        	'agreement' => '我已经认真阅读并同意《使用协议》',
            'reutrnUrl' => '返回地址',
        );
    }

    /**
     * 用户登陆
     */
    public function login($afterLogin = true)
    {
        $duration = (user()->allowAutoLogin && $this->rememberMe) ? user()->autoLoginDuration : 0;
        if (user()->login($this->_identity, $duration)) {
            $afterLogin && $this->afterLogin($duration);
            return true;
        }
        else
            return false;
    }

    /**
     * 创建新账号
     */
    public function signup()
    {
        $user = new User();
	    $user->username = $this->username;
	    $user->screen_name = $this->screen_name;
	    $user->password = $this->password;
	    $user->state = (param('user_required_admin_verfiy') || param('user_required_email_verfiy')) ? USER_STATE_UNVERIFY : USER_STATE_ENABLED;
	    $user->encryptPassword();
	    $user->source = USER_SOURCE_PC_WEB;
	    $result = $user->save();

	    if ($result) {
	        $this->afterSignup($user);
	        return true;
	    }
	    else
	        return false;
    }

    public function incrementErrorLoginNums()
    {
        $cookie = request()->cookies[self::COOKIE_LOGIN_ERROR];
        if ($cookie === null) {
            $cookie = new CHttpCookie(self::COOKIE_LOGIN_ERROR, 1);
        }
        elseif ($cookie->value < self::$_maxLoginErrorNums)
            $cookie->value += 1;
        else
            return ;
        
        $cookie->expire = $_SERVER['REQUEST_TIME'] + 3600;
        request()->cookies->add(self::COOKIE_LOGIN_ERROR, $cookie);
    }

    public function clearErrorLoginNums()
    {
        request()->cookies->remove(self::COOKIE_LOGIN_ERROR);
    }

    public function getEnableCaptcha()
    {
        $errorNums = (int)request()->cookies[self::COOKIE_LOGIN_ERROR]->value;
        return $errorNums >= self::$_maxLoginErrorNums;
    }

    protected function beforeValidate()
    {
        $this->username = trim(strip_tags($this->username));
        $this->screen_name = trim(strip_tags($this->screen_name));
        return true;
    }
    
    protected function afterValidate()
    {
        parent::afterValidate();
        if ($this->hasErrors())
            $this->incrementErrorLoginNums();
        else
            $this->clearErrorLoginNums();
    }

    public function afterLogin($duration)
    {
        CDBase::setClientLastVisit($duration);
        $returnUrl = urldecode($this->returnUrl);
        if (empty($returnUrl))
            $returnUrl = strip_tags(trim($_GET['url']));
        if (empty($returnUrl))
                $returnUrl = aurl('member/default/index');
        
        request()->redirect($returnUrl);
        exit(0);
    }
    
    public function afterSignup(User $user)
    {
        $user->sendVerifyEmail();
        $identity = new UserIdentity($user->username, $user->password);
        if ($identity->authenticate(true)) {
            $result = user()->login($identity);
            if ($result) {
                $duration = user()->allowAutoLogin ? user()->autoLoginDuration : 0;
                $this->afterLogin($duration);
            }
        }
    }
}


