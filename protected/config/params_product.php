<?php
return array(

    'autoLoginDuration' => 3600 * 24 * 7,
    'waterfall_post_count_page' => 32,
    'grid_post_count_page' => 15,
    'video_count_page' => 8,
    'duanzi_count_page' => 20,
    'lengtu_count_page' => 15,
    'girl_count_page' => 10,
    'commentCountOfPage' => 20,

    'userIsRequireEmailVerify' => false,

    'uploadBasePath' => dirname(__FILE__) . DS . '..' . DS . '..' . DS . 'uploads' . DS,
    'uploadBaseUrl' => 'http://f.waduanzi.com/',
    'resourceBasePath' => dirname(__FILE__) . DS . '..' . DS . '..' . DS . 'resources' . DS,
    'resourceBaseUrl' => 'http://s.waduanzi.com/',

    /*
     * datetime format
     */
    'formatDateTime' => 'Y-m-d H:i:s',
    'formatShortDateTime' => 'Y-m-d H:i',
    'formatDate' => 'Y-m-d',
    'formatTime' => 'H:i:s',
    'formatShortTime' => 'H:i',
        
    'channels' => array(
        CHANNEL_DUANZI => '挖段子',
        CHANNEL_LENGTU => '挖冷图',
        CHANNEL_GIRL => '挖福利',
        CHANNEL_VIDEO => '挖短片',
    ),
);