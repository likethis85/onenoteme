<?php
class UserIdentity extends CUserIdentity
{
    const ERROR_USER_FORBIDDEN = 4;
    
    private $_id;
    private $_name;
    /**
     * 用户model
     * @var User
     */
    private $user;
    
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
            $this->user = User::model()->find($criteria);
            
            $password = $md5 ? $this->password : md5($this->password);
            if ($this->user === null)
                $this->errorCode = self::ERROR_USERNAME_INVALID;
            elseif ($this->user->state == User::STATE_DISABLED)
                $this->errorCode = self::ERROR_USER_FORBIDDEN;
            elseif ($this->user->password != $password) {
                $this->errorCode = self::ERROR_PASSWORD_INVALID;
            }
            else {
                $this->_id = $this->user->id;
                $this->_name = $this->user->getDisplayName();
                $this->errorCode = self::ERROR_NONE;
                $this->afterAuthSuccess();
            }
        }
        catch (Exception $e) {
            $this->errorCode = self::ERROR_UNKNOWN_IDENTITY;
//             echo $e->getMessage();
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
        $s['image_url'] = $this->user->profile->image_url;
    }
}

