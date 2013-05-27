<?php
class CDAppUser extends CApplicationComponent implements IWebUser
{
    public $guestName = 'Guest';
    
    private $_keyPrefix;
    private $_access=array();

    /**
     * PHP magic method.
     * This method is overriden so that persistent states can be accessed like properties.
     * @param string $name property name
     * @return mixed property value
     */
    public function __get($name)
    {
        if($this->hasState($name))
            return $this->getState($name);
        else
            return parent::__get($name);
    }
    
    /**
     * PHP magic method.
     * This method is overriden so that persistent states can be set like properties.
     * @param string $name property name
     * @param mixed $value property value
     */
    public function __set($name,$value)
    {
        if($this->hasState($name))
            $this->setState($name,$value);
        else
            parent::__set($name,$value);
    }
    
    /**
     * PHP magic method.
     * This method is overriden so that persistent states can also be checked for null value.
     * @param string $name property name
     * @return boolean
     */
    public function __isset($name)
    {
        if($this->hasState($name))
            return $this->getState($name)!==null;
        else
            return parent::__isset($name);
    }
    
    /**
     * PHP magic method.
     * This method is overriden so that persistent states can also be unset.
     * @param string $name property name
     * @throws CException if the property is read only.
     */
    public function __unset($name)
    {
        if($this->hasState($name))
            $this->setState($name,null);
        else
            parent::__unset($name);
    }
    
    /**
     * Initializes the application component.
     * This method overrides the parent implementation by starting session,
     * performing cookie-based authentication if enabled, and updating the flash variables.
     */
    public function init()
    {
        parent::init();
        Yii::app()->getSession()->open();
        if($this->getIsGuest() && $this->allowAutoLogin)
            $this->restoreFromCookie();
        elseif($this->autoRenewCookie && $this->allowAutoLogin)
        $this->renewCookie();
        if($this->autoUpdateFlash)
            $this->updateFlash();
    
        $this->updateAuthStatus();
    }
    
    /**
     * Logs in a user.
     *
     * The user identity information will be saved in storage that is
     * persistent during the user session. By default, the storage is simply
     * the session storage. If the duration parameter is greater than 0,
     * a cookie will be sent to prepare for cookie-based login in future.
     *
     * Note, you have to set {@link allowAutoLogin} to true
     * if you want to allow user to be authenticated based on the cookie information.
     *
     * @param IUserIdentity $identity the user identity (which should already be authenticated)
     * @param integer $duration number of seconds that the user can remain in logged-in status. Defaults to 0, meaning login till the user closes the browser.
     * If greater than 0, cookie-based login will be used. In this case, {@link allowAutoLogin}
     * must be set true, otherwise an exception will be thrown.
     * @return boolean whether the user is logged in
     */
    public function login($identity,$duration=0)
    {
        $id=$identity->getId();
        $states=$identity->getPersistentStates();
        if($this->beforeLogin($id,$states,false))
        {
            $this->changeIdentity($id,$identity->getName(),$states);
    
            if($duration>0)
            {
                if($this->allowAutoLogin)
                    $this->saveToCookie($duration);
                else
                    throw new CException(Yii::t('yii','{class}.allowAutoLogin must be set true in order to use cookie-based authentication.',
                            array('{class}'=>get_class($this))));
            }
    
            $this->afterLogin(false);
        }
        return !$this->getIsGuest();
    }
    
    /**
     * Logs out the current user.
     * This will remove authentication-related session data.
     * If the parameter is true, the whole session will be destroyed as well.
     * @param boolean $destroySession whether to destroy the whole session. Defaults to true. If false,
     * then {@link clearStates} will be called, which removes only the data stored via {@link setState}.
     */
    public function logout($destroySession=true)
    {
        if($this->beforeLogout())
        {
            if($this->allowAutoLogin)
            {
                Yii::app()->getRequest()->getCookies()->remove($this->getStateKeyPrefix());
                if($this->identityCookie!==null)
                {
                    $cookie=$this->createIdentityCookie($this->getStateKeyPrefix());
                    $cookie->value=null;
                    $cookie->expire=0;
                    Yii::app()->getRequest()->getCookies()->add($cookie->name,$cookie);
                }
            }
            if($destroySession)
                Yii::app()->getSession()->destroy();
            else
                $this->clearStates();
            $this->_access=array();
            $this->afterLogout();
        }
    }
    
