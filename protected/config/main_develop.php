<?php
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
$params = require(dirname(__FILE__) . DS . 'params_develop.php');

return array(
    'basePath' => dirname(__FILE__) . DS . '..',
    'id' => 'onenote.me',
    'name' => '挖段子',
    'language' => 'zh_cn',
    'charset' => 'utf-8',
    'timezone' => 'Asia/Shanghai',

    'import' => array(
        'application.dmodels.*',
        'application.models.*',
        'application.extensions.*',
        'application.components.*',
        'application.apis.*',
        'application.libs.*',
        'application.components.widgets.*',
    ),
    'modules' => array(
        'admin' => array(
            'layout' => 'main',
        ),
        'gii'=>array(
            'class'=>'system.gii.GiiModule',
            'password'=>'123',
            // 'ipFilters'=>array(...a list of IPs...),
        ),
    ),
    'preload' => array('log'),
    'components' => array(
        'log' => array(
            'class'=>'CLogRouter',
            'routes'=>array(
                array(
                    'class'=>'CFileLogRoute',
                    'categories'=>'system.db.*',
                ),
                array(
                    'class'=>'CWebLogRoute',
                    'levels'=>'trace,info,error,notic',
                    'categories'=>'system.db.*',
                ),
            ),
        ),
        'db' => array(
            'class' => 'CDbConnection',
			'connectionString' => 'mysql:host=127.0.0.1; port=3306; dbname=cd_waduanzi',
			'username' => 'root',
		    'password' => '123',
		    'charset' => 'utf8',
		    'persistent' => true,
		    'tablePrefix' => 'cd_',
            'enableParamLogging' => true,
            'enableProfiling' => true,
//		    'schemaCacheID' => 'cache',
//		    'schemaCachingDuration' => 3600,    // metadata 缓存超时时间(s)
//		    'queryCacheID' => 'cache',
//		    'queryCachingDuration' => 3600,
        ),
        'cache' => array(
            'class' => 'CFileCache',
		    'directoryLevel' => 2,
        ),
        'assetManager' => array(
            'basePath' => $params['resourceBasePath'] . 'assets',
            'baseUrl' => $params['resourceBaseUrl'] . 'assets',
        ),
        'urlManager' => array(
            'urlFormat' => 'path',
		    'showScriptName' => false,
            'cacheID' => 'cache',
            'rules' => array(
                '' => 'site/index',
                'static/<view:[\w\d]+>' => 'site/page',
        
                'post/list-<cid:\d+>-<page:\d+>' => 'post/list',
                'post/list-<cid:\d+>' => 'post/list',
                'post-<id:\d+>' => 'post/show',
                'post-<_a:[\w\d]+>-<page:\d+>' => 'post/<_a>',
                'post-<_a:[\w\d]+>' => 'post/<_a>',
        
                'tags' => 'tag/list',
                'tag-<name:[\w\d%]+>' => 'tag/posts',
            ),
        ),
        'user' => array(
            'allowAutoLogin' => true,
            'loginUrl' => array('site/login'),
            'guestName' => '匿名人士',
        ),
    ),
    
    'params' => $params,
);


    