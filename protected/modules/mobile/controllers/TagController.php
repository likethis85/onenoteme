<?php
class TagController extends MobileController
{
    public function filters()
    {
        return array(
            array(
                'COutputCache + posts',
                'duration' => param('mobile_post_list_cache_expire'),
                'varyByParam' => array('name', 'page'),
                'varyByExpression' => array(request(), 'getServerName'),
            ),
        );
    }
    
    public function actionIndex($name, $page = 1)
    {
        $this->redirect(url('mobile/tag/posts', array('name'=>$name, 'page'=>$page)));
    }
    
    public function actionArchives($name, $page = 1)
    {
        $this->forward('tag/posts');
    }
    
    public function actionPosts($name, $page = 1)
    {
        $limit = 10;
        $name = trim(strip_tags(urldecode($name)));
        
        $tagID = app()->getDb()->createCommand()
            ->select('id')
            ->from(TABLE_TAG)
            ->where('name = :tagname', array(':tagname' => $name))
            ->queryScalar();
        
        if ($tagID === false)
            throw new CHttpException(403, "当前还没有与{$name}标签有关的段子");
        
        $count = app()->getDb()->createCommand()
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
        
        $models = MobilePost::model()->findAll($criteria);
        
        $this->pageTitle = "与{$name}相关的笑话、冷图、视频 - 挖段子";
        $this->setKeywords("{$name}段子,{$name}笑话,{$name}糗事,{$name}语录,{$name}漫画,{$name}视频");
        $this->setDescription("与{$name}有关的相关段子、笑话、冷笑话、糗事、经典语录、冷图、美女写真、视频");
        
        $this->channel = 'tag';
        $this->render('/post/list', array(
            'models' => $models,
            'pages' => $pages,
            'listTitle' => '与' . h($name) . '相关的笑话、冷图、视频。。。',
        ));
    }
}

