<?php
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
defined('YII_DEBUG') or define('YII_DEBUG', true);
//!YII_DEBUG && error_reporting(0);

$cdc = dirname(__FILE__) . '/../library/framework/yii.php';
$short = dirname(__FILE__) . '/../library/shortcut.php';
require_once($cdc);
require_once($short);

$cfg = CDC_DEBUG ? 'main_develop.php' : 'main_product.php';
$config = dirname(__FILE__) . '/../protected/config/' . $cfg;

$app = Yii::createWebApplication($config);
$app->run();
