<?php
class MobileController extends Controller
{
    const COUNT_OF_PAGE = 10;
    
    public function init()
    {
        $this->layout = 'mobile';
    }
    
    public function filters()
    {
        $duration = 300;
        return array(
            array(
                'COutputCache + index',
                'duration' => $duration,
                'varyByParam' => array('page'),
            ),
            array(
                'COutputCache + tag',
                'duration' => $duration,
                'varyByParam' => array('name'),
            ),
            array(
                'COutputCache + channel',
                'duration' => $duration,
                'varyByParam' => array('id'),
            )
        );
    }
    
    public function actionIndex()
    {
        $limit = self::COUNT_OF_PAGE;
        
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(array('state' => POST_STATE_ENABLED));
        $criteria->addCondition('channel_id != '. CHANNEL_VIDEO);
        $criteria->order = 'create_time desc, id desc';
        $criteria->limit = $limit;
        
        $count = Post::model()->count($criteria);
        $pages = new CPagination($count);
        $pages->setPageSize($limit);
        $pages->applyLimit($criteria);
        
        $models = Post::model()->findAll($criteria);
        
        $this->pageTitle = '挖段子 - 笑死人不尝命';
        $this->setKeywords('笑话大全,黄段子,爆笑短信,最新段子,最全的段子,经典语录,糗事百科,秘密,笑话段子,经典笑话,笑话大全,搞笑大全,我们爱讲冷笑话,哈哈笑');
        $this->setDescription('最新发布的段子。网罗互联网各种精品段子，各种糗事，各种笑话，各种秘密，各种经典语录，应有尽有。烦了、累了、无聊了，就来挖段子逛一逛。');
        
        $this->channel = 'latest';
        $this->render('index', array(
        	'models' => $models,
            'pages' => $pages,
        ));
    }
    
    public function actionTag($name)
    {
        $duration = 60 * 60 *24;
        $limit = self::COUNT_OF_PAGE;
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
        
        $this->pageTitle = $name . '相关段子 - 挖段子';
        $this->setKeywords("{$name}相关段子,{$name}相关冷笑话,{$name}相关糗事,{$name}相关语录");
        $this->setDescription("与{$name}有关的相关段子、笑话、冷笑话、糗事、经典语录、视频");
        
        $this->channel = 'tag';
        $this->render('index', array(
        	'models' => $models,
            'pages' => $pages,
            'listTitle' => "与{$name}相关的笑话、冷图、视频。。。",
        ));
    }
    
    public function actionChannel($id)
    {
        $id = (int)$id;
        $limit = self::COUNT_OF_PAGE;
        
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(array('channel_id' => $id, 'state' => POST_STATE_ENABLED));
        $criteria->order = 'create_time desc, id desc';
        $criteria->limit = $limit;
        
        $count = Post::model()->count($criteria);
        $pages = new CPagination($count);
        $pages->setPageSize($limit);
        $pages->applyLimit($criteria);
        
        $models = Post::model()->findAll($criteria);
        
        $this->pageTitle = '挖段子 - 笑死人不尝命';
        $this->setKeywords('笑话大全,黄段子,爆笑短信,最新段子,最全的段子,经典语录,糗事百科,秘密,笑话段子,经典笑话,笑话大全,搞笑大全,我们爱讲冷笑话,哈哈笑');
        $this->setDescription('最新发布的段子。网罗互联网各种精品段子，各种糗事，各种笑话，各种秘密，各种经典语录，应有尽有。烦了、累了、无聊了，就来挖段子逛一逛。');
        
        $this->channel = 'latest';
        $this->render('index', array(
            'models' => $models,
            'pages' => $pages,
        ));
    }
    
    public function actionShow($id)
    {
        $id = (int)$id;
        if ($id < 0)
            throw new CHttpException(403, '该段子不存在');
        
        $model = Post::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(403, '该段子不存在');
        
        $this->render('show', array('model'=>$model));
    }

    public function actionAboutme()
    {
        $this->renderPartial('aboutme');
    }

    public function actionNextversion()
    {
        $this->renderPartial('nextversion');
    }
}