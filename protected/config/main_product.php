<?php
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
$params = require(dirname(__FILE__) . DS . 'params_product.php');

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
        'application.components.widgets.*',
    ),
    'modules' => array(
        'admin' => array(
            'layout' => 'main',
        ),
    ),
    'components' => array(
        'db' => array(
            'class' => 'CDbConnection',
			'connectionString' => 'mysql:host=localhost; port=3306; dbname=cd_waduanzi',
			'username' => 'root',
		    'password' => 'cdc_790406',
		    'charset' => 'utf8',
		    'persistent' => true,
		    'tablePrefix' => 'cd_',
//             'enableParamLogging' => true,
//             'enableProfiling' => true,
		    'schemaCacheID' => 'cache',
		    'schemaCachingDuration' => 3600 * 24,    // metadata 缓存超时时间(s)
		    'queryCacheID' => 'cache',
		    'queryCachingDuration' => 60,
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
                'http://api.waduanzi.com/<_a>' => 'api/<_a>',
                    
                    
                '' => 'site/index',
                'static/<view:[\w\d]+>' => 'site/page',
        
                'post/list-<cid:\d+>-<page:\d+>' => 'post/list',
                'post/list-<cid:\d+>' => 'post/list',
                '<_a:(duanzi|lengtu|girl|video)>-<page:\d+>' => 'channel/<_a>',
                '<_a:(duanzi|lengtu|girl|video)>' => 'channel/<_a>',
                'post-<id:\d+>' => 'post/show',
                'live-<page:\d+>' => 'post/live',
                'live' => 'post/live',
                'hottest' => 'post/hottest',
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


    