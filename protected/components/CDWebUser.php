<?php
class CDWebUser extends CWebUser
{
    const AUTH_ADMIN_NAME = 'enter_admin_system';
    const AUTH_EDITOR_NAME = 'editor';
    const AUTH_AUTHOR_NAME = 'author';
    const AUTH_CHIEF_EDITOR_NAME = 'chief_editor';
    
    public $autoLoginDuration = 0;
    
    public function getHomeUrl()
    {
        $url = '';
        if (!$this->getIsGuest())
            $url = CDBaseUrl::userHomeUrl($this->id);
        
        return $url;
    }
    
    public function getIsAdmin()
    {
        return $this->checkAccess(self::AUTH_ADMIN_NAME);
    }
    
    public function getIsEditor()
    {
        return $this->checkAccess(self::AUTH_EDITOR_NAME);
    }
    
    public function getIsAuthor()
    {
        return $this->checkAccess(self::AUTH_AUTHOR_NAME);
    }
    
    public function getIsChiefEditor()
    {
        return $this->checkAccess(self::AUTH_CHIEF_EDITOR_NAME);
    }

    public function getIsVip()
    {
        return $this->getIsAdmin();
    }
}