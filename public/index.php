<?php
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
defined('YII_PRODUCT') or define('YII_PRODUCT', false);
defined('YII_DEBUG') or define('YII_DEBUG', true);
YII_DEBUG or error_reporting(0);

$bootstrap = extension_loaded('apc') ? 'yiilite.php' : 'yii.php';
$cdc = dirname(__FILE__) . '/../library/framework/yii.php';
$short = dirname(__FILE__) . '/../library/shortcut.php';
$define = dirname(__FILE__) . '/../protected/config/define.php';
require_once($define);
require_once($cdc);
require_once($short);

$config = dirname(__FILE__) . '/../protected/config/' . (YII_PRODUCT ? 'main_product.php' : 'main_develop.php');

$app = Yii::createWebApplication($config);
$app->run();