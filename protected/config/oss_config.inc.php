<?php
//个人测试
//ACCESS_ID
define('OSS_ACCESS_ID', 'h4p2fw4j9ostgs3ssohe2jzk');

//ACCESS_KEY
define('OSS_ACCESS_KEY', 'rb1D8x1gzzACQAoLgM1PcNP8KFU=');


//是否输出DEBUG
define('DEBUG', FALSE);

//语言版本设置
define('LANG', 'zh');

/*%****************************************************************************************%*/
//文件上传相关

//设置每个php进程的内存消耗值,对应于php.ini里的memory_limit
define('MAX_MEMORY_LIMIT', '256M');

//设置每个php进程的最大执行时间
define('MAX_EXECUTE_TIME','3600');

//上传文件的最大值,默认值128M
define('MAX_UPLOAD_FILE_SIZE', 128 * 1024 * 1024);

//定义软件名称，版本号等信息
define('OSS_NAME','oss-sdk-php');
define('OSS_VERSION','1.0.0');
define('OSS_BUILD','20111108162025');
define('OSS_AUTHOR', 'xiaobing.meng@alibaba-inc.com');
