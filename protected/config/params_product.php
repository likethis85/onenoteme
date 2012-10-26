<?php
return array(
    // 缓存数据目录
    'dataPath' => CD_CONFIG_ROOT . DS . '..' . DS . 'data' . DS,
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
        CHANNEL_DUANZI => '挖笑话',
        CHANNEL_LENGTU => '挖趣图',
        CHANNEL_GIRL => '挖福利',
        CHANNEL_VIDEO => '挖视频',
        CHANNEL_MUSIC => '挖音乐',
        CHANNEL_FOCUS => '挖热点',
    ),

    // 默认评论是否需要审核, 1直接显示，0需要审核
    'defaultNewCommentState' => 1,
        
    // 简述中可以使用的html标签
    'summary_html_tags' => '<b><strong><img><p>',
    'mobile_summary_html_tags' => '<img>',
    
    // default param and value
    'post_list_type' => 0,
    'beian_code' => '',
    'tongji_code' => '',
    'header_html' => '',
    'footer_after_html' => '',
    'footer_before_html' => '',
    'site_keywords' => '',
    'site_description' => '',
    'filterKeywordReplacement' => '文明用语',
    'visit_nums_init_min' => 1,
    'visit_nums_init_max' => 1,
    'user_required_email_verfiy' => 0,
    'user_required_admin_verfiy' => 0,
    'auto_remote_image_local' => 0,
    'mobile_post_list_page_count' => 8,

    'sitename' => '挖段子',
    'shortdesc' => '笑死人不偿命 - 每日精品笑话连载',
    
    'autoLoginDuration' => 3600 * 24 * 7,
    'waterfall_post_count_page' => 32,
    'grid_post_count_page' => 15,
    'video_count_page' => 8,
    'duanzi_count_page' => 20,
    'lengtu_count_page' => 15,
    'girl_count_page' => 10,
    'commentCountOfPage' => 20,

    'user_required_email_verfiy' => 0,
    'user_required_admin_verfiy' => 0,
        
    /* sns params */
    'weibo_official_account' => '',
    'qqt_official_account' => '',
    'relative_weibo_accounts' => '',
    'relative_qqt_accounts' => '',
);