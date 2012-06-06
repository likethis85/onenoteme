<?php
class UserIdentity extends CUserIdentity
{
    const ERROR_USER_FORBIDDEN = 4;
    
    private $_id;
    private $_name;
    private $user;
    
    public function authenticate($md5 = false)
    {
        if ($this->isAuthenticated) {
            $this->errorCode = self::ERROR_NONE;
            return true;
        }
        
        try {
            $cmd = app()->getDb()->createCommand()
                ->select('id, username, screen_name, password, state')
                ->where('username = :username');
            $params = array(':username'=>$this->username);
            $this->user = DUser::model()->find($cmd, $params);
            
            $password = $md5 ? $this->password : md5($this->password);
            if ($this->user === null)
                $this->errorCode = self::ERROR_USERNAME_INVALID;
            elseif ($this->user->state == DUser::STATE_DISABLED)
                $this->errorCode = self::ERROR_USER_FORBIDDEN;
            elseif ($this->user->password != $password) {
                $this->errorCode = self::ERROR_PASSWORD_INVALID;
            }
            else {
                $this->_id = $this->user->id;
                $this->_name = $this->user->username;
                $this->errorCode = self::ERROR_NONE;
                $this->afterAuthSuccess();
            }
        }
        catch (Exception $e) {
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
    
    private function afterAuthSuccess()
    {
        $s = app()->session;
        $s['state'] = $this->user->state;
        $s['username'] = $this->username;
    }
}