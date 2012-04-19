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
define('TABLE_NAME_POST', '{{post}}');
define('TABLE_NAME_USER', '{{user}}');
define('TABLE_NAME_COMMENT', '{{comment}}');
define('TABLE_NAME_DEVICE', '{{device}}');
define('TABLE_NAME_TAG', '{{tag}}');
define('TABLE_NAME_MOVIE', '{{movie}}');
define('TABLE_NAME_MOVIE_CATEGORY', '{{movie_category}}');
define('TABLE_NAME_MOVIE_SETS', '{{movie_sets}}');
define('TABLE_NAME_MOVIE_FAVORITE', '{{movie_favorite}}');
define('TABLE_NAME_MOVIE_SPECIAL', '{{movie_special}}');
define('TABLE_NAME_SPECIAL2MOVIE', '{{special2movie}}');