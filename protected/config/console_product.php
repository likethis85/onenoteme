<?php

$c['id'] = 'waduanzi.com';

$c['components.db'] = array(
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
);

$c['components.cache'] = array(
    'serializer' => ccacheSerializer(),
    'class'=>'CMemCache',
    'useMemcached' => extension_loaded('memcached'),
    'servers'=>array(
        array('host'=>'cb190624cf5b11e3.m.cnhzalicm10pub001.ocs.aliyuncs.com', 'port'=>11211, 'timeout' =>3, 'weight'=>100),
//        array('host'=>'localhost', 'port'=>22122, 'weight'=>100),
    ),
);

$c['components.fcache'] = array(
    'serializer' => ccacheSerializer(),
    'class' => 'CFileCache',
    'directoryLevel' => 2,
);

$c['components.redis'] = array(
    'class' => 'application.extensions.CDRedisCache',
    'host' => '127.0.0.1',
    'port' => 6379,
    'timeout' => 3,
    'serializer' => ccacheSerializer(),
    'options' => array(
        Redis::OPT_PREFIX => 'wdz_',
        Redis::OPT_SERIALIZER => extension_loaded('igbinary') ? Redis::SERIALIZER_IGBINARY : Redis::SERIALIZER_PHP,
    ),
);

$c['components.apn'] = array(
    'class' => 'CDApnProvider',
    'sandbox' => false,
    'cert' => dirname(__FILE__) . DS . 'product_ck.pem',
    'pass' => '',
);

return $c;