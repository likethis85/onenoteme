<?php
class WapController extends Controller
{
    const COUNT_OF_PAGE = 10;
    
    public function init()
    {
        $this->layout = 'wap';
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
        );
    }
    
    public function actionIndex()
    {
        $limit = self::COUNT_OF_PAGE;
        
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(array('state' => POST_STATE_ENABLED));
        $criteria->addCondition('channel_id = '. CHANNEL_DUANZI);
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
}