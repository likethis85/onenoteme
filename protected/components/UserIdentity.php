<?php
class UserIdentity extends CUserIdentity
{
    const ERROR_USER_FORBIDDEN = 4;
    
    private $_id;
    private $_name;
    
    public function authenticate()
    {
        if ($this->isAuthenticated)
            return true;
        try {
            $cmd = app()->getDb()->createCommand()
                ->select('id, email, name, password')
                ->where('email = :email');
            $params = array(':email'=>$this->username);
            $user = DUser::model()->find($cmd, $params);
            
            if ($user !== null && ($user instanceof DUser))
                $this->errorCode = self::ERROR_USERNAME_INVALID;
            elseif ($user->state == DUser::STATE_DISABLED)
                $this->errorCode = self::ERROR_USER_FORBIDDEN;
            elseif ($user->password != md5($this->password)) {
                $this->errorCode = self::ERROR_PASSWORD_INVALID;
            }
            else {
                $this->_id = $user->id;
                $this->_name = $user->id;
                $this->errorCode = self::ERROR_NONE;
            }
        }
        catch (CException $e) {
            $this->errorCode = self::ERROR_UNKNOWN_IDENTITY;
        }
        
        return !$this->errorCode;
    }
    
    public function getId()
    {
        return $this->_id;
    }
    
    public function getName()
    {
        return $this->_name;
    }
}