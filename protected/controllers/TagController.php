<?php
class TagController extends Controller
{
    public function filters()
    {
        $duration = 24*60*60;
        return array(
//             'switchMobile + posts',
            array(
                'COutputCache + list',
                'duration' => $duration,
            ),
            array(
                'COutputCache + posts',
                'duration' => $duration,
                'varyByParam' => array('name', 'page', 's'),
                'requestTypes' => array('GET'),
                'varyByExpression' => array(user(), 'getIsGuest'),
            ),
            array(
                'COutputCache + posts',
                'duration' => $duration,
                'varyByParam' => array('name', 'page', 's'),
                'requestTypes' => array('POST'),
            ),
        );
    }
    
    public function actionList()
    {
        if (cache()) {
            $cacheKey = 'all_tags';
            $tags = cache()->get($cacheKey);
            if ($tags === false) {
                $tags = Tag::model()->findAll();
                cache()->set($cacheKey, $tags, 24*60*60);
            }
        }
        else
            $tags = Tag::model()->findAll();
        
        $this->pageTitle = '标签列表 - 挖段子';
        $this->setKeywords('各种段子标签');
        $this->setDescription('挖段子网所有标签列表');
        
        $this->channel = 'tag';
        $this->render('list', array(
        	'tags' => (array)$tags,
        ));
    }
    
    public function actionPosts($name, $s = POST_LIST_STYLE_LINE, $page = 1)
    {
        $s = strip_tags(trim($s));
        
        $duration = 120;
        $limit = ($s == POST_LIST_STYLE_WATERFALL) ? param('waterfall_post_count_page') : param('tag_posts_count_page');
        
        $name = urldecode($name);
        
        $tagID = app()->getDb()->createCommand()
            ->select('id')
            ->from(TABLE_TAG)
            ->where('name = :tagname', array(':tagname' => $name))
            ->queryScalar();
        
        if ($tagID === false)
            throw new CHttpException(403, "当前还没有与{$name}标签有关的段子");
        
        $count = app()->getDb()->cache($duration)->createCommand()
            ->select('count(*)')
            ->from(TABLE_POST_TAG)
            ->where('tag_id = :tagid', array(':tagid' => $tagID))
            ->queryScalar();
        
        $pages = new CPagination($count);
        $pages->setPageSize($limit);
        $offset = ($pages->currentPage - 1) * $limit;
        $postIDs = app()->getDb()->createCommand()
            ->select('post_id')
            ->from(TABLE_POST_TAG)
            ->where('tag_id = :tagid', array(':tagid' => $tagID))
            ->order('post_id desc')
            ->limit($limit)
            ->offset($offset)
            ->queryColumn();
        
        $criteria = new CDbCriteria();
        $criteria->addInCondition('id', $postIDs);
        // 只有vip才可以查看GIRL频道
        if (!user()->getIsVip())
            $criteria->addCondition('t.channel_id != ' . CHANNEL_GIRL);
        
        $models = Post::model()->findAll($criteria);
        
        if ($pages->currentPage > 1)
            $mobileUrl = aurl('mobile/'. $this->id . '/' . $this->action->id, array('page'=>$pages->currentPage));
        else
            $mobileUrl = aurl('mobile/'. $this->id . '/' . $this->action->id);
        cs()->registerMetaTag('format=html5;url=' . $mobileUrl, null, 'mobile-agent');
        
        $this->pageTitle = "与{$name}相关的笑话、冷图、视频 - 挖段子";
        $this->setKeywords("{$name}段子,{$name}笑话,{$name}糗事,{$name}语录,{$name}漫画,{$name}视频");
        $this->setDescription("与{$name}有关的相关段子、笑话、冷笑话、糗事、经典语录、冷图、美女写真、视频");
        
        $this->channel = 'tag';
        $view = ($s == POST_LIST_STYLE_WATERFALL) ? '/post/mixed_list' : 'posts';
        $this->render($view, array(
        	'models' => $models,
            'pages' => $pages,
            'fallTitle' => h($name),
        ));
    }
}


