<?php
/**
 * 注意:需要PHP 5.3版本或以上
 * 使用时只需要使用
 * include /pathto/sendcloud_php/SendCloudLoader.php即可。
 */

/** 定义目录为当前目录 */
define("ROOT_DIR", __dir__ . DIRECTORY_SEPARATOR);

/**
 * SendClou自动加载依赖类。
 * @author delong
 */
function sendCloudLoader() {
	/** PHP Mailer依赖 */
    if (!class_exists('PHPMailer', false))
    	require ROOT_DIR . '/lib/phpmailer/class.phpmailer.php';
    if (!class_exists('SMTP', false))
	require ROOT_DIR . '/lib/phpmailer/class.smtp.php';
	require ROOT_DIR . '/lib/phpmailer/language/phpmailer.lang-zh_cn.php';
	// SendCloud依赖
	/** SendCloud依赖 */
	require 'SendCloud.php';
	require 'SendCloud/Smtp.php';
	require 'SendCloud/Message.php';
	require 'SendCloud/AppFilter.php';
	require 'SendCloud/SmtpApiHeader.php';
}

// spl_autoload_register("sendCloudLoader", true, true);