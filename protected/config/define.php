<?php
define('CD_YES', 1);
define('CD_NO', 0);

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
define('TABLE_POST', '{{post}}');
define('TABLE_POST_TEMP', '{{post_temp}}');
define('TABLE_WEIBO_ACCOUNT', '{{weibo_account}}');
define('TABLE_USER', '{{user}}');
define('TABLE_COMMENT', '{{comment}}');
define('TABLE_DEVICE', '{{device}}');
define('TABLE_TAG', '{{tag}}');
define('TABLE_POST_TAG', '{{post_tag}}');
define('TABLE_MOVIE_SETS', '{{movie_sets}}');
define('TABLE_POST_FAVORITE', '{{post_favorite}}');
define('TABLE_WEIBO_ID', '{{weibo_id}}');