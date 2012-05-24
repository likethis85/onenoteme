<?php
class ChannelController extends Controller
{
    public function actionDuanzi()
    {
        $this->channel = CHANNEL_DUANZI;
        $data = $this->fetchChannelPosts(CHANNEL_DUANZI);
        if (request()->getIsAjaxRequest())
            $this->renderPartial('/post/mixed_list', $data);
        else
            $this->render('/post/mixed_list', $data);
    }
    
    public function actionLengtu()
    {
        $this->channel = CHANNEL_LENGTU;
        $data = $this->fetchChannelPosts(CHANNEL_LENGTU);
        if (request()->getIsAjaxRequest())
            $this->renderPartial('/post/mixed_list', $data);
        else
            $this->render('/post/mixed_list', $data);
    }
    
    public function actionGirl()
    {
        $this->channel = CHANNEL_GIRL;
        $data = $this->fetchChannelPosts(CHANNEL_GIRL);
        if (request()->getIsAjaxRequest())
            $this->renderPartial('/post/mixed_list', $data);
        else
            $this->render('/post/mixed_list', $data);
    }
    
    private function fetchChannelPosts($channelid)
    {
        $duration = 120;
        
        $channelid = (int)$channelid;
        $limit = param('postCountOfPage');
        
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(array('channel_id'=>$channelid, 'state'=>POST_STATE_ENABLED));
        $criteria->order = 'create_time desc, id desc';
        $criteria->limit = $limit;
        
        $count = Post::model()->cache($duration)->count($criteria);
        $pages = new CPagination($count);
        $pages->setPageSize($limit);
        $pages->applyLimit($criteria);
    
        $models = Post::model()->cache($duration)->findAll($criteria);
    
        $channels = param('channels');
        $channel = $channels[$channelid];
        $this->pageTitle = $channel;
        $this->setDescription($channel . '频道, 挖段子分类和每个分类的笑话列表。');
    
        $data  = array(
            'models' => $models,
            'pages' => $pages,
        );
        
        return $data;
    }
}