    /**
     * Returns a value indicating whether the user is a guest (not authenticated).
     * @return boolean whether the current application user is a guest.
     */
    public function getIsGuest()
    {
        return $this->getState('__id')===null;
    }
    
    /**
     * Returns a value that uniquely represents the user.
     * @return mixed the unique identifier for the user. If null, it means the user is a guest.
     */
    public function getId()
    {
        return $this->getState('__id');
    }
    
    /**
     * @param mixed $value the unique identifier for the user. If null, it means the user is a guest.
     */
    public function setId($value)
    {
        $this->setState('__id',$value);
    }
    
    /**
     * Returns the unique identifier for the user (e.g. username).
     * This is the unique identifier that is mainly used for display purpose.
     * @return string the user name. If the user is not logged in, this will be {@link guestName}.
     */
    public function getName()
    {
        if(($name=$this->getState('__name'))!==null)
            return $name;
        else
            return $this->guestName;
    }
    
    /**
     * Sets the unique identifier for the user (e.g. username).
     * @param string $value the user name.
     * @see getName
     */
    public function setName($value)
    {
        $this->setState('__name',$value);
    }
    

    /**
     * Redirects the user browser to the login page.
     * Before the redirection, the current URL (if it's not an AJAX url) will be
     * kept in {@link returnUrl} so that the user browser may be redirected back
     * to the current page after successful login. Make sure you set {@link loginUrl}
     * so that the user browser can be redirected to the specified login URL after
     * calling this method.
     * After calling this method, the current request processing will be terminated.
     */
    public function loginRequired()
    {
        $app=Yii::app();
        $request=$app->getRequest();
    
        if(!$request->getIsAjaxRequest())
            $this->setReturnUrl($request->getUrl());
        elseif(isset($this->loginRequiredAjaxResponse))
        {
            echo $this->loginRequiredAjaxResponse;
            Yii::app()->end();
        }
    
        if(($url=$this->loginUrl)!==null)
        {
            if(is_array($url))
            {
                $route=isset($url[0]) ? $url[0] : $app->defaultController;
                $url=$app->createUrl($route,array_splice($url,1));
            }
            $request->redirect($url);
        }
        else
            throw new CHttpException(403,Yii::t('yii','Login Required'));
    }
    

    /**
     * @return string a prefix for the name of the session variables storing user session data.
     */
    public function getStateKeyPrefix()
    {
        if($this->_keyPrefix!==null)
            return $this->_keyPrefix;
        else
            return $this->_keyPrefix=md5('Yii.'.get_class($this).'.'.Yii::app()->getId());
    }
    
    /**
     * @param string $value a prefix for the name of the session variables storing user session data.
     */
    public function setStateKeyPrefix($value)
    {
        $this->_keyPrefix=$value;
    }
    
    /**
     * Returns the value of a variable that is stored in user session.
     *
     * This function is designed to be used by CWebUser descendant classes
     * who want to store additional user information in user session.
     * A variable, if stored in user session using {@link setState} can be
     * retrieved back using this function.
     *
     * @param string $key variable name
     * @param mixed $defaultValue default value
     * @return mixed the value of the variable. If it doesn't exist in the session,
     * the provided default value will be returned
     * @see setState
     */
    public function getState($key,$defaultValue=null)
    {
        $key=$this->getStateKeyPrefix().$key;
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $defaultValue;
    }
    
    /**
     * Stores a variable in user session.
     *
     * This function is designed to be used by CWebUser descendant classes
     * who want to store additional user information in user session.
     * By storing a variable using this function, the variable may be retrieved
     * back later using {@link getState}. The variable will be persistent
     * across page requests during a user session.
     *
     * @param string $key variable name
     * @param mixed $value variable value
     * @param mixed $defaultValue default value. If $value===$defaultValue, the variable will be
     * removed from the session
     * @see getState
     */
    public function setState($key,$value,$defaultValue=null)
    {
        $key=$this->getStateKeyPrefix().$key;
        if($value===$defaultValue)
            unset($_SESSION[$key]);
        else
            $_SESSION[$key]=$value;
    }
    
