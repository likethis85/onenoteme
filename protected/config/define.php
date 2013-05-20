<?php
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
define('CHANNEL_DUANZI', 0);
define('CHANNEL_LENGTU', 20);
define('CHANNEL_VIDEO', 40);

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
define('TABLE_FILTER_KEYWORD', '{{filter_keyword}}');
define('TABLE_LINK', '{{link}}');
define('TABLE_ADVERT', '{{advert}}');
define('TABLE_ADCODE', '{{adcode}}');
define('TABLE_UPLOAD', '{{upload}}');
define('TABLE_IOS_DEVICE', '{{ios_device}}');
define('TABLE_POST_TEMP', '{{post_temp}}');
define('TABLE_WEIBO_ACCOUNT', '{{weibo_account}}');
define('TABLE_WEIBO_ID', '{{weibo_id}}');

define('POST_STATE_ENABLED', 1);
define('POST_STATE_DISABLED', 0);
define('POST_STATE_UNVERIFY', -1);
define('POST_STATE_TRASH', -99);


define('COMMENT_STATE_ENABLED', 1);
define('COMMENT_STATE_DISABLED', 0);

/* user state */
define('USER_STATE_UNVERIFY', 0);
define('USER_STATE_ENABLED', 1);
define('USER_STATE_FORBIDDEN', -1);
/* advert state */
define('ADVERT_STATE_DISABLED', 0);
define('ADVERT_STATE_ENABLED', 1);
/* advert state */
define('ADCODE_STATE_DISABLED', 0);
define('ADCODE_STATE_ENABLED', 1);

/* link ishome */
define('LINK_IN_HOME', 1);
define('LINK_NOT_IN_HOME', 0);


define('POST_LIST_STYLE_LINE', 'line');
define('POST_LIST_STYLE_GRID', 'grid');

/*
 * 这些尺寸与又拍云里的自定义版本相对应
 */
define('IMAGE_THUMBNAIL_WIDTH', 200);
define('IMAGE_THUMBNAIL_HEIGHT', 260);
define('IMAGE_THUMBNAIL_SQUARE_SIZE', 200);

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
define('UPYUN_AVATAR_CUSTOM_LARGE', 'lavatar');
define('UPYUN_AVATAR_CUSTOM_SMALL', 'savatar');
define('UPYUN_AVATAR_CUSTOM_MINI', 'mavatar');

define('IMAGE_MAX_HEIGHT_FOLDING', 700);
define('IMAGE_WATER_URL_SIZE', 200);
define('IMAGE_WATER_SITENAME_SIZE', 350);

define('GENDER_UNKOWN', 0);
define('GENDER_FEMALE', 1);
define('GENDER_MALE', 2);

define('WEIBO_APP_KEY', '2981913360');
define('WEIBO_APP_SECRET', 'f06fd0b530f3d9daa56db67e5e8610e1');
define('QQT_APP_KEY', '801080691');
define('QQT_APP_SECRET', '4e0c4ac86b36120efac4c44a9ac9e895');

define('USER_DEFAULT_AVATAR_URL', 'images/default_avatar.png');








