<?php
class UserController extends Controller
{
    public function actionVerify()
    {
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(array('state'=>User::STATE_DISABLED));
        $data = self::fetchUserList($criteria, true, true);
        
        $this->render('list', $data);
    }
    
    public function actionSearch()
    {
        
    }

    private static function fetchUserList(CDbCriteria $criteria = null, $pages = true, $sort = false)
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
            $sort = new CSort('User');
            $sort->defaultOrder = 'id';
            $sort->applyOrder($criteria);
        }
        else
            $criteria->order = 'id desc';
    
        $models = User::model()->findAll($criteria);
        $data = array(
                'sort' => $sort,
                'pages' => $pages,
                'models' => $models,
        );
        return $data;
    }
}