    /**
     * Returns a value indicating whether there is a state of the specified name.
     * @param string $key state name
     * @return boolean whether there is a state of the specified name.
     */
    public function hasState($key)
    {
        $key=$this->getStateKeyPrefix().$key;
        return isset($_SESSION[$key]);
    }
    
    /**
     * Clears all user identity information from persistent storage.
     * This will remove the data stored via {@link setState}.
     */
    public function clearStates()
    {
        $keys=array_keys($_SESSION);
        $prefix=$this->getStateKeyPrefix();
        $n=strlen($prefix);
        foreach($keys as $key)
        {
            if(!strncmp($key,$prefix,$n))
                unset($_SESSION[$key]);
        }
    }
    

    /**
     * Changes the current user with the specified identity information.
     * This method is called by {@link login} and {@link restoreFromCookie}
     * when the current user needs to be populated with the corresponding
     * identity information. Derived classes may override this method
     * by retrieving additional user-related information. Make sure the
     * parent implementation is called first.
     * @param mixed $id a unique identifier for the user
     * @param string $name the display name for the user
     * @param array $states identity states
     */
    protected function changeIdentity($id,$name,$states)
    {
        Yii::app()->getSession()->regenerateID(true);
        $this->setId($id);
        $this->setName($name);
        $this->loadIdentityStates($states);
    }
    

    /**
     * Retrieves identity states from persistent storage and saves them as an array.
     * @return array the identity states
     */
    protected function saveIdentityStates()
    {
        $states=array();
        foreach($this->getState(self::STATES_VAR,array()) as $name=>$dummy)
            $states[$name]=$this->getState($name);
        return $states;
    }
    
    /**
     * Loads identity states from an array and saves them to persistent storage.
     * @param array $states the identity states
     */
    protected function loadIdentityStates($states)
    {
        $names=array();
        if(is_array($states))
        {
            foreach($states as $name=>$value)
            {
                $this->setState($name,$value);
                $names[$name]=true;
            }
        }
        $this->setState(self::STATES_VAR,$names);
    }

    /**
     * Updates the authentication status according to {@link authTimeout}.
     * If the user has been inactive for {@link authTimeout} seconds,
     * he will be automatically logged out.
     * @since 1.1.7
     */
    protected function updateAuthStatus()
    {
        if($this->authTimeout!==null && !$this->getIsGuest())
        {
            $expires=$this->getState(self::AUTH_TIMEOUT_VAR);
            if ($expires!==null && $expires < time())
                $this->logout(false);
            else
                $this->setState(self::AUTH_TIMEOUT_VAR,time()+$this->authTimeout);
        }
    }
    
    /**
     * Performs access check for this user.
     * @param string $operation the name of the operation that need access check.
     * @param array $params name-value pairs that would be passed to business rules associated
     * with the tasks and roles assigned to the user.
     * Since version 1.1.11 a param with name 'userId' is added to this array, which holds the value of
     * {@link getId()} when {@link CDbAuthManager} or {@link CPhpAuthManager} is used.
     * @param boolean $allowCaching whether to allow caching the result of access check.
     * When this parameter
     * is true (default), if the access check of an operation was performed before,
     * its result will be directly returned when calling this method to check the same operation.
     * If this parameter is false, this method will always call {@link CAuthManager::checkAccess}
     * to obtain the up-to-date access result. Note that this caching is effective
     * only within the same request and only works when <code>$params=array()</code>.
     * @return boolean whether the operations can be performed by this user.
     */
    public function checkAccess($operation,$params=array(),$allowCaching=true)
    {
        if($allowCaching && $params===array() && isset($this->_access[$operation]))
            return $this->_access[$operation];
    
        $access=Yii::app()->getAuthManager()->checkAccess($operation,$this->getId(),$params);
        if($allowCaching && $params===array())
            $this->_access[$operation]=$access;
    
        return $access;
    }
}