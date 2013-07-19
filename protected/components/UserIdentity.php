<?php
class UserIdentity extends CUserIdentity
{
    const ERROR_USER_FORBIDDEN = 4;
    const ERROR_USER_UNVERIFY = 5;
    
    private $_id;
    private $_name;
    /**
     * 用户model
     * @var User
     */
    private $_user;
    
    public function authenticate($md5 = false)
    {
        if ($this->isAuthenticated) {
            $this->errorCode = self::ERROR_NONE;
            return true;
        }
        
        try {
            $criteria = new CDbCriteria();
            $criteria->select = array('t.id', 't.username', 't.screen_name', 't.password', 't.state');
            $criteria->addColumnCondition(array('username'=>$this->username));
            $this->_user = User::model()->find($criteria);
            
            $password = $md5 ? $this->password : md5($this->password);
            if ($this->_user === null) {
                $this->errorMessage = '账号不存在';
                $this->errorCode = self::ERROR_USERNAME_INVALID;
            }
            elseif ($this->_user->state == USER_STATE_FORBIDDEN) {
                $this->errorMessage = '账号被禁用';
                $this->errorCode = self::ERROR_USER_FORBIDDEN;
            }
            elseif ($this->_user->password != $password) {
                $this->errorMessage = '密码不正确';
                $this->errorCode = self::ERROR_PASSWORD_INVALID;
            }
            else {
                $this->_id = $this->_user->id;
                $this->_name = $this->_user->getDisplayName();
                $this->errorCode = self::ERROR_NONE;
                $this->afterAuthSuccess();
            }
        }
        catch (Exception $e) {
            $this->errorCode = self::ERROR_UNKNOWN_IDENTITY;
            $this->errorMessage = '未知错误';
//             echo $e->getMessage();
        }
        
        return !$this->errorCode;
    }
    
    /**
     * 验证通过后返回当前用户
     * @return Ambigous <NULL, User>
     */
    public function getUser()
    {
        return $this->isAuthenticated ? $this->_user : null;
    }
    
    public function getId()
    {
        return $this->_id;
    }
    
    public function getName()
    {
        return $this->_name;
    }
    
    private function afterAuthSuccess()
    {
        $s = app()->session;
        $s['state'] = $this->_user->state;
        $s['username'] = $this->username;
        $s['image_url'] = $this->_user->profile->smallAvatarUrl;
    }
}

