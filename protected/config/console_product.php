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
            'connectionString' => sprintf('mysql:host=%s; port=%s; dbname=%s', DB_MYSQL_HOST, DB_MYSQL_PORT, DB_MYSQL_DBNAME),
            'username' => DB_MYSQL_USER,
            'password' => DB_MYSQL_PASSWORD,
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
        'redis' => array(
            'class' => 'application.extensions.CDRedisCache',
            'host' => '127.0.0.1',
            'port' => 6379,
            'timeout' => 3,
            'serializer' => $cacheSerializer,
            'options' => array(
                Redis::OPT_PREFIX => 'wdz_',
                Redis::OPT_SERIALIZER => extension_loaded('igbinary') ? Redis::SERIALIZER_IGBINARY : Redis::SERIALIZER_PHP,
            ),
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
