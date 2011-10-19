<?php
defined('CDC_DEBUG') or define('CDC_DEBUG', true);

//$cdc = dirname(__FILE__) . '/../framework/cdc.php';
//$cdc = '/Volumes/data/Webroot/cdcframework/framework/cdc.php';
$cdc = 'e:/Webroot/cdcframework/framework/cdc.php';
$cfg = CDC_DEBUG ? 'main_develop.php' : 'main_product.php';

$config = dirname(__FILE__) . '/../protected/config/' . $cfg;

require_once($cdc);

$app = Cdc::createApplication($config);
$app->run();
