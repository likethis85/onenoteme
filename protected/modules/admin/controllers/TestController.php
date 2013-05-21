<?php
class TestController extends AdminController
{
    public function actionDel($page = 1, $count = 500)
    {
        $criteria = new CDbCriteria();
        $criteria->limit = $count;
        $criteria->order = 'id asc';
        
        $models = Post1::model()->findAll($criteria);
        if (count($models))
            echo 'no more posts.<br />';
        
        foreach ($models as $model) {
            try {
                $model->delete();
            }
            catch (Exception $e) {
                echo $e->getMessage() . '<br />';
            }
        }
    }
}