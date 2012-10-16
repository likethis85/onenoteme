<?php
class ChannelController extends Controller
{
    public function fi2lters()
    {
        $duration = 300;
        return array(
            array(
                'COutputCache + duanzi, lengtu, girl, video',
                'duration' => $duration,
                'varyBySession' => true,
                'varyByParam' => array('page', 's'),
                'requestTypes' => array('POST'),
            ),
            array(
                'COutputCache + duanzi, lengtu, girl, video',
                'duration' => $duration,
                'varyBySession' => true,
                'varyByParam' => array('page', 's'),
                'requestTypes' => array('GET'),
            ),
        );
    }
    
    public function actionDuanzi($s = POST_LIST_STYLE_GRID)
    {
        cs()->registerLinkTag('alternate', 'application/rss+xml', aurl('feed/channel', array('cid'=>CHANNEL_DUANZI)), null, array('title'=>app()->name . ' » 挖笑话 Feed'));
        $this->pageTitle = '挖笑话 - 最冷笑话精选，每天分享笑话N枚，你的贴身开心果';
        $this->setDescription($this->pageTitle);
        $this->setKeywords('挖笑话,糗事,内涵笑话,爆笑笑话,幽默笑话,笑话大全,爆笑短信,xiaohua,冷笑话,短信笑话,小笑话,笑话短信,经典笑话,冷笑话大全,短笑话,搞笑短信,笑话大全乐翻天,搞笑笑话,疯狂恶搞,爆笑童趣,雷人囧事');
        
        $this->channel = CHANNEL_DUANZI;
        $data = $this->fetchChannelPosts(CHANNEL_DUANZI, param('duanzi_count_page'));
        $view = ($s == POST_LIST_STYLE_WATERFALL) ? '/post/mixed_list' : 'text_list';
        if (request()->getIsAjaxRequest())
            $this->renderPartial($view, $data);
        else
            $this->render($view, $data);
    }
    
    public function actionLengtu($s = POST_LIST_STYLE_GRID)
    {
        cs()->registerLinkTag('alternate', 'application/rss+xml', aurl('feed/channel', array('cid'=>CHANNEL_LENGTU)), null, array('title'=>app()->name . ' » 挖冷图 Feed'));
        $this->pageTitle = '挖趣图 - 最搞笑的，最好玩的，最内涵的图片精选';
        $this->setDescription($this->pageTitle);
        $this->setKeywords('挖趣图,搞笑图片,内涵图,邪恶图片,色色图,暴走漫画,微漫画,4格漫画,8格漫画,搞笑漫画,内涵漫画,邪恶漫画,疯狂恶搞,爆笑童趣,雷人囧事,动画萌图,狗狗萌图,猫咪萌图,喵星人萌图,汪星人萌图');
        
        $this->channel = CHANNEL_LENGTU;
        $data = $this->fetchChannelPosts(CHANNEL_LENGTU, param('lengtu_count_page'));
        $view = ($s == POST_LIST_STYLE_WATERFALL) ? '/post/mixed_list' : 'lengtu_list';
        if (request()->getIsAjaxRequest())
            $this->renderPartial($view, $data);
        else
            $this->render($view, $data);
    }
    
    public function actionGirl($s = POST_LIST_STYLE_GRID)
    {
        cs()->registerLinkTag('alternate', 'application/rss+xml', aurl('feed/channel', array('cid'=>CHANNEL_GIRL)), null, array('title'=>app()->name . ' » 挖福利 Feed'));
        $this->pageTitle = '挖福利 - 最新最全的女明星写真、清纯校花、美女模特、正妹性感自拍';
        $this->setDescription($this->pageTitle);
        $this->setKeywords('阳光正妹,清纯学生,网友自拍,香港模特,台湾正妹,美女自拍,淘女郎,微女郎,美女写真,美女私房照,校花,气质美女,清纯美女,性感车模,比基尼,足球宝贝');
        
        $this->channel = CHANNEL_GIRL;
        $data = $this->fetchChannelPosts(CHANNEL_GIRL, param('girl_count_page'));
        $view = ($s == POST_LIST_STYLE_WATERFALL) ? '/post/mixed_list' : 'girl_list';
        if (request()->getIsAjaxRequest())
            $this->renderPartial($view, $data);
        else
            $this->render($view, $data);
    }
    
    public function actionVideo()
    {
        cs()->registerLinkTag('alternate', 'application/rss+xml', aurl('feed/channel', array('cid'=>CHANNEL_VIDEO)), null, array('title'=>app()->name . ' » 挖视频 Feed'));
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
            $limit = param('grid_post_count_page');
        
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(array('channel_id'=>$channelid, 'state'=>POST_STATE_ENABLED));
        $criteria->order = 'create_time desc, id desc';
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

