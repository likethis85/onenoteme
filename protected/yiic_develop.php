<?php
YII_DEBUG || error_reporting(0);

defined('DS') or define('DS', DIRECTORY_SEPARATOR);
defined('YII_PRODUCT') or define('YII_PRODUCT', true);
defined('YII_DEBUG') or define('YII_DEBUG', false);
define('CD_COMMAND_ROOT', dirname(__FILE__));
define('CD_CONFIG_ROOT', dirname(__FILE__) . '/config');

$short = dirname(__FILE__) . '/../library/shortcut.php';
$define = CD_CONFIG_ROOT . DS . 'define.php';
require($define);
require($short);

// change the following paths if necessary
$yiic = CD_COMMAND_ROOT . '/../library/framework/yiic.php';
$config = CD_CONFIG_ROOT . DS . 'console.php';

require($yiic);
