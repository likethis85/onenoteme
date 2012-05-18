<?php
class ChannelController extends Controller
{
    public function actionDuanzi()
    {
        $this->channel = 'duanzi';
        $data = $this->fetchChannelPosts(CHANNEL_DUANZI);
        $this->render('text_posts', $data);
    }
    
    public function actionLengtu()
    {
        $this->channel = 'lengtu';
        $data = $this->fetchChannelPosts(CHANNEL_LENGTU);
        $this->render('pic_posts', $data);
    }
    
    public function actionGirl()
    {
        $this->channel = 'girl';
        $data = $this->fetchChannelPosts(CHANNEL_GIRL);
        $this->render('pic_posts', $data);
    }
    
    public function actionVideo()
    {
        $this->channel = 'video';
        $data = $this->fetchChannelPosts(CHANNEL_VIDEO);
        $this->render('video_posts', $data);
    }
    
    private function fetchChannelPosts($channelid)
    {
        $duration = 120;
        
        $channelid = (int)$channelid;
        $limit = param('postCountOfPage');
        $where = 'state = :state and channel_id = :channel_id';
        $params = array(':state'=>POST_STATE_ENABLED, ':channel_id'=>$channelid);
        $cmd = app()->db->createCommand()
            ->order('create_time desc, id desc')
            ->limit($limit)
            ->where($where, $params);
    
        $count = DPost::model()->count($where, $params);
        $pages = new CPagination($count);
        $pages->setPageSize($limit);
    
        $offset = $pages->getCurrentPage() * $limit;
        $cmd->offset($offset);
        $models = DPost::model()->cache($duration)->findAll($cmd);
    
        global $channels;
        
        $channel = $channels[$channelid];
        $this->pageTitle = $channel;
        $this->setDescription($channel . '频道,挖段子分类和每个分类的笑话列表。');
    
        $data  = array(
            'models' => $models,
            'pages' => $pages,
        );
        
        return $data;
    }
}