<?php
class SitemapController extends Controller
{
    public function init()
    {
        parent::init();
        header('Content-Type:application/xml; charset=' . app()->charset);
    }
    
    public function actionIndex()
    {
        $this->renderPartial('index');
    }
    
    public function actionArchives($date)
    {
        $this->fetchDatePosts($date);
    }
    
    public function actionChannels()
    {
        $this->renderPartial('channels');
    }
    
    public function actionJoke()
    {
        $this->fetchChannelPosts(CHANNEL_DUANZI);
    }
    
    public function actionLengtu()
    {
        $this->fetchChannelPosts(CHANNEL_LENGTU);
    }
    
    public function actionGirl()
    {
        $this->fetchChannelPosts(CHANNEL_GIRL);
    }
    
    public function actionVideo()
    {
        $this->fetchChannelPosts(CHANNEL_VIDEO);
    }
    
    public function actionGhost()
    {
        $this->fetchChannelPosts(CHANNEL_GHOSTSTORY);
    }
    
    private function fetchChannelPosts($channel = null, $duration = 600)
    {
        $conditions = 'state = :enabled';
        $params = array(':enabled' => POST_STATE_ENABLED);
        if ($channel !== null) {
            $conditions = array('and', 'channel_id = :channelID', $conditions);
            $params[':channelID'] = (int)$channel;
        }
        $cmd = app()->getDb()->cache($duration)->createCommand()
            ->select('id')
            ->from(TABLE_POST)
            ->where($conditions, $params)
            ->order('id desc')
            ->limit(10000);
        $posts = $cmd->queryAll();
        $this->renderPartial('posts', array(
            'posts' => $posts
        ));
        app()->end();
        exit(0);
    }
    
    private function fetchDatePosts($date = null, $duration = 600)
    {
        $conditions = 'state = :enabled';
        $params = array(':enabled' => POST_STATE_ENABLED);
        if ($channel !== null) {
            $conditions = array('and', 'channel_id = :channelID', $conditions);
            $params[':channelID'] = (int)$channel;
        }
        $cmd = app()->getDb()->cache($duration)->createCommand()
            ->select('id')
            ->from(TABLE_POST)
            ->where($conditions, $params)
            ->order('id desc')
            ->limit(10000);
        $posts = $cmd->queryAll();
        $this->renderPartial('posts', array(
            'posts' => $posts
        ));
        app()->end();
        exit(0);
    }
}
