<?php
return array(
    // 缓存数据目录
    'dataPath' => CD_CONFIG_ROOT . DS . '..' . DS . 'data' . DS,
    'localUploadBasePath' => dirname(__FILE__) . DS . '..' . DS . '..' . DS . 'uploads' . DS,
    'localUploadBaseUrl' => 'http://f.waduanzi.cn/',
    'upyunImageBaseUrl' => 'http://wdztest.b0.upaiyun.com/',
    'upyunFileBaseUrl' => 'http://f2.wabao.me/',
    'resourceBasePath' => dirname(__FILE__) . DS . '..' . DS . '..' . DS . 'resources' . DS,
    'resourceBaseUrl' => 'http://s.waduanzi.cn/',
    'upyun_enabled' => false,

    /*
     * datetime format
     */
    'formatDateTime' => 'Y-m-d H:i:s',
    'formatShortDateTime' => 'Y-m-d H:i',
    'formatDate' => 'Y-m-d',
    'formatTime' => 'H:i:s',
    'formatShortTime' => 'H:i',
        
    /*
     * 前台相关参数
    */
    // 默认评论是否需要审核, 1直接显示，0需要审核
    'default_mobile_new_comment_state' => 1,

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
    'shortdesc' => '笑死人不偿命',
    
    'autoLoginDuration' => 3600 * 24 * 7,
    'waterfall_post_count_page' => 20,
    'line_post_count_page' => 20,
    'grid_post_count_page' => 28,
    'video_count_page' => 15,
    'duanzi_count_page' => 30,
    'lengtu_count_page' => 20,
    'girl_count_page' => 20,
    'commentCountOfPage' => 20,
    'comment_count_page_home' => 20,
    // 评论最少字符
    'comment_min_length' => 2,

    'user_required_email_verfiy' => 0,
    'user_required_admin_verfiy' => 0,
        
    /* sns params */
    'weibo_official_account' => '',
    'qqt_official_account' => '',
    'relative_weibo_accounts' => '',
    'relative_qqt_accounts' => '',

    /* cache setting */
    'mobile_post_list_cache_expire' => 60 * 60,
    'mobile_comment_list_cache_expire' => 10,
    'mobile_post_show_cache_expire' => 10,
        
    'wdz_weixin_account_name' => '挖段子搞笑',
    'wdz_weixin_account_id' => 'gh_9261dce78e9f',

    'cache_adcodes_id' => 'cd_adcodes_id_%s',
        

    'default_mini_avatar' => 'images/mini_avatar.png',
    'default_small_avatar' => 'images/small_avatar.png',
    'default_large_avatar' => 'images/large_avatar.png',
);


