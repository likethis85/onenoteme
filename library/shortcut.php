<?php
/**
 * @author Chris Chen(cdcchen@gmail.com)
 * @version v1.0
 * @since 2010-9-7 10:39
 */

defined('LIBRARY_ROOT') or define('LIBRARY_ROOT', dirname(__FILE__));

/**
 * This is the shortcut to DIRECTORY_SEPARATOR
 */
defined('DS') or define('DS', DIRECTORY_SEPARATOR);

if (!function_exists('getallheaders'))
{
    function getallheaders()
    {
        $headers = array();
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headerName = str_replace(' ', '-', str_replace('_', ' ', substr($name, 5)));
                $headers[$headerName] = $value;
            }
        }
        return $headers;
    }
}

/**
 * This is the shortcut to Yii::app()
 * @return CApplication Yii::app()
 */
function app()
{
    return Yii::app();
}
 
/**
 * This is the shortcut to Yii::app()->clientScript
 * @return CClientScript Yii::app()->clientScript
 */
function cs()
{
    return Yii::app()->clientScript;
}

/**
 * This is the shortcut to Yii::app()->createUrl()
 * @param string $route
 * @param array $params
 * @param string $anchor
 * @param string $ampersand
 * @return string 相对url地址
 */
function url($route, array $params=array(), $anchor = null, $ampersand='&')
{
    return Yii::app()->createUrl($route, $params, $ampersand) . ($anchor !== null ? '#' . $anchor : '');
}
/**
 * This is the shortcut to Yii::app()->createAbsoluteUrl()
 * @param string $route
 * @param array $params
 * @param string $anchor
 * @param string $ampersand
 * @return string 绝对url地址
 */
function aurl($route, array $params=array(), $schema='', $anchor = null, $ampersand='&')
{
    return Yii::app()->createAbsoluteUrl($route, $params, $schema, $ampersand) . ($anchor !== null ? '#' . $anchor : '');
}
 
/**
 * This is the shortcut to CHtml::encode
 * @param string $text 待处理字符串
 * @return string 使用CHtml::encode(即htmlspecialchars)处理过的字符串
 */
function h($text)
{
    return CHtml::encode($text);
}
 
/**
 * This is the shortcut to CHtml::link()
 * @param string $text 链接显示文本
 * @param string $url 链接地址
 * @param array $htmlOptions <a>标签附加属性
 * @return string <a>链接html代码
 */
function l($text, $url = '#', $htmlOptions = array())
{
    return CHtml::link($text, $url, $htmlOptions);
}

/**
 * This is the shortcut to CHtml::image()
 * @param string $src 图片url
 * @param string $alt img标签alt属性
 * @param array $htmlOptions <img>标签附加属性
 * @return string <img>html代码
 */
function image($src, $alt='', $htmlOptions=array())
{
    return CHtml::image($src, $alt, $htmlOptions);
}
 
/**
 * This is the shortcut to Yii::t() with default category = 'stay'
 */
function t($message, $category = 'main', $params = array(), $source = null, $language = null)
{
    return Yii::t($category, $message, $params, $source, $language);
}
 
/**
 * This is the shortcut to Yii::app()->request->baseUrl
 * If the parameter is given, it will be returned and prefixed with the app baseUrl.
 * @param string $url 相对url地址
 * @return string 返回相对url地址
 */
function bu($url = null)
{
    static $baseUrl = null;
    if ($baseUrl === null)
        $baseUrl = rtrim(Yii::app()->request->baseUrl, '/') . '/';
    return $url === null ? $baseUrl : $baseUrl . ltrim($url, '/');
}

/**
 * This is the shortcut to Yii::app()->request->getBaseUrl(true)
 * If the parameter is given, it will be returned and prefixed with the app absolute baseUrl.
 * @param string $url 相对url地址
 * @return string 返回绝对url地址
 */
