<?php
class TagController extends Controller
{
    public function filters()
    {
        $duration = 24*60*60;
        return array(
            array(
                'COutputCache + list',
                'duration' => $duration,
            ),
            array(
                'COutputCache + posts',
                'duration' => $duration,
                'varyByParam' => array('name', 'page', 's'),
                'requestTypes' => array('GET'),
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
        $cacheKey = 'all_tags';
        $tags = app()->getCache()->get($cacheKey);
        if ($tags === false) {
            $tags = Tag::model()->findAll();
            app()->getCache()->set($cacheKey, $tags, 24*60*60);
        }
        
        $this->pageTitle = '各种段子标签 - 挖段子';
        $this->setKeywords('各种段子标签');
        $this->setDescription('各种段子标签');
        
        $this->channel = 'tag';
        $this->render('list', array(
        	'tags' => (array)$tags,
        ));
    }
    
    public function actionArchives($name, $s = POST_LIST_STYLE_LINE)
    {
        $this->redirect(url('tag/posts', array('name'=>$name, 's'=>$s)));
    }
    
    public function actionPosts($name, $s = POST_LIST_STYLE_LINE)
    {
        $s = strip_tags(trim($s));
        
        $duration = 120;
        $limit = ($s == POST_LIST_STYLE_WATERFALL) ? param('waterfall_post_count_page') : param('line_post_count_page');
        
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

        $models = Post::model()->findAll($criteria);
        
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