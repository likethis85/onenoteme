<?php

class ChannelController extends MobileController
{
    public function filters()
    {
        return array(
            array(
                'COutputCache + joke, lengtu, girl, video, ghost',
                'duration' => param('mobile_post_list_cache_expire'),
                'varyByParam' => array('page'),
                'varyByExpression' => array(request(), 'getServerName'),
            ),
        );
    }

	public function actionJoke($page = 1)
	{
	    $data = self::fetchPosts(CHANNEL_FUNNY, MEDIA_TYPE_TEXT);
	     
	    $this->pageTitle = '挖笑话 - 最冷笑话精选，每天分享笑话N枚，你的贴身开心果';
        $this->setDescription($this->pageTitle);
        $this->setKeywords('挖笑话,糗事,内涵笑话,爆笑笑话,幽默笑话,笑话大全,爆笑短信,xiaohua,冷笑话,短信笑话,小笑话,笑话短信,经典笑话,冷笑话大全,短笑话,搞笑短信,笑话大全乐翻天,搞笑笑话,疯狂恶搞,爆笑童趣,雷人囧事');
        
        $this->channel = CHANNEL_FUNNY . MEDIA_TYPE_TEXT;
	    cs()->registerMetaTag('all', 'robots');
	    $this->render('posts', $data);
	}

	public function actionGhost($page = 1)
	{
	    $data = self::fetchPosts(CHANNEL_GHOSTSTORY, MEDIA_TYPE_TEXT);
	     
	    $this->pageTitle = '挖鬼故事 - 让鬼友们在生活中找点恐怖的小刺激';
        $this->setDescription($this->pageTitle);
        $this->setKeywords('鬼故事,短篇鬼故事,恐怖鬼故事,校园鬼故事,鬼故事大全');
        
        $this->channel = CHANNEL_GHOSTSTORY . MEDIA_TYPE_TEXT;
	    cs()->registerMetaTag('all', 'robots');
	    $this->render('posts', $data);
	}
	
	public function actionLengtu($page = 1)
	{
	    $data = self::fetchPosts(CHANNEL_FUNNY, MEDIA_TYPE_IMAGE);
	     
	    $this->pageTitle = '挖趣图 - 最搞笑的，最好玩的，最内涵的图片精选';
        $this->setDescription($this->pageTitle);
        $this->setKeywords('挖趣图,搞笑图片,内涵图,邪恶图片,色色图,暴走漫画,微漫画,4格漫画,8格漫画,搞笑漫画,内涵漫画,邪恶漫画,疯狂恶搞,爆笑童趣,雷人囧事,动画萌图,狗狗萌图,猫咪萌图,喵星人萌图,汪星人萌图');
        
        $this->channel = CHANNEL_FUNNY . MEDIA_TYPE_IMAGE;
	    cs()->registerMetaTag('all', 'robots');
	    $this->render('posts', $data);
	}
	
	public function actionGirl($page = 1)
	{
	    if (!user()->getIsVip())
	        throw new CHttpException(403, '该频道不存在');
	    
	    $data = self::fetchPosts(CHANNEL_GIRL, MEDIA_TYPE_IMAGE);
	     
	    $this->pageTitle = '挖福利 - 最新最全的女明星写真、清纯校花、美女模特、正妹性感自拍';
        $this->setDescription($this->pageTitle);
        $this->setKeywords('阳光正妹,清纯学生,网友自拍,香港模特,台湾正妹,美女自拍,淘女郎,微女郎,美女写真,美女私房照,校花,气质美女,清纯美女,性感车模,比基尼,足球宝贝');
        
        $this->channel = CHANNEL_GIRL . MEDIA_TYPE_IMAGE;
	    cs()->registerMetaTag('all', 'robots');
	    $this->render('posts', $data);
	}
	
	public function actionVideo($page = 1)
	{
	    $data = self::fetchPosts(CHANNEL_FUNNY, MEDIA_TYPE_VIDEO);
	     
	    $this->pageTitle = '挖短片 - 各种有趣的，新奇的，经典的，有意思的精品视频短片';
        $this->setDescription($this->pageTitle);
        $this->setKeywords('搞笑视频短片,cf搞笑视频,lol搞笑视频,搞笑视频,疯狂恶搞,爆笑童趣,雷人囧事,动物奇趣,优酷搞笑视频,娱乐搞笑视频,土豆搞笑视频,激动搞笑视频');
        
        $this->channel = CHANNEL_FUNNY . MEDIA_TYPE_VIDEO;
	    cs()->registerMetaTag('all', 'robots');
	    $this->render('posts', $data);
	}

	private static function fetchPosts($channelid = null, $typeid = null, $categoryid = null, $with = '')
	{
	    $duration = 60 * 60 * 24;
	    $limit = (int)$limit;
	    
	    $criteria = new CDbCriteria();
	    $criteria->order = 't.istop desc, t.create_time desc';
	    $criteria->limit = param('mobile_post_list_page_count');
	    $criteria->scopes = array('published');
	    
	    if ($categoryid !== null) {
	        $categoryid = (int)$categoryid;
	        $criteria->addColumnCondition(array('t.category_id'=>$categoryid));
	    }
	    if ($channelid !== null) {
	        $channelid = (int)$channelid;
	        $criteria->addColumnCondition(array('t.channel_id'=>$channelid));
	    }
	    if ($typeid !== null) {
	        $typeid = (int)$typeid;
	        $criteria->addColumnCondition(array('t.media_type'=>$typeid));
	    }
	
	    $count = MobilePost::model()->cache($duration)->count($criteria);
	    $pages = new CPagination($count);
	    $pages->setPageSize(param('mobile_post_list_page_count'));
	    $pages->applyLimit($criteria);
	    
	    if ($with)
	        $models = MobilePost::model()->with($with)->findAll($criteria);
	    else
	        $models = MobilePost::model()->findAll($criteria);
	    
	    return array(
	        'models' => $models,
	        'pages' => $pages,
	    );
	}
}