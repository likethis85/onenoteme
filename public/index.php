<?php
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
defined('YII_PRODUCT') or define('YII_PRODUCT', false);
defined('YII_DEBUG') or define('YII_DEBUG', true);
//!YII_DEBUG && error_reporting(0);

$cdc = dirname(__FILE__) . '/../library/framework/yii.php';
$short = dirname(__FILE__) . '/../library/shortcut.php';
require_once($cdc);
require_once($short);

$config = dirname(__FILE__) . '/../protected/config/' . (YII_PRODUCT ? 'main_product.php' : 'main_develop.php');

$app = Yii::createWebApplication($config);
$app->run();
