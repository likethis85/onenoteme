<?php
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
$params = require(dirname(__FILE__) . DS . 'params_product.php');

return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'id' => 'waduanzi.com',
	'name'=>'Daily Jokes',
    'language' => 'zh_cn',
    'charset' => 'utf-8',
    'timezone' => 'Asia/Shanghai',

    'import' => array(
        'application.dmodels.*',
        'application.models.*',
        'application.extensions.*',
        'application.libs.*',
    ),
	'components'=>array(
		'db' => array(
            'class' => 'CDbConnection',
            'connectionString' => sprintf('mysql:host=%s; port=%s; dbname=%s', MYSQL_HOST, MYSQL_PORT, MYSQL_DBNAME),
            'username' => MYSQL_USER,
            'password' => MYSQL_PASSWORD,
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
            'serializer' => array('igbinary_serialize', 'igbinary_unserialize'),
            'class'=>'CMemCache',
            'useMemcached' => extension_loaded('memcached'),
            'servers'=>array(
                array('host'=>'localhost', 'port'=>22122, 'weight'=>100),
            ),
        ),
        'fcache' => array(
            'serializer' => array('igbinary_serialize', 'igbinary_unserialize'),
            'class' => 'CFileCache',
		    'directoryLevel' => 2,
        ),
        'apn' => array(
            'class' => 'CDApnProvider',
            'sandbox' => false,
            'cert' => dirname(__FILE__) . DS . 'product_ck.pem',
            'pass' => '',
        ),
	),
    'params' => $params,
);
