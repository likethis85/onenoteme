<?php
class CDBaseUrl
{
    public static function siteHomeUrl()
    {
        return aurl('site/index');
    }
    
    public static function memberHomeUrl()
    {
        return aurl('member/default/index');
    }
    
    public static function wapHomeUrl()
    {
        return aurl('wap/index');
    }
    
    public static function adminHomeUrl()
    {
        return aurl('admin/default/index');
    }
    
    
    public static function mobileHomeUrl()
    {
        return aurl('mobile/default/index');
    }
    
    public static function loginUrl($url = '')
    {
        $loginUrl = aurl('account/login');
        if (CDBase::isHttpUrl($url))
            $loginUrl = aurl('account/login', array('url'=>$url));
        
        return $loginUrl;
    }
    
    public static function quickLoginUrl()
    {
        return aurl('account/quicklogin');
    }
    
    public static function logoutUrl($url = '')
    {
        $logoutUrl = aurl('account/logout');
        if (CDBase::isHttpUrl($url))
            $logoutUrl = aurl('account/logout', array('url'=>$url));
        
        return $logoutUrl;
    }
    
    public static function singupUrl($url = '')
    {
        $signupUrl = aurl('account/signup');
        if (CDBase::isHttpUrl($url))
            $signupUrl = aurl('account/signup', array('url'=>$url));
        
        return $signupUrl;
    }
    
    public static function activateUrl($code)
    {
        return aurl('account/activate', array('code'=>$code));
    }
    
    public static function userHomeUrl($uid)
    {
        return aurl('user/index', array('id'=>(int)$uid));
    }

    public static function publishUrl()
    {
        return aurl('post/publish');
    }
}


