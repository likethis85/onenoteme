<?php
class SitemapController extends Controller
{
    public function init()
    {
        parent::init();
        header('Content-Type:application/xml; charset=' . app()->charset);
    }
    
    
    public function filters()
    {
        return array(
            array(
                'COutputCache + index, channels, tags',
                'duration' => 600,
            ),
        );
    }
    
    public function actionIndex()
    {
        $this->renderPartial('index');
    }
    
    public function actionChannels()
    {
        $this->renderPartial('channels');
    }
    
    public function actionTags()
    {
        $duration = 86400;
        $cmd = db()->cache($duration)->createCommand()
            ->from(TABLE_TAG)
            ->where('post_nums > 0')
            ->order('id asc');
            
        $tags = $cmd->queryAll();
        
        $this->renderPartial('tags', array('tags'=>$tags));
        app()->end();
        exit(0);
    }
    
    public function actionJoke()
    {
        $this->fetchChannelPosts(CHANNEL_FUNNY, MEDIA_TYPE_TEXT);
    }
    
    public function actionLengtu()
    {
        $this->fetchChannelPosts(CHANNEL_FUNNY, MEDIA_TYPE_IMAGE);
    }
    
    public function actionFocus()
    {
        $this->fetchChannelPosts(CHANNEL_FOCUS);
    }
        
    private function fetchChannelPosts($channel = null, $mediatype = null, $duration = 3600)
    {
        $conditions = 'state = :enabled';
        $params = array(':enabled' => POST_STATE_ENABLED);
        if ($channel !== null) {
            $conditions = array('and', 'channel_id = :channelID', $conditions);
            $params[':channelID'] = (int)$channel;
        }
        if ($mediatype !== null) {
            $conditions = array('and', 'media_type = :mediatype', $conditions);
            $params[':mediatype'] = (int)$mediatype;
        }
        $cmd = db()->cache($duration)->createCommand()
            ->select('id')
            ->from(TABLE_POST)
            ->where($conditions, $params)
            ->order('id desc')
            ->limit(20000);
        $posts = $cmd->queryAll();
        $this->renderPartial('posts', array(
            'posts' => $posts
        ));
        app()->end();
        exit(0);
    }
    
}