function abu($url = null)
{
    if (filter_var($url, FILTER_VALIDATE_URL))
        return $url;
    
    static $baseUrl = null;
    if ($baseUrl === null)
        $baseUrl = rtrim(Yii::app()->request->getBaseUrl(true), '/') . '/';
    return $url === null ? $baseUrl : $baseUrl. ltrim($url, '/');
}
 
/**
 * Returns the named application parameter.
 * This is the shortcut to Yii::app()->params[$name].
 * @param string $name 参数名称
 * @return mixed 参数值
 */
function param($name)
{
    return Yii::app()->params[$name];
}

/**
 * alias param
 * @param string $name 参数名称
 * @return mixed 参数值
 */
function p($name)
{
    return Yii::app()->params[$name];
}
 
/**
 * This is the shortcut to Yii::app()->user.
 * @return CDWebUser
 */
function user()
{
    return Yii::app()->user;
}

/**
 * this is the shortcut to Yii::app()->theme->baseUrl
 * @param string $url
 * @return string Yii::app()->theme->baseUrl
 */
function tbu($url = null, $useDefault = true)
{
    if (empty(Yii::app()->theme))
        return sbu($url);
    
    static $themeBasePath;
    static $themeBaseUrl;
    $themeBasePath = rtrim(param('themeResourceBasePath'), DS) . DS . Yii::app()->theme->name . DS;
    $filename = realpath($themeBasePath . $url);
    if (file_exists($filename)) {
        $themeBaseUrl = rtrim(Yii::app()->theme->baseUrl, '/') . '/';
        return ($url === null) ? $themeBaseUrl : $themeBaseUrl . ltrim($url, '/');
    }
    elseif ($useDefault) {
        return sbu($url);
    }
    else
        return 'javascript:void(0);';
}

/**
 * This is the shortcut to Yii::app()->authManager.
 * @return IAuthManager Yii::app()->authManager
 */
function auth()
{
    return Yii::app()->authManager;
}

/**
 * 此函数返回附件地址相对于BasePath的物理路径
 * @param string $file 附件文件相对path地址
 * @return string
 */
function sbp($file = null)
{
    static $resourcePath = null;
    if ($resourcePath === null)
        $resourcePath = rtrim(param('resourceBasePath'), DS) . DS;

    return empty($file) ? $resourcePath : $resourcePath . ltrim($file, DS);
}

/**
 * 此函数返回附件地址的BaseUrl
 * @param string $url 静态资源文件相对url地址
 * @return string
 */
function sbu($url = null)
{
    static $resourceBaseUrl = null;
    if ($resourceBaseUrl === null)
        $resourceBaseUrl = rtrim(param('resourceBaseUrl'), '/') . '/';
    
    if (empty($url))
        return $resourceBaseUrl;
    else
        return (stripos($url, 'http://') === 0) ? $url : $resourceBaseUrl . ltrim($url, '/');
}

/**
 * This is the shortcut to Yii::app()->getStatePersister().
 * @return CStatePersister
 */
function sp()
{
    return Yii::app()->getStatePersister();
}

/**
 * This is the shortcut to Yii::app()->getSecurityManager().
 * @return CSecurityManager
 */
function sm()
{
    return Yii::app()->getSecurityManager();
}

/**
 * This is the shortcut to Yii::app()->request
 * @return CHttpRequest
 */
function request()
{
    return Yii::app()->request;
}
function dp($path = null)
{
    $dp = rtrim(param('dataPath'), DS) . DS;
    return $path ?  $dp . $path : $dp;
}

/**
 * 此函数返回附件地址相对于BasePath的物理路径
 * @param string $file 附件文件相对path地址
 * @return string
 */
function fbp($file = null)
{
    static $uploadBasePath = null;
    if ($uploadBasePath === null) {
        $uploader = app()->getComponent('localUploader');
        if ($uploader === null)
            throw new CDException('没有配置localuploader');
        $uploadBasePath = rtrim($uploader->basePath, DS) . DS;
    }

    return empty($file) ? $uploadBasePath : $uploadBasePath . ltrim($file, DS);
}

