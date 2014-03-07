<?php
defined('CD_CONFIG_ROOT') or define('CD_CONFIG_ROOT', dirname(__FILE__));

try {
    $config = require(CD_CONFIG_ROOT . DS . (CD_PRODUCT ? 'main_product.php' : 'main_develop.php'));
    $params = require(CD_CONFIG_ROOT . DS . 'params.php');
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
    'id' => $config['id'],
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
        'rest',
    ),
    'components' => array(
        'errorHandler' => array(
            'errorAction' => 'site/error',
        ),
        'log' => $config['components.log'],
        'db' => $config['components.db'],
        'cache' => $config['components.cache'],
        'fcache' => $config['components.fcache'],
        'redis' => $config['components.redis'],
        'urlManager' => $config['components.urlManager'],
        'session' => $config['components.session'],
        'assetManager' => array(
            'basePath' => $params['resourceBasePath'] . 'assets',
            'baseUrl' => $params['resourceBaseUrl'] . 'assets',
        ),
        'authManager' => array(
            'class' => 'CDbAuthManager',
            'assignmentTable' => TABLE_AUTH_ASSIGMENT,
            'itemChildTable' => TABLE_AUTH_ITEMCHILD,
            'itemTable' => TABLE_AUTH_ITEM,
        ),
        'widgetFactory'=>array(
            'enableSkin' => true,
        ),
        'user' => array(
            'class' => 'CDWebUser',
            'allowAutoLogin' => true,
            'autoLoginDuration' => $params['autoLoginDuration'],
            'loginUrl' => array('/account/login'),
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


    
