<?php

$c['id']  = 'waduanzi.com';

$c['components.log'] = array(
    'class'=>'CLogRouter',
    'routes'=>array(
        array(
            'class'=>'CFileLogRoute',
            'categories'=>'system.db.*',
        ),
        /* array(
            'class'=>'CWebLogRoute',
            'levels'=>'trace,info,error,notice',
            'categories'=>'system.db.*',
        ), */
    ),
);

$c['components.db'] = array(
    'class' => 'CDbConnection',
    'connectionString' => sprintf('mysql:host=%s; port=%s; dbname=%s', DB_MYSQL_HOST, DB_MYSQL_PORT, DB_MYSQL_DBNAME),
    'username' => DB_MYSQL_USER,
    'password' => DB_MYSQL_PASSWORD,
    'charset' => 'utf8',
    'persistent' => false,
    'tablePrefix' => 'cd_',
    'attributes' => array(
        PDO::ATTR_ORACLE_NULLS => PDO::NULL_TO_STRING,
        PDO::ATTR_EMULATE_PREPARES => true,
    ),
//    'enableParamLogging' => true,
//    'enableProfiling' => true,
    'schemaCacheID' => 'cache',
    'schemaCachingDuration' => 3600 * 24,    // metadata 缓存超时时间(s)
    'queryCacheID' => 'redis',
    'queryCachingDuration' => 60,
);

$c['components.cache'] = array(
    'class'=>'application.extensions.CDMemCache',
    'serializer' => ccacheSerializer(),
    'useMemcached' => extension_loaded('memcached'),
    'username' => '131ce938744011e3',
    'password' => 'cdc_wdz_790406',
    'options' => array(
        Memcached::OPT_COMPRESSION => false,
        Memcached::OPT_BINARY_PROTOCOL => true,
        Memcached::OPT_SERIALIZER => extension_loaded('igbinary') ? Memcached::SERIALIZER_IGBINARY : Memcached::SERIALIZER_PHP,
    ),
    'servers'=>array(
        array('host'=>'131ce938744011e3.m.cnhzalicm10pub001.ocs.aliyuncs.com', 'port'=>11211, 'timeout' =>3, 'weight'=>100),
//        array('host'=>'localhost', 'port'=>22122, 'timeout' =>3, 'weight'=>100),
    ),
);

$c['components.fcache'] = array(
    'serializer' => $cacheSerializer,
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

$c['components.urlManager'] = array(
    'urlFormat' => 'path',
    'showScriptName' => false,
    'caseSensitive' => false,
    'cacheID' => 'cache',
    'rules' => array(
        'http://api.waduanzi.com/<_a>' => 'api/<_a>',

        'archives/<id:\d+>' => 'post/show',

        'mobile/archives/<id:\d+>' => 'mobile/post/show',
        'mobile/page/<page:\d+>' => 'mobile/default/index',
        'mobile' => 'mobile/default/index',
        'mobile/<_a:(joke|lengtu|video|latest|hot|day|week|month|girl|focus)>/page/<page:\d+>' => 'mobile/channel/<_a>',
        'mobile/<_a:(joke|lengtu|video|latest|hot|day|week|month|girl|focus)>' => 'mobile/channel/<_a>',
        'mobile/tag/<name:.+>' => 'mobile/tag/posts',

        'page/<page:\d+>' => 'site/index',
        '/' => 'site/index',

        '<_a:(joke|lengtu|video|hot|day|week|month|latest|girl|focus)>/page/<page:\d+>' => 'channel/<_a>',
        '<_a:(joke|lengtu|video|hot|day|week|month|latest|girl|focus)>' => 'channel/<_a>',

        '<_a:(bdmap|links)>' => 'site/<_a>',
        '<_a:(login|logout|signup|quicklogin|activate)>' => 'account/<_a>',

        'tags' => 'tag/list',
        'tag/<name:.+>' => 'tag/posts',

        'sponsor/' => 'sponsor/index',

        'feed' => 'feed/index',
        'u/<id:\d+>' => 'user/index',
        'sitemap/<_a>' => array('sitemap/<_a>', 'urlSuffix'=>'.xml', 'caseSensitive'=>false),

        'member' => '/member/default/index',

        'http://rest.waduanzi.com/post/timeline/<user_id:\d+>' => 'rest/post/timeline',
        'http://rest.waduanzi.com/post/show/<post_id:\d+>' => 'rest/post/show',
        'http://rest.waduanzi.com/comment/show/<post_id:\d+>' => 'rest/comment/show',
        'http://rest.waduanzi.com/post/<_a:(support|oppose|like|unlike)>/<post_id:\d+>' => 'rest/post/<_a>',
        'http://rest.waduanzi.com/comment/<_a:(support|report)>/<comment_id:\d+>' => 'rest/comment/<_a>',
        'http://rest.waduanzi.com/user/show/<user_id:\d+>' => 'rest/user/show',
        'http://rest.waduanzi.com/<_c>/<_a>' => 'rest/<_c>/<_a>',
    ),
);

$c['components.session'] = array(
    'autoStart' => true,
    'sessionName' => 'wdz_ssid',
    'cookieParams' => array(
        'lifetime' => $params['autoLoginDuration'],
        'domain' => GLOBAL_COOKIE_DOMAIN,
        'path' => GLOBAL_COOKIE_PATH,
    ),
);

return $c;


    
