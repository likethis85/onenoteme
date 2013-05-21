<?php
class TestController extends AdminController
{
    public function actionDel($page = 1, $count = 500)
    {
        $criteria = new CDbCriteria();
        $criteria->limit = $count;
        $criteria->order = 'id asc';
        
        $models = Post1::model()->findAll($criteria);
        if (count($models) == 0)
            echo 'no more posts.<br />';
        
        foreach ($models as $model) {
            try {
                if ($model->delete())
                    echo 'del success.<br />';
                else
                    echo 'del failed.<br />';
            }
            catch (Exception $e) {
                echo $e->getMessage() . '<br />';
            }
        }
    }
}