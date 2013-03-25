<?php
class CDWebUser extends CWebUser
{
    public function getHomeUrl()
    {
        $url = '';
        if (!$this->getIsGuest())
            $url = CDBase::userHomeUrl($this->id);
        
        return $url;
    }
    
    public function getIsAdmin()
    {
        return $this->checkAccess('enter_admin_system');
    }
}