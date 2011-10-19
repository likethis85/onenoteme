<?php
return array(
    'basePath' => dirname(__FILE__) . DS . '..',
    'id' => 'cdcframework',
    'name' => 'cdc framework demo',
    'language' => 'zh_cn',
    'charset' => 'utf-8',

    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.apis.*',
        'application.libs.*',
    ),

    'components' => array(
        'db' => array(
            'class' => 'DDbConnection',
			'connectionString' => 'mysql:host=127.0.0.1; port=3306; dbname=cd_onenote',
			'username' => 'root',
		    'password' => '123',
		    'charset' => 'utf8',
		    'persistent' => true,
		    'tablePrefix' => 'cd_',
		    //'schemaCacheID' => 'fcache',
		    //'schemaCachingDuration' => 3600,    // metadata 缓存超时时间(s)
        ),
        'cache' => array(
            'class' => 'DFileCache',
		    'directoryLevel' => 2,
        ),
        'urlManager' => array(
            'urlFormat' => 'path',
		    'showScriptName' => false,
            'cacheID' => 'cache',
        ),
    ),
    
    'params' => array(
        'myname' => 'chen dong',
    ),
);