<?php
class ChannelController extends Controller
{
    
    public function actionDuanzi()
    {
        $this->pageTitle = '挖笑话 - 最冷笑话精选，每天分享笑话N枚，你的贴身开心果';
        $this->setDescription($this->pageTitle);
        $this->setKeywords('挖笑话,糗事,内涵笑话,爆笑笑话,幽默笑话,笑话大全,爆笑短信,xiaohua,冷笑话,短信笑话,小笑话,笑话短信,经典笑话,冷笑话大全,短笑话,搞笑短信,笑话大全乐翻天,搞笑笑话,疯狂恶搞,爆笑童趣,雷人囧事');
        
        $this->channel = CHANNEL_DUANZI;
        $data = $this->fetchChannelPosts(CHANNEL_DUANZI);
        if (request()->getIsAjaxRequest())
            $this->renderPartial('/post/mixed_list', $data);
        else
            $this->render('/post/mixed_list', $data);
    }
    
    public function actionLengtu()
    {
        $this->pageTitle = '挖趣图 - 最搞笑的，最好玩的，最内涵的图片精选';
        $this->setDescription($this->pageTitle);
        $this->setKeywords('挖趣图,搞笑图片,内涵图,邪恶图片,色色图,暴走漫画,微漫画,4格漫画,8格漫画,搞笑漫画,内涵漫画,邪恶漫画,疯狂恶搞,爆笑童趣,雷人囧事,动画萌图,狗狗萌图,猫咪萌图,喵星人萌图,汪星人萌图');
        
        $this->channel = CHANNEL_LENGTU;
        $data = $this->fetchChannelPosts(CHANNEL_LENGTU);
        if (request()->getIsAjaxRequest())
            $this->renderPartial('/post/mixed_list', $data);
        else
            $this->render('/post/mixed_list', $data);
    }
    
    public function actionGirl()
    {
        $this->pageTitle = '挖福利 - 最新最全的女明星写真、清纯校花、美女模特、正妹性感自拍';
        $this->setDescription($this->pageTitle);
        $this->setKeywords('阳光正妹,清纯学生,网友自拍,香港模特,台湾正妹,美女自拍,淘女郎,微女郎,美女写真,美女私房照,校花,气质美女,清纯美女,性感车模,比基尼,足球宝贝');
        
        $this->channel = CHANNEL_GIRL;
        $data = $this->fetchChannelPosts(CHANNEL_GIRL);
        if (request()->getIsAjaxRequest())
            $this->renderPartial('/post/mixed_list', $data);
        else
            $this->render('/post/mixed_list', $data);
    }
    
    public function actionVideo()
    {
        $this->pageTitle = '挖短片 - 各种有趣的，新奇的，经典的，有意思的精品视频短片';
        $this->setDescription($this->pageTitle);
        $this->setKeywords('搞笑视频短片,cf搞笑视频,lol搞笑视频,搞笑视频,疯狂恶搞,爆笑童趣,雷人囧事,动物奇趣,优酷搞笑视频,娱乐搞笑视频,土豆搞笑视频,激动搞笑视频');
        
        $this->channel = CHANNEL_VIDEO;
        $data = $this->fetchChannelPosts(CHANNEL_VIDEO, param('videoCountOfPage'));
        $this->render('video_list', $data);
    }
    
    private function fetchChannelPosts($channelid, $limit = 0)
    {
        $duration = 120;
        
        $channelid = (int)$channelid;
        $limit = (int)$limit;
        if ($limit === 0)
            $limit = param('postCountOfPage');
        
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
    
        $models = Post::model()->cache($duration)->findAll($criteria);
    
        $data  = array(
            'models' => $models,
            'pages' => $pages,
        );
        
        return $data;
    }

    public function actionConvert()
    {
        $movies = MovieSets::model()->findAll();
        foreach ($movies as $m) {
            $post = new Post();
            $post->channel_id = CHANNEL_VIDEO;
            $post->title = $m->name;
            $post->content = $m->story ? $m->story : $m->name;
            $post->original_pic = $m->url;
            $post->create_time = $m->create_time;
            $post->view_nums = mt_rand(100, 1000);
            $post->up_score = mt_rand(100, 200);
            $post->down_score = mt_rand(0, 10);
            $post->state = POST_STATE_ENABLED;
            if ($post->save()) {
                echo 'save success<br />';
            }
            else
                echo var_export($post->getErrors()) . '<br />';
        }
    }
}