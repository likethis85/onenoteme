<?php
class ChannelController extends Controller
{
    public function filters()
    {
        $duration = 120;
        return array(
            array(
                'COutputCache + joke, duanzi, lengtu, girl, video',
                'duration' => $duration,
                'varyByExpression' => 'user()->getIsGuest()',
                'varyByParam' => array('page', 's'),
                'requestTypes' => array('POST'),
            ),
            array(
                'COutputCache + joke, duanzi, lengtu, girl, video',
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
        $this->pageTitle = '挖笑话 - 最冷笑话精选，每天分享笑话N枚，你的贴身开心果';
        $this->setDescription($this->pageTitle);
        $this->setKeywords('挖笑话,糗事,内涵笑话,爆笑笑话,幽默笑话,笑话大全,爆笑短信,xiaohua,冷笑话,短信笑话,小笑话,笑话短信,经典笑话,冷笑话大全,短笑话,搞笑短信,笑话大全乐翻天,搞笑笑话,疯狂恶搞,爆笑童趣,雷人囧事');
        
        $this->channel = CHANNEL_DUANZI;
        $count = ($s == POST_LIST_STYLE_WATERFALL) ? param('waterfall_post_count_page') : param('duanzi_count_page');
        $data = $this->fetchChannelPosts(CHANNEL_DUANZI, $count);
        $view = ($s == POST_LIST_STYLE_WATERFALL) ? '/post/mixed_list' : 'text_list';
        if (request()->getIsAjaxRequest())
            $this->renderPartial($view, $data);
        else
            $this->render($view, $data);
    }
    
    public function actionDuanzi($page = 1, $s = POST_LIST_STYLE_LINE)
    {
        $this->redirect(url('channel/joke', array('page'=>$page, 's'=>$s)), true, 301);
    }
    
    public function actionLengtu($page = 1, $s = POST_LIST_STYLE_LINE)
    {
        cs()->registerLinkTag('alternate', 'application/rss+xml', aurl('feed/lengtu'), null, array('title'=>app()->name . ' » 挖趣图 Feed'));
        $this->pageTitle = '挖趣图 - 最搞笑的，最好玩的，最内涵的图片精选';
        $this->setDescription($this->pageTitle);
        $this->setKeywords('挖趣图,搞笑图片,内涵图,邪恶图片,色色图,暴走漫画,微漫画,4格漫画,8格漫画,搞笑漫画,内涵漫画,邪恶漫画,疯狂恶搞,爆笑童趣,雷人囧事,动画萌图,狗狗萌图,猫咪萌图,喵星人萌图,汪星人萌图');
        $this->channel = CHANNEL_LENGTU;
        
        $list_view = 'line_list';
        if (($s == POST_LIST_STYLE_GRID)) {
            $list_view = 'grid_list';
            $count = param('grid_post_count_page');
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
    
    public function actionGirl($page = 1, $s = POST_LIST_STYLE_WATERFALL)
    {
        cs()->registerLinkTag('alternate', 'application/rss+xml', aurl('feed/girl'), null, array('title'=>app()->name . ' » 挖女神 Feed'));
        $this->pageTitle = '挖女神 - 最新最全的女明星写真、清纯校花、美女模特、正妹性感自拍';
        $this->setDescription($this->pageTitle);
        $this->setKeywords('阳光正妹,清纯学生,网友自拍,香港模特,台湾正妹,美女自拍,淘女郎,微女郎,美女写真,美女私房照,校花,气质美女,清纯美女,性感车模,比基尼,足球宝贝');
        $this->channel = CHANNEL_GIRL;
        
        $list_view = 'line_list';
        if (($s == POST_LIST_STYLE_GRID)) {
            $list_view = 'grid_list';
            $count = param('grid_post_count_page');
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
    
    public function actionVideo()
    {
        cs()->registerLinkTag('alternate', 'application/rss+xml', aurl('feed/video'), null, array('title'=>app()->name . ' » 挖视频 Feed'));
        $this->pageTitle = '挖短片 - 各种有趣的，新奇的，经典的，有意思的精品视频短片';
        $this->setDescription($this->pageTitle);
        $this->setKeywords('搞笑视频短片,cf搞笑视频,lol搞笑视频,搞笑视频,疯狂恶搞,爆笑童趣,雷人囧事,动物奇趣,优酷搞笑视频,娱乐搞笑视频,土豆搞笑视频,激动搞笑视频');
        
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
        
        if ($pages->getCurrentPage() < $_GET[$pages->pageVar]-1)
            return array();
    
        $models = Post::model()->findAll($criteria);
    
        $data  = array(
            'models' => $models,
            'pages' => $pages,
        );
        
        return $data;
    }

}

