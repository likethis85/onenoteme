<?php

define('DB_MYSQL_HOST', 'cdcchen.mysql.rds.aliyuncs.com');
define('DB_MYSQL_PORT', '3306');
define('DB_MYSQL_DBNAME', 'cd_waduanzi');
define('DB_MYSQL_USER', 'waduanzi');
define('DB_MYSQL_PASSWORD', 'cdc_790406');

//define('MYSQL_HOST', 'localhost');
//define('MYSQL_PORT', '3306');
//define('MYSQL_DBNAME', 'cd_waduanzi');
//define('MYSQL_USER', 'root');
//define('MYSQL_PASSWORD', 'cdc_790406');

define('CD_YES', 1);
define('CD_NO', 0);

define('SITE_DOMAIN', 'waduanzi.com');
define('CD_LAST_VISIT', 'wdz_lastvisit');
define('CD_CLIENT_ID', 'wdz_clientid');
define('GLOBAL_COOKIE_DOMAIN', '.' . SITE_DOMAIN);
define('GLOBAL_COOKIE_PATH', '/');

define('MEDIA_TYPE_UNKOWN', 0);
define('MEDIA_TYPE_TEXT', 10);
define('MEDIA_TYPE_IMAGE', 20);
define('MEDIA_TYPE_AUDIO', 30);
define('MEDIA_TYPE_VIDEO', 40);


define('CHANNEL_FUNNY', 1);
define('CHANNEL_FOCUS', 2);

// 以下是表名
define('TABLE_CATEGORY', '{{category}}');
define('TABLE_POST', '{{post}}');
define('TABLE_CONFIG', '{{config}}');
define('TABLE_COMMENT', '{{comment}}');
define('TABLE_TAG', '{{tag}}');
define('TABLE_POST_TAG', '{{post_tag}}');
define('TABLE_MOVIE_SETS', '{{movie_sets}}');
define('TABLE_POST_FAVORITE', '{{post_favorite}}');
define('TABLE_USER', '{{user}}');
define('TABLE_USER_PROFILE', '{{user_profile}}');
define('TABLE_USER_WEIXIN', '{{user_weixin}}');
define('TABLE_USER_YIXIN', '{{user_yixin}}');
define('TABLE_FILTER_KEYWORD', '{{filter_keyword}}');
define('TABLE_LINK', '{{link}}');
define('TABLE_ADVERT', '{{advert}}');
define('TABLE_ADCODE', '{{adcode}}');
define('TABLE_UPLOAD', '{{upload}}');
define('TABLE_MOBILE_DEVICE', '{{mobile_device}}');
define('TABLE_IOS_DEVICE', '{{ios_device}}');
define('TABLE_POST_TEMP', '{{post_temp}}');
define('TABLE_WEIBO_ACCOUNT', '{{weibo_account}}');
define('TABLE_WEIBO_ID', '{{weibo_id}}');
define('TABLE_APP_SYSTEM_CONFIG', '{{app_system_config}}');
define('TABLE_APP_USER_CONFIG', '{{app_user_config}}');
define('TABLE_FEEDBACK', '{{feedback}}');
define('TABLE_POST_VIDEO', '{{post_video}}');
define('TABLE_APP_UNION_LOG', '{{app_union_log}}');
define('TABLE_APP_ADSLOT', '{{app_adslot}}');
define('TABLE_APP_ADVERT', '{{app_advert}}');

define('POST_STATE_ENABLED', 1);
define('POST_STATE_DISABLED', 0);
define('POST_STATE_UNVERIFY', -1);
define('POST_STATE_TRASH', -99);


define('COMMENT_STATE_ENABLED', 1);
define('COMMENT_STATE_DISABLED', 0);

define('COMMENT_SOURCE_UNKNOWN', 0);
define('COMMENT_SOURCE_PC_WEB', 1);
define('COMMENT_SOURCE_MOBILE_WEB', 2);
define('COMMENT_SOURCE_IPHONE', 3);
define('COMMENT_SOURCE_IPAD', 4);
define('COMMENT_SOURCE_ANDROID', 5);

/* user state */
define('USER_STATE_UNVERIFY', 0);
define('USER_STATE_ENABLED', 1);
define('USER_STATE_FORBIDDEN', -1);

/* source */
define('USER_SOURCE_UNKNOWN', 0);
define('USER_SOURCE_PC_WEB', 1);
define('USER_SOURCE_MOBILE_WEB', 2);
define('USER_SOURCE_IPHONE', 3);
define('USER_SOURCE_IPAD', 4);
define('USER_SOURCE_ANDROID', 5);

/* advert state */
define('ADVERT_STATE_DISABLED', 0);
define('ADVERT_STATE_ENABLED', 1);
/* advert state */
define('ADCODE_STATE_DISABLED', 0);
define('ADCODE_STATE_ENABLED', 1);

/* app adslot state */
define('APP_ADSLOT_STATE_DISABLED', 0);
define('APP_ADSLOT_STATE_ENABLED', 1);
/* app advert state */
define('APP_ADVERT_STATE_DISABLED', 0);
define('APP_ADVERT_STATE_ENABLED', 1);

/* link ishome */
define('LINK_IN_HOME', 1);
define('LINK_NOT_IN_HOME', 0);

/* post content level */
define('CONTENT_LEVEL_NORMAL', 0);
define('CONTENT_LEVEL_SLIGHT', 1);
define('CONTENT_LEVEL_FORBIDDEN', 10);

