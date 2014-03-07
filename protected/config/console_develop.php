<?php

$c['id'] = 'waduanzi.cn';

$c['components.log'] = array(
    'class'=>'CLogRouter',
    'routes'=>array(
        array(
            'class'=>'CFileLogRoute',
            'categories'=>'system.db.*',
        ),
    ),
);

$c['components.db'] = array(
    'class' => 'CDbConnection',
    'connectionString' => 'mysql:host=127.0.0.1; port=3306; dbname=cd_waduanzi',
    'username' => 'root',
    'password' => '123',
    'charset' => 'utf8',
    'persistent' => true,
    'tablePrefix' => 'cd_',
    'enableParamLogging' => true,
    'enableProfiling' => true,
);

$c['components.cache'] = array(
    'class' => 'CFileCache',
    'serializer' => ccacheSerializer(),
    'directoryLevel' => 2,
);

$c['components.fcache'] = array(
    'class' => 'CFileCache',
    'serializer' => ccacheSerializer(),
    'directoryLevel' => 2,
);

$c['components.redis'] = array(
    'class' => 'application.extensions.CDRedisCache',
    'host' => '127.0.0.1',
    'port' => 6379,
    'timeout' => 3,
    'serializer' => $cacheSerializer,
    'options' => array(
        Redis::OPT_PREFIX => 'wdz_',
        Redis::OPT_SERIALIZER => extension_loaded('igbinary') ? Redis::SERIALIZER_IGBINARY : Redis::SERIALIZER_PHP,
    ),
);

$c['components.apn'] = array(
    'class' => 'CDApnProvider',
    'sandbox' => true,
    'cert' => dirname(__FILE__) . DS . 'develop_ck.pem',
    'pass' => '',
);

return $c;