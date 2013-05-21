<?php
class ChannelController extends Controller
{
    public function filters()
    {
        $duration = 120;
        return array(
            array(
                'COutputCache + joke, lengtu, video, ghost, latest, hot, day, week, month',
                'duration' => $duration,
                'varyByParam' => array('page'),
                'varyByExpression' => array(user(), 'getIsGuest'),
                'requestTypes' => array('GET'),
            ),
        );
    }
    

    public function actionLatest($page = 1)
    {
        $this->channel = 'latest';
        $this->setSitePageTitle('');
        $this->setKeywords(p('home_index_keywords'));
        $this->setDescription(p('home_index_description'));
    
        $criteria = new CDbCriteria();
        $criteria->scopes = array('homeshow', 'published');
        $criteria->addColumnCondition(array('channel_id' => CHANNEL_FUNNY));
        $criteria->order = 't.istop desc, t.create_time desc';
        $criteria->limit = (int)p('line_post_count_page');
    
        $data = self::fetchPosts($criteria);
        $this->render('funny_hot', array(
            'models' => $data['models'],
            'pages' => $data['pages'],
        ));
    }
    
    public function actionHot($page = 1)
    {
        $this->setSitePageTitle('12小时内人最热门笑话');
    
        $mobileUrl = ($page > 1) ? aurl('mobile/default/index', array('page'=>$page)) : CDBaseUrl::mobileHomeUrl();
        cs()->registerMetaTag('format=html5;url=' . $mobileUrl, null, 'mobile-agent');
    
        $this->fetchFunnyHotPosts(8);
    }
    
    public function actionDay($page = 1)
    {
        $this->setSitePageTitle('24小时内人最热门笑话');
    
        $mobileUrl = ($page > 1) ? aurl('mobile/default/index', array('page'=>$page)) : CDBaseUrl::mobileHomeUrl();
        cs()->registerMetaTag('format=html5;url=' . $mobileUrl, null, 'mobile-agent');
    
        $this->fetchFunnyHotPosts(24);
    }
    
    public function actionWeek($page = 1)
    {
        $this->setSitePageTitle('一周内人最热门笑话');
    
        $mobileUrl = ($page > 1) ? aurl('mobile/default/index', array('page'=>$page)) : CDBaseUrl::mobileHomeUrl();
        cs()->registerMetaTag('format=html5;url=' . $mobileUrl, null, 'mobile-agent');
    
        $this->fetchFunnyHotPosts(7*24);
    }
    
    public function actionMonth($page = 1)
    {
        $this->setSitePageTitle('一月内人最热门笑话');
    
        $mobileUrl = ($page > 1) ? aurl('mobile/default/index', array('page'=>$page)) : CDBaseUrl::mobileHomeUrl();
        cs()->registerMetaTag('format=html5;url=' . $mobileUrl, null, 'mobile-agent');
    
        $this->fetchFunnyHotPosts(30*24);
    }
    
    public function actionJoke($page = 1)
    {
        $mobileUrl = ($page > 1) ? aurl('mobile/channel/joke', array('page'=>$page)) : aurl('mobile/channel/joke');
        cs()->registerMetaTag('format=html5;url=' . $mobileUrl, null, 'mobile-agent');
        
        cs()->registerLinkTag('alternate', 'application/rss+xml', aurl('feed/joke'), null, array('title'=>app()->name . ' » 挖笑话 Feed'));
        $this->pageTitle = p('channel_joke_title');
        $this->setDescription(p('channel_joke_description'));
        $this->setKeywords(p('channel_joke_keywords'));
        
        $count = (int)p('duanzi_count_page');
        $data = $this->fetchFunnyMediaPosts(MEDIA_TYPE_TEXT, $count);
        $this->render('text_list', $data);
    }
    
