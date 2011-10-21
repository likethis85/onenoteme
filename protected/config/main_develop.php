<?php
defined('DS') or define('DS', DIRECTORY_SEPARATOR);

$params = require(dirname(__FILE__) . DS . YII_DEBUG ? 'params_develop.php' : 'params.php');

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
        'application.components.*',
        'application.apis.*',
        'application.libs.*',
    ),
    'preload' => array('log'),
    'components' => array(
        'log' => array(
            'class' => 'CLogRouter',
			'routes' => array(
				array(
					'class' => 'CFileLogRoute',
					'levels' => 'trace, info, error, warning, watch',
				    'categories' => 'system.db.*',
				),
				array(
					'class' => 'CWebLogRoute',
					'levels' => 'trace, info, error, warning, watch',
				    'categories' => 'system.db.*',
				),
            ),
        ),
        'db' => array(
            'class' => 'CDbConnection',
			'connectionString' => 'mysql:host=127.0.0.1; port=3306; dbname=cd_onenote',
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
                'site-<_a:[\w\d]+>' => 'site/<_a>',
                'list-<cid:\d+>-<page:\d+>' => 'post/list',
                'list-<cid:\d+>' => 'post/list',
                '<_a:[\w\d]+>-<page:\d+>' => 'post/<_a>',
                '<_a:[\w\d]+>' => 'post/<_a>',
                'static/<view:[\w\d]+>' => 'site/page',
            ),
        ),
    ),
    
    'params' => $params,
);