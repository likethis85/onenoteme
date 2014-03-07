<?php
YII_DEBUG or error_reporting(0);

define('DS', DIRECTORY_SEPARATOR);
define('CD_DOCUMENT_ROOT', dirname(__FILE__));
defined('CD_PRODUCT') or define('CD_PRODUCT', false);
defined('YII_DEBUG') or define('YII_DEBUG', true);
define('CD_CONFIG_ROOT', CD_DOCUMENT_ROOT . '/../protected/config');

$yii = CD_PRODUCT && extension_loaded('apc') ? 'yiilite.php' : 'yii.php';
$cdc = CD_DOCUMENT_ROOT . '/../library/framework/' . $yii;
$short = CD_DOCUMENT_ROOT . '/../library/shortcut.php';
$define = CD_CONFIG_ROOT . '/define.php';
require($define);
require($cdc);
require($short);

$app = Yii::createWebApplication(CD_CONFIG_ROOT . DS . 'main.php');
$app->run();