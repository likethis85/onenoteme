<?php
class ChannelController extends Controller
{
    public function beforeAction($action)
    {
        $this->autoSwitchMobile();
        return true;
    }
    
    public function filters()
    {
        $duration = 120;
        return array(
            array(
                'COutputCache + joke, lengtu, girl, video, ghost',
                'duration' => $duration,
                'varyByExpression' => 'user()->getIsGuest() && $this->mobileDevice',
                'varyByParam' => array('page', 's'),
                'requestTypes' => array('POST'),
            ),
            array(
                'COutputCache + joke, lengtu, girl, video, ghost',
                'duration' => $duration,
                'varyByExpression' => 'user()->getIsGuest()',
                'varyByParam' => array('page', 's'),
                'requestTypes' => array('GET'),
            ),
        );
    }
    
    public function actionJoke($page = 1, $s = POST_LIST_STYLE_LINE)
    {
        cs()->registerLinkTag('alternate', 'application/rss+xml', aurl('feed/joke'), null, array('title'=>app()->name . ' » 挖笑话 Feed'));
        $this->pageTitle = param('channel_joke_title');
        $this->setDescription(param('channel_joke_description'));
        $this->setKeywords(param('channel_joke_keywords'));
        
        $this->channel = CHANNEL_DUANZI;
        $count = ($s == POST_LIST_STYLE_WATERFALL) ? param('waterfall_post_count_page') : param('duanzi_count_page');
        $data = $this->fetchChannelPosts(CHANNEL_DUANZI, $count);
        $view = ($s == POST_LIST_STYLE_WATERFALL) ? '/post/mixed_list' : 'text_list';
        if (request()->getIsAjaxRequest())
            $this->renderPartial($view, $data);
        else
            $this->render($view, $data);
    }

    public function actionGhost($page = 1, $s = POST_LIST_STYLE_LINE)
    {
        cs()->registerLinkTag('alternate', 'application/rss+xml', aurl('feed/joke'), null, array('title'=>app()->name . ' » 挖鬼故事 Feed'));
        $this->pageTitle = param('channel_ghost_title');
        $this->setDescription(param('channel_ghost_description'));
        $this->setKeywords(param('channel_ghost_keywords'));
    
        $this->channel = CHANNEL_GHOSTSTORY;
        $count = ($s == POST_LIST_STYLE_WATERFALL) ? param('waterfall_post_count_page') : param('ghost_story_count_page');
        $data = $this->fetchChannelPosts(CHANNEL_GHOSTSTORY, $count);
        $view = ($s == POST_LIST_STYLE_WATERFALL) ? '/post/mixed_list' : 'text_list';
        if (request()->getIsAjaxRequest())
            $this->renderPartial($view, $data);
        else
            $this->render($view, $data);
    }
    
    public function actionLengtu($page = 1, $s = POST_LIST_STYLE_LINE)
    {
        cs()->registerLinkTag('alternate', 'application/rss+xml', aurl('feed/lengtu'), null, array('title'=>app()->name . ' » 挖趣图 Feed'));
        $this->pageTitle = param('channel_lengtu_title');
        $this->setDescription(param('channel_lengtu_description'));
        $this->setKeywords(param('channel_lengtu_keywords'));
        $this->channel = CHANNEL_LENGTU;
        
        $list_view = 'line_list';
        if (($s == POST_LIST_STYLE_GRID)) {
            $list_view = 'grid_list';
            $count = param('grid_post_count_page_column_four');
        }
        elseif ($s == POST_LIST_STYLE_WATERFALL)
            $count = param('waterfall_post_count_page');
        else
            $count = param('lengtu_count_page');
        
        $data = $this->fetchChannelPosts(CHANNEL_LENGTU, $count);
        $data['list_view'] = '/post/' . $list_view;
        $view = (($s == POST_LIST_STYLE_WATERFALL)) ? '/post/mixed_list' : 'lengtu_list';
        if (request()->getIsAjaxRequest())
            $this->renderPartial($view, $data);
        else
            $this->render($view, $data);
    }
    
    public function actionGirl($page = 1, $s = POST_LIST_STYLE_GRID)
    {
        cs()->registerLinkTag('alternate', 'application/rss+xml', aurl('feed/girl'), null, array('title'=>app()->name . ' » 挖女神 Feed'));
        $this->pageTitle = param('channel_girl_title');
        $this->setDescription(param('channel_girl_description'));
        $this->setKeywords(param('channel_girl_keywords'));
        $this->channel = CHANNEL_GIRL;
        
        $list_view = 'line_list';
        if (($s == POST_LIST_STYLE_GRID)) {
            $list_view = 'grid_list';
            $count = param('grid_post_count_page_column_three');
        }
        elseif ($s == POST_LIST_STYLE_WATERFALL)
            $count = param('waterfall_post_count_page');
        else
            $count = param('girl_count_page');
        
        $data = $this->fetchChannelPosts(CHANNEL_GIRL, $count);
        $data['list_view'] = '/post/' . $list_view;
        $view = (($s == POST_LIST_STYLE_WATERFALL)) ? '/post/mixed_list' : 'girl_list';
        if (request()->getIsAjaxRequest())
            $this->renderPartial($view, $data);
        else
            $this->render($view, $data);
    }
    
    public function actionVideo($page = 1)
    {
        cs()->registerLinkTag('alternate', 'application/rss+xml', aurl('feed/video'), null, array('title'=>app()->name . ' » 挖视频 Feed'));
        $this->pageTitle = param('channel_video_title');
        $this->setDescription(param('channel_video_description'));
        $this->setKeywords(param('channel_video_keywords'));
        
        $this->channel = CHANNEL_VIDEO;
        $data = $this->fetchChannelPosts(CHANNEL_VIDEO, param('video_count_page'));
        $this->render('video_list', $data);
    }

    private function fetchChannelPosts($channelid, $limit = 0)
    {
        $duration = 60 * 60 * 24;
        $channelid = (int)$channelid;
        $limit = (int)$limit;
        if ($limit === 0)
            $limit = param('line_post_count_page');
        
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(array('t.channel_id'=>$channelid, 't.state'=>POST_STATE_ENABLED));
        $criteria->order = 't.istop desc, t.create_time desc, t.id desc';
        $criteria->limit = $limit;
        
        $count = Post::model()->cache($duration)->count($criteria);
        $pages = new CPagination($count);
        $pages->setPageSize($limit);
        $pages->applyLimit($criteria);
        
        $page = $pages->getCUrrentPage();
        if ($page < $_GET[$pages->pageVar]-1)
            return array();
    
        $models = Post::model()->findAll($criteria);
    
        if ($page > 1)
            $mobileUrl = aurl('mobile/'. $this->id . '/' . $this->action->id, array('page'=>$page));
        else
            $mobileUrl = aurl('mobile/'. $this->id . '/' . $this->action->id);
        cs()->registerMetaTag('format=html5;url=' . $mobileUrl, null, 'mobile-agent');
            
        $data  = array(
            'models' => $models,
            'pages' => $pages,
        );
        
        return $data;
    }

}

