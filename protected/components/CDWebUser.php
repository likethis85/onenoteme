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
}