/* score */
define('PUBLISH_SCORE', 10);
define('POST_COMMENT', 1);

/* feedback */
define('NETWORK_STATUS_UNKOWN', -1);
define('NETWORK_STATUS_NOT_REACHABLE', 0);
define('NETWORK_STATUS_WWAN', 1);
define('NETWORK_STATUS_WIFI', 2);

/* app platform */
define('PLATFORM_IPHONE', 1);
define('PLATFORM_IPAD', 2);
define('PLATFORM_ANDROID', 3);

/*
 * 这些尺寸与又拍云里的自定义版本相对应
 */
define('IMAGE_THUMBNAIL_WIDTH', 200);
define('IMAGE_THUMBNAIL_HEIGHT', 260);
define('IMAGE_THUMBNAIL_SQUARE_SIZE', 200);
define('IMAGE_RECT_IMAGE_WIDTH', 200);
define('IMAGE_RECT_IMAGE_HEIGHT', 112);

// APP 专用
define('IMAGE_APP_THUMB_WIDTH', 200);
define('IMAGE_APP_THUMB_HEIGHT', 260);
define('IMAGE_APP_MIDDLE_WIDTH', 640);

define('IMAGE_THUMB_WIDTH', 200);
define('IMAGE_SMALL_WIDTH', 320);
define('IMAGE_MIDDLE_WIDTH', 640);
define('IMAGE_LARGE_WIDTH', 1280);

define('AVATAR_MINI_SIZE', 24);
define('AVATAR_SMALL_SIZE', 48);
define('AVATAR_LARGE_SIZE', 144);

define('UPYUN_IMAGE_CUSTOM_SEPARATOR', '!');
define('UPYUN_IMAGE_CUSTOM_THUMB', 'thumb');
define('UPYUN_IMAGE_CUSTOM_SMALL', 'small');
define('UPYUN_IMAGE_CUSTOM_MIDDLE', 'middle');
define('UPYUN_IMAGE_CUSTOM_LARGE', 'large');
define('UPYUN_IMAGE_CUSTOM_FIXTHUBM', 'fixthumb');
define('UPYUN_IMAGE_CUSTOM_SQUARETHUBM', 'squarethumb');
define('UPYUN_IMAGE_CUSTOM_RECTTHUBM', 'rectthumb');
define('UPYUN_AVATAR_CUSTOM_LARGE', 'lavatar');
define('UPYUN_AVATAR_CUSTOM_SMALL', 'savatar');
define('UPYUN_AVATAR_CUSTOM_MINI', 'mavatar');

// app
define('UPYUN_IMAGE_CUSTOM_APP_THUMB', 'appthumb');
define('UPYUN_IMAGE_CUSTOM_APP_MIDDLE', 'appmiddle');

/*
 * 列表页，图片尺寸超过多大会折叠
 */
define('IMAGE_MAX_HEIGHT_FOLDING', 1000);

/*
 * 图片尺寸添加水印阀值
 */
define('IMAGE_WATER_URL_SIZE', 200);
define('IMAGE_WATER_SITENAME_SIZE', 400);

/*
 * 内容列表页，图片最大尺寸
 */
define('POST_LIST_IMAGE_MAX_WIDTH', 580);
define('MOBILE_POST_LIST_IMAGE_MAX_WIDTH', 300);

define('GENDER_UNKOWN', 0);
define('GENDER_FEMALE', 1);
define('GENDER_MALE', 2);

define('CLIENT_ID_YOUKU', '1f2d57f9b5ea2ce9');
define('CLIENT_ID_56', '3000003067');

define('WEIBO_APP_KEY', '2981913360');
define('WEIBO_APP_SECRET', 'f06fd0b530f3d9daa56db67e5e8610e1');
define('QQT_APP_KEY', '801080691');
define('QQT_APP_SECRET', '4e0c4ac86b36120efac4c44a9ac9e895');
define('BAIDU_APP_WDZ_APP_KEY', 'bGGXpA6v8UVnwdUEZkNgde4o');
define('BAIDU_APP_WDZ_SECRET_KEY', 'BtbYXKyVGiwT8toE3Mdiwpa1p00DbAqd');

define('BAIDU_DEVICE_TYPE_BROWSER', 1);
define('BAIDU_DEVICE_TYPE_PC', 2);
define('BAIDU_DEVICE_TYPE_ANDROID', 3);
define('BAIDU_DEVICE_TYPE_IOS', 4);
define('BAIDU_DEVICE_TYPE_WINDOWS_PHONE', 5);

define('BAIDU_MESSAGE_TYPE_MESSAGE', 0);
define('BAIDU_MESSAGE_TYPE_ALERT', 1);

define('USER_DEFAULT_AVATAR_URL', 'images/default_avatar.png');

define('IPHONE_APP_URL', 'http://itunes.apple.com/cn/app/id486268988?mt=8');
define('ANDROID_APP_URL', 'http://s0.wabao.me/android/waduanzi.apk');

// @todo 此常量值是添加内容分级功能的时间戳，因为此时间以前内容比较多，无法手动修改，只能将其全部归成敏感内容，不显示adsense广告。
// 2013-10-30 20:00:00
define('SITE_ADD_CONTENT_LEVEL_TIMESTAMP', '1383163200');





