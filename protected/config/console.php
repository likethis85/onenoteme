<?php
defined('CD_CONFIG_ROOT') or define('CD_CONFIG_ROOT', dirname(__FILE__));

try {
    $config = require(CD_CONFIG_ROOT . DS . (CD_PRODUCT ? 'console_product.php' : 'console_develop.php'));
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
		'db' => $config['components.db'],
        'cache' => $config['components.cache'],
        'fcache' => $config['components.fcache'],
        'redis' => $config['components.redis'],
        'apn' => $config['components.apn'],
	),
    'params' => $params,
);
