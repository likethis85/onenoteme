<?php
class ChannelController extends Controller
{
    public function actionDuanzi()
    {
        $this->channel = 'duanzi';
        $this->fetchChannelPosts(CHANNEL_DUANZI);
    }
    
    public function actionLengtu()
    {
        $this->channel = 'lengtu';
        $this->fetchChannelPosts(CHANNEL_LENGTU);
    }
    
    public function actionGirl()
    {
        $this->channel = 'girl';
        $this->fetchChannelPosts(CHANNEL_GIRL);
    }
    
    public function actionVideo()
    {
        $this->channel = 'video';
        $this->fetchChannelPosts(CHANNEL_VIDEO);
    }
    
    private function fetchChannelPosts($channelid)
    {
        $duration = 120;
        
        $channelid = (int)$channelid;
        $limit = param('postCountOfPage');
        $where = 'state != :state and channel_id = :channel_id';
        $params = array(':state'=>DPost::STATE_DISABLED, ':channel_id'=>$channelid);
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
    
        $cmd = app()->db->createCommand()
        ->order('orderid desc, id asc');
        $categories = DCategory::model()->findAll($cmd);
    
        global $channels;
        
        $channel = $channels[$channelid];
        $this->pageTitle = $channel;
        $this->setKeywords($channel . ',段子分类,' . implode(',', CHtml::listData($categories, 'id', 'name')));
        $this->setDescription($channel . '频道,挖段子分类和每个分类的笑话列表。');
    
        $this->render('posts', array(
            'models' => $models,
            'pages' => $pages,
            'categories' => $categories,
        ));
    }
}