<?php
class AppUserIdentity extends CUserIdentity
{
    const ERROR_USER_FORBIDDEN = 4;
    const ERROR_USER_UNVERIFY = 5;
    
    private $_id;
    private $_name;
    
    /**
     * 用户model
     * @var RestUser
     */
    private $_user;
    
    public function authenticate($md5 = true)
    {
        if ($this->isAuthenticated) {
            $this->errorCode = self::ERROR_NONE;
            return true;
        }
        
        try {
            $criteria = new CDbCriteria();
            $criteria->addColumnCondition(array('username'=>$this->username));
            $this->_user = RestUser::model()->find($criteria);
            
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
                $this->afterAuthenticate();
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
     * @return RestUser | null
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
    
    private function afterAuthenticate()
    {
        
    }
}

