<?php

class DefaultController extends MobileController
{
    public function filters()
    {
        return array(
            array(
                'COutputCache + index',
                'duration' => 120,
                'varyByParam' => array('page'),
            ),
        );
    }
    
	public function actionIndex()
	{
	    $data = self::fetchLatestPosts();
	    
	    $this->pageTitle = param('sitename') . '手机版 - ' . param('shortdesc');
        $this->setKeywords('挖段子,挖笑话,挖冷图,挖鬼故事,挖女神,挖视频,挖电影,精品笑话，内涵段子,内涵图,邪恶漫画,黄色笑话,幽默笑话,成人笑话,夫妻笑话,笑话集锦,荤段子,黄段子');
        $this->setDescription('最新发布的段子，每日精品笑话连载。网罗互联网各种精品段子，各种糗事，各种笑话，各种鬼故事,各种秘密，各种经典语录，各种有趣的图片，各种漂亮mm校花模特正妹，应有尽有。烦了、累了、无聊了，就来挖段子逛一逛。');
        
	    cs()->registerMetaTag('all', 'robots');
	    $this->render('index', $data);
	}

	public function actionError()
	{
	    $error = app()->errorHandler->error;
	    if ($error) {
	        if ($error['code'] == 404)
	            $this->redirect($this->getHomeUrl());
	        $this->pageTitle = 'Error ' . $error['code'];
	        $this->render('/system/error', $error);
	    }
	}
	
	private static function fetchLatestPosts()
	{
	    $channels = array(CHANNEL_FUNNY);
	    $mediaTypes = array(MEDIA_TYPE_TEXT, MEDIA_TYPE_IMAGE);
	    $criteria = new CDbCriteria();
	    if ($channels)
    	    $criteria->addInCondition('t.channel_id', $channels);
	    if ($mediaTypes)
    	    $criteria->addInCondition('t.media_type', $mediaTypes);
	    $criteria->order = 't.istop desc, t.create_time desc';
	    $criteria->limit = param('mobile_post_list_page_count');
	    $criteria->scopes = array('published');
	
	    $duration = 60 * 60 * 24;
	    $count = MobilePost::model()->cache($duration)->count($criteria);
	    $pages = new CPagination($count);
	    $pages->setPageSize(param('mobile_post_list_page_count'));
	    $pages->applyLimit($criteria);
	    $models = MobilePost::model()->findAll($criteria);
	
	    return array(
	        'models' => $models,
	        'pages' => $pages,
	    );
	}
}


