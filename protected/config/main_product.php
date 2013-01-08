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
            'serializer' => array('igbinary_serialize', 'igbinary_unserialize'),
            'class'=>'CMemCache',
            'useMemcached' => true,
            'servers'=>array(
                array('host'=>'localhost', 'port'=>22122, 'weight'=>100),
            ),
        ),
        'fcache' => array(
            'serializer' => array('igbinary_serialize', 'igbinary_unserialize'),
            'class' => 'CFileCache',
		    'directoryLevel' => 2,
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
            
                'http://<_a:(joke|lengtu|girl|video)>.waduanzi.com/page/<page:\d+>&s=<s:\w+>' => 'channel/<_a>',
                'http://<_a:(joke|lengtu|girl|video)>.waduanzi.com/page/<page:\d+>' => 'channel/<_a>',
                'http://<_a:(joke|lengtu|girl|video)>.waduanzi.com/' => 'channel/<_a>',
                
                'http://www.waduanzi.com/page/<page:\d+>' => 'site/index',
                'http://www.waduanzi.com/' => 'site/index',
                'http://www.waduanzi.com/<_a:(login|signup|logout|bdmap|links)>' => 'site/<_a>',
                'http://www.waduanzi.com/<_a:(duanzi|lengtu|girl|video)>-<page:\d+>' => 'channel/<_a>',
                'http://www.waduanzi.com/<_a:(duanzi|lengtu|girl|video)>' => 'channel/<_a>',
                'http://www.waduanzi.com/archives/<id:\d+>' => 'post/show',
                'http://www.waduanzi.com/post-<id:\d+>' => 'post/detail',
                'http://www.waduanzi.com/originalpic/<id:\d+>' => 'post/bigpic',
                'http://www.waduanzi.com/tags' => 'tag/list',
                'http://www.waduanzi.com/tag/<name:.+>' => 'tag/posts',
                'http://www.waduanzi.com/tag-<name:.+>' => 'tag/archives',
                'http://www.waduanzi.com/feed/<cid:\d+>' => 'feed/channel',
                'http://www.waduanzi.com/u/<id:\d+>' => 'user/index',
                    
                'http://wap.waduanzi.com/' => 'wap/index',
                'http://wap.waduanzi.com/<_a>' => 'wap/<_a>',
            
                'http://m.waduanzi.com/page/<page:\d+>' => 'mobile/default/index',
                'http://m.waduanzi.com/' => 'mobile/default/index',
                'http://m.waduanzi.com/<_a:(joke|girl|lengtu|video)>/page/<page:\d+>' => 'mobile/channel/<_a>',
                'http://m.waduanzi.com/<_a:(joke|girl|lengtu|video)>' => 'mobile/channel/<_a>',
                'http://m.waduanzi.com/archives/<id:\d+>' => 'mobile/post/show',
                'http://m.waduanzi.com/post-<id:\d+>' => 'mobile/post/detail',
                'http://m.waduanzi.com/tag/<name:.+>' => 'mobile/tag/posts',
                'http://m.waduanzi.com/tag-<name:.+>' => 'mobile/tag/archives',
                'http://m.waduanzi.com/<_c>' => 'mobile/<_c>',
                'http://m.waduanzi.com/<_c>/<_a>' => 'mobile/<_c>/<_a>',
            
                'http://my.waduanzi.com/' => '/member/default/index',
                'http://my.waduanzi.com/<_c>' => 'member/<_c>',
                'http://my.waduanzi.com/<_c>/<_a>' => 'member/<_c>/<_a>',
            ),
        ),
        'session' => array(
            'autoStart' => true,
            'cookieParams' => array(
                'lifetime' => $params['autoLoginDuration'],
                'domain' => '.waduanzi.com',
                'path' => '/',
            ),
        ),
        'user' => array(
            'class' => 'CDWebUser',
            'allowAutoLogin' => true,
            'loginUrl' => array('/site/login'),
            'guestName' => '匿名段友',
        ),
    ),
    
    'params' => $params,
);



    