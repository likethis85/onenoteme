<?php
$c['id'] = 'waduanzi.cn';

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
    'connectionString' => 'mysql:host=127.0.0.1; port=3306; dbname=cd_waduanzi',
    'username' => 'root',
    'password' => '123',
    'charset' => 'utf8',
    'persistent' => true,
    'tablePrefix' => 'cd_',
    'attributes' => array(
        PDO::ATTR_ORACLE_NULLS => PDO::NULL_TO_STRING,
        PDO::ATTR_EMULATE_PREPARES => true,
    ),
    'enableParamLogging' => true,
    'enableProfiling' => true,
//    'schemaCacheID' => 'cache',
//    'schemaCachingDuration' => 3600,    // metadata 缓存超时时间(s)
//    'queryCacheID' => 'cache',
		    'queryCachingDuration' => 3600,
);

$c['components.cache'] = array(
    'class'=>'CMemCache',
//    'serializer' => ccacheSerializer(),
    'useMemcached' => extension_loaded('memcached'),
    'servers'=>array(
        array('host'=>'localhost', 'port'=>11211, 'timeout' =>3, 'weight'=>100),
    ),
);

$c['components.fcache'] = array(
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
    'rules' => array(
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

        'rest/post/timeline/<user_id:\d+>' => 'rest/post/timeline',
        'rest/post/show/<post_id:\d+>' => 'rest/post/show',
        'rest/user/show/<user_id:\d+>' => 'rest/user/show',
        'rest/comment/show/<post_id:\d+>' => 'rest/comment/show',
        'rest/comment/report/<comment_id:\d+>' => 'rest/comment/report',
    ),
);

$c['components.session'] = array(
    'autoStart' => true,
    'cookieParams' => array(
        'lifetime' => $params['autoLoginDuration'],
        'domain' => '.waduanzi.cn',
        'path' => '/',
    ),
);

return $c;
    