<?php
define('CD_CONFIG_ROOT', dirname(__FILE__));
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
$params = require(dirname(__FILE__) . DS . 'params_develop.php');

return array(
    'basePath' => dirname(__FILE__) . DS . '..',
    'id' => 'waduanzi.com',
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
        'application.widgets.*',
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
                /* array(
                    'class'=>'CWebLogRoute',
                    'levels'=>'trace,info,error,notic',
                    'categories'=>'system.db.*',
                ), */
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
//             'cacheID' => 'cache',
            'rules' => array(
                '' => 'site/index',
                '<_a:(duanzi|lengtu|girl|video)>-<page:\d+>' => 'channel/<_a>',
                '<_a:(duanzi|lengtu|girl|video)>' => 'channel/<_a>',
                'post-<id:\d+>' => 'post/show',
                'tags' => 'tag/list',
                'tag-<name:[\w\s\%\-\+\.]+>' => 'tag/posts',
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


    