/**
 * 此函数返回附件地址的BaseUrl
 * @param string $file 附件文件相对url地址
 * @return string
 */
function fbu($file = null, $imageFile = true)
{
    $url = '';
    if (upyunEnabled())
        $url = upyunbu($file, $imageFile);
    else
        $url = localbu($file);

    return $url;
}

/**
 * 此函数返回附件保存在本地服务器时地址的BaseUrl
 * @param string $file 附件文件相对url地址
 * @return string
 */
function localbu($file = null)
{
    static $uploadBaseUrl = null;
    if ($uploadBaseUrl === null) {
        $uploader = app()->getComponent('localUploader');
        if ($uploader === null)
            throw new CDException('没有配置localUploader');

        $uploadBaseUrl = rtrim($uploader->baseUrl, '/') . '/';
    }

    if (empty($file))
        return $uploadBaseUrl;
    else
        return (stripos($file, 'http://') === 0) ? $file : $uploadBaseUrl . ltrim($file, '/');
}

/**
 * 此函数返回使用又拍云时附件地址的BaseUrl
 * @param string $file 附件文件相对url地址
 * @return string
 */
function upyunbu($file = null, $imageFile = true)
{
    static $uploadBaseUrl = null;
    $isImage = (int)$imageFile;
    if ($uploadBaseUrl[$isImage] === null) {
        $uploader = upyunUploader($imageFile);
        if ($uploader === null)
            throw new CDException('没有配置upyun uploader');

        $uploadBaseUrl[$isImage] = rtrim($uploader->baseUrl, '/') . '/';
    }

    if (empty($file))
        return $uploadBaseUrl[$isImage];
    else
        return (stripos($file, 'http://') === 0) ? $file : $uploadBaseUrl[$isImage] . ltrim($file, '/');
}

/**
 * 返回当前使用的uploader
 * @return CDUpyunUploader | CDLocalUploader 如果使用又拍云存储，则返回CDUpyunUploader，存储在硬盘，返回CDLocalUploader
 */
function uploader($image = true)
{
    $uploader = upyunEnabled() ? upyunUploader($image) : localuploader();
    return $uploader;
}

/**
 * 获取又拍云uploader component
 * @param string $image 如果为true, 则返回upyunImageUploader，否则返回upyunFileUploader
 * @return CDUpyunUploader 如果找不到组件，则返回null
 */
function upyunUploader($image = true)
{
    $component = (bool)$image ? 'upyunImageUploader' : 'upyunFileUploader';
    $uploader = app()->getComponent($component);
    return $uploader;
}

/**
 * 获取本地存储uploader component
 * @return CDLocalUploader 如果找不到组件，则返回null
 */
function localuploader()
{
    return app()->getComponent('localUploader');
}

/**
 * 当前环境是否使用又拍云
 * @return boolean
 */
function upyunEnabled()
{
    return (bool)param('upyun_enabled');
}

/**
 * 获取缓存组件
 * @param string $component
 * @return CCache | null
 */
function cache($component = 'cache')
{
    return app()->getComponent($component);
}

/**
 * 获取缓存组件
 * @param string $component
 * @return CFileCache | null
 */
function fcache()
{
    return app()->getComponent('fcache');
}

/**
 * 获取缓存组件
 * @param string $component
 * @return CMemCache | null
 */
function memcache()
{
    return app()->getComponent('memcache');
}

/**
 * 获取缓存组件
 * @param string $component
 * @return CDRedisCache | null
 */
function pcache()
{
    return app()->getComponent('redis');
}

/**
 * 获取数据库组件
 * @param string $component
 * @return CDbConnection | null
 */
function db($component = 'db')
{
    return app()->getComponent($component);
}

/**
 * 应用用户
 * @return CDAppUser
 */
function appuser()
{
    return app()->getComponent('appuser');
}

