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
    
    public static function loginUrl()
    {
        return aurl('site/login');
    }
    
    public static function logoutUrl()
    {
        return aurl('site/logout');
    }
    
    public static function singupUrl()
    {
        return aurl('site/signup');
    }
    
    public static function userHomeUrl($uid)
    {
        return aurl('user/index', array('id'=>(int)$uid));
    }
    
    public static function fallStyleUrl(CController $controller)
    {
        return aurl($controller->route, array_merge($controller->actionParams, array('s'=>POST_LIST_STYLE_WATERFALL)));
    }
    
    
    public static function gridStyleUrl(CController $controller)
    {
        return aurl($controller->route, array_merge($controller->actionParams, array('s'=>POST_LIST_STYLE_GRID)));
    }
    
    
    public static function lineStyleUrl(CController $controller)
    {
        return aurl($controller->route, array_merge($controller->actionParams, array('s'=>POST_LIST_STYLE_LINE)));
    }
    
}


