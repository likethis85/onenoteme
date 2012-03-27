<?php
define('CHANNEL_DUANZI', 0);
define('CHANNEL_LENGTU', 20);
define('CHANNEL_GIRL', 30);
define('CHANNEL_VIDEO', 40);

$channels = array(
    CHANNEL_DUANZI => '挖段子',
    CHANNEL_LENGTU => '挖冷图',
    CHANNEL_GIRL => '挖福利',
    CHANNEL_VIDEO => '挖好片',
);


// 以下是表名
define('TABLE_NAME_POST', '{{post}} t');
define('TABLE_NAME_USER', '{{user}} t');
define('TABLE_NAME_COMMENT', '{{comment}} t');
define('TABLE_NAME_DEVICE', '{{device}} t');
define('TABLE_NAME_TAG', '{{tag}} t');