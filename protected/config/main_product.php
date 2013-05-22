<?php
define('CD_CONFIG_ROOT', dirname(__FILE__));
defined('DS') or define('DS', DIRECTORY_SEPARATOR);

try {
    $params = require(CD_CONFIG_ROOT . DS . 'params_product.php');
    $cachefile = $params['dataPath'] . DS . 'setting.config.php';
    if (file_exists($cachefile)) {
        $customSetting = require($cachefile);
        $params = array_merge($params, $customSetting);
    }
}
catch (Exception $e) {
    echo $e->getMessage();
    exit(0);
}

return array(
    'basePath' => dirname(__FILE__) . DS . '..',
    'id' => 'waduanzi.com',
    'name' => '挖段子网',
    'language' => 'zh_cn',
    'charset' => 'utf-8',
    'timezone' => 'Asia/Shanghai',

    'import' => array(
        'application.dmodels.*',
        'application.models.*',
        'application.extensions.*',
        'application.components.*',
        'application.libs.*',
        'application.widgets.*',
    ),
    'modules' => array(
        'admin' => array(
            'layout' => 'main',
        ),
        'member' => array(
            'layout' => 'main',
        ),
        'mobile' => array(
            'layout' => 'main',
        ),
        'app' => array(
            'layout' => 'main',
        ),
    ),
    'components' => array(
        'errorHandler' => array(
            'errorAction' => 'site/error',
        ),
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
		    'queryCacheID' => 'redis',
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
            'options' => array(
                Redis::OPT_PREFIX => 'wdz_',
                Redis::OPT_SERIALIZER => extension_loaded('igbinary') ? Redis::SERIALIZER_IGBINARY : Redis::SERIALIZER_PHP,
            ),
        ),
        'assetManager' => array(
            'basePath' => $params['resourceBasePath'] . 'assets',
            'baseUrl' => $params['resourceBaseUrl'] . 'assets',
        ),
        'authManager' => array(
            'class' => 'CDbAuthManager',
            'assignmentTable' => '{{auth_assignment}}',
            'itemChildTable' => '{{auth_itemchild}}',
            'itemTable' => '{{auth_item}}',
        ),
        'widgetFactory'=>array(
            'enableSkin' => true,
        ),
        'urlManager' => array(
            'urlFormat' => 'path',
		    'showScriptName' => false,
            'cacheID' => 'cache',
            'rules' => array(
                'http://api.waduanzi.com/<_a>' => 'api/<_a>',
                
                'archives/<id:\d+>' => 'post/show',
                    
                'mobile/archives/<id:\d+>' => 'mobile/post/show',
                'mobile/page/<page:\d+>' => 'mobile/default/index',
                'mobile' => 'mobile/default/index',
                'mobile/<_a:(joke|lengtu|video|latest|hot|ghost|girl)>/page/<page:\d+>' => 'mobile/channel/<_a>',
                'mobile/<_a:(joke|lengtu|video|latest|hot|ghost|girl)>' => 'mobile/channel/<_a>',
                'mobile/tag/<name:.+>' => 'mobile/tag/posts',
            
                'page/<page:\d+>' => 'site/index',
                '/' => 'site/index',
                
                '<_a:(joke|lengtu|video|hot|day|week|month|latest|girl|ghost)>/page/<page:\d+>' => 'channel/<_a>',
                '<_a:(joke|lengtu|video|hot|day|week|month|latest|girl|ghost)>' => 'channel/<_a>',
                
                '<_a:(bdmap|links)>' => 'site/<_a>',
                '<_a:(login|logout|signup|quicklogin|activate)>' => 'account/<_a>',

                'tags' => 'tag/list',
                'tag/<name:.+>' => 'tag/posts',
                
                'sponsor/' => 'sponsor/index',
                
                'feed' => 'feed/index',
                'u/<id:\d+>' => 'user/index',
                'sitemap/<_a>' => array('sitemap/<_a>', 'urlSuffix'=>'.xml', 'caseSensitive'=>false),
                    
                'wap' => 'wap/index',
                'member' => '/member/default/index',
            ),
        ),
        'session' => array(
            'autoStart' => true,
            'sessionName' => 'wdz_ssid',
            'cookieParams' => array(
                'lifetime' => $params['autoLoginDuration'],
                'domain' => GLOBAL_COOKIE_DOMAIN,
                'path' => GLOBAL_COOKIE_PATH,
            ),
        ),
        'user' => array(
            'class' => 'CDWebUser',
            'allowAutoLogin' => true,
            'loginUrl' => array('/site/login'),
            'guestName' => '匿名段友',
        ),
        'mailer' => array(
            'class' => 'application.extensions.CDSendCloudMailer',
            'username' => 'postmaster@wdztrigger.sendcloud.org',
            'password' => 'voQh3RP5',
            'fromName' => '挖段子网',
            'fromAddress' => 'noreply@waduanzi.com',
            'replyTo' => 'service@waduanzi.com',
        ),
        'localUploader' => array(
            'class' => 'application.extensions.CDLocalUploader',
            'basePath' => $params['localUploadBasePath'],
            'baseUrl' => $params['localUploadBaseUrl'],
        ),
        'upyunImageUploader' => array(
            'class' => 'application.extensions.CDUpyunUploader',
            'isImageBucket' => true,
            'endpoint' => 'v1.api.upyun.com',
            'bucket' => 'wdzimage',
            'username' => 'cdcchen',
            'password' => 'cdc790406',
            'basePath' => '/',
            'baseUrl' => $params['upyunImageBaseUrl'],
        ),
        'upyunFileUploader' => array(
            'class' => 'application.extensions.CDUpyunUploader',
            'isImageBucket' => false,
            'endpoint' => 'v1.api.upyun.com',
            'bucket' => 'wdzfile',
            'username' => 'cdcchen',
            'password' => 'cdc790406',
            'basePath' => '/',
            'baseUrl' => $params['upyunFileBaseUrl'],
        ),
    ),
    
    'params' => $params,
);



    