    public function actionLengtu($page = 1)
    {
        $mobileUrl = ($page > 1) ? aurl('mobile/channel/lengtu', array('page'=>$page)) : aurl('mobile/channel/lengtu');
        cs()->registerMetaTag('format=html5;url=' . $mobileUrl, null, 'mobile-agent');
        
        cs()->registerLinkTag('alternate', 'application/rss+xml', aurl('feed/lengtu'), null, array('title'=>app()->name . ' » 挖趣图 Feed'));
        $this->pageTitle = p('channel_lengtu_title');
        $this->setDescription(p('channel_lengtu_description'));
        $this->setKeywords(p('channel_lengtu_keywords'));
        
        $count = (int)p('lengtu_count_page');
        $data = $this->fetchFunnyMediaPosts(MEDIA_TYPE_IMAGE, $count, 'uploadImagesCount');
        $this->render('lengtu_list', $data);
    }
    
    public function actionVideo($page = 1)
    {
        $mobileUrl = ($page > 1) ? aurl('mobile/channel/video', array('page'=>$page)) : aurl('mobile/channel/video');
        cs()->registerMetaTag('format=html5;url=' . $mobileUrl, null, 'mobile-agent');
        
        cs()->registerLinkTag('alternate', 'application/rss+xml', aurl('feed/video'), null, array('title'=>app()->name . ' » 挖视频 Feed'));
        $this->pageTitle = p('channel_video_title');
        $this->setDescription(p('channel_video_description'));
        $this->setKeywords(p('channel_video_keywords'));
        
        $count = (int)p('video_count_page');
        $data = $this->fetchFunnyMediaPosts(MEDIA_TYPE_VIDEO, $count);
        $this->render('video_list', $data);
    }

    public function actionGhost()
    {
        $this->redirect(CDBaseUrl::siteHomeUrl(), true, 301);
    }
    
    public function actionGirl()
    {
        $this->redirect(CDBaseUrl::siteHomeUrl(), true, 301);
    }
    
    private function fetchFunnyHotPosts($hours)
    {
        $this->channel = 'hot';
        $this->setKeywords(p('home_index_keywords'));
        $this->setDescription(p('home_index_description'));
    
        $criteria = new CDbCriteria();
        $criteria->scopes = array('homeshow', 'published');
        $criteria->addColumnCondition(array('channel_id' => CHANNEL_FUNNY));
        if ($hours > 0) {
            $fromtime = $_SERVER['REQUEST_TIME'] - $hours * 3600;
            $criteria->addCondition('t.create_time > :fromtime');
            $criteria->params[':fromtime'] = $fromtime;
        }
        $criteria->order = 't.istop desc, (t.up_score-t.down_score) desc, t.create_time desc';
        $limit = (int)p('line_post_count_page');
        $criteria->limit = $limit;
    
        $data = self::fetchPosts($criteria, $hours);
        $this->render('funny_hot', array(
            'models' => $data['models'],
            'pages' => $data['pages'],
        ));
    }
    
    private static function fetchPosts(CDbCriteria $criteria)
    {
        $duration = 60*60*24;
        $count = Post::model()->cache($duration)->count($criteria);
        $pages = new CPagination($count);
        $pages->setPageSize($criteria->limit);
        $pages->applyLimit($criteria);
    
        $models = Post::model()->findAll($criteria);
    
        return array(
            'models' => $models,
            'pages' => $pages,
        );
    }
    

    private function fetchFunnyMediaPosts($typeid, $limit, $with = null)
    {
        $this->channel = CHANNEL_FUNNY . $typeid;
        $this->setKeywords(p('home_index_keywords'));
        $this->setDescription(p('home_index_description'));
    
        $criteria = new CDbCriteria();
        $criteria->scopes = array('published');
        $criteria->addColumnCondition(array('channel_id' => CHANNEL_FUNNY, 'media_type'=>(int)$typeid));
        $criteria->order = 't.istop desc, t.create_time desc';
        $criteria->limit = $limit;
        if (!empty($with))
            $criteria->with = $with;
    
        return self::fetchPosts($criteria);
    }


}

