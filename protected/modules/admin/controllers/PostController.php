<?php
class PostController extends AdminController
{
    public function actionVerify()
    {
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(array('state'=>Post::STATE_DISABLED));
        $data = self::fetchPostList($criteria, true, true);

        $this->render('verify', $data);
    }
    
    public function actionToday()
    {
        $date = getdate();
        $timestamp = mktime(0, 0, 0, $date['mon'], $date['mday'], $date['year']);
        $criteria = new CDbCriteria();
        $criteria->addCondition('create_time > :timestamp');
        $criteria->params = array(':timestamp' => $timestamp);
        $data = self::fetchPostList($criteria, true, true);
        
        $this->render('list', $data);
    }
    
    public function actionList()
    {
        $data = self::fetchPostList(null, true, true);
        
        $this->render('list', $data);
    }
    
    public function actionSearch()
    {
        
    }
    
    private static function fetchPostList(CDbCriteria $criteria = null, $pages = true, $sort = false)
    {
        $pageSize = 30;
        $criteria = ($criteria === null) ? new CDbCriteria() : $criteria;
        
        if ($pages) {
            $count = Post::model()->count($criteria);
            $pages = new CPagination($count);
            $pages->setPageSize($pageSize);
            $pages->applyLimit($criteria);
        }
        else
            $criteria->limit = $pageSize;
        
        if ($sort) {
            $sort = new CSort('Post');
            $sort->defaultOrder = 't.id desc';
            $sort->applyOrder($criteria);
        }
        else
            $criteria->order = 't.id desc';
        
        $models = Post::model()->findAll($criteria);
        $data = array(
            'sort' => $sort,
            'pages' => $pages,
            'models' => $models,
        );
        return $data;
    }
}
