<?php
class TestController extends AdminController
{
    public function init()
    {
        exit('exit');
    }
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
    
    public function actionDeltags()
    {
        $sql = "select DISTINCT tags from cd_post where tags != '' order by id asc";
        $rows = app()->getDb()->createCommand($sql)
            ->from('cd_post')
            ->queryColumn();
        
        $tags = array();
        foreach ($rows as $row) {
            $tags = array_merge($tags, explode(',', $row));
        }
        $tags = array_unique($tags);
        $tags = array_map('trim', $tags);
        
        $models = Tag::model()->findAll();
        $count1 = $count2 = 0;
        foreach ($models as $model) {
            if (!in_array($model->name, $tags)) {
                $model->delete() && $count1++;
            }
            else
                $count2++;
        }
        
        echo 'count1: ' . $count1 . '<br />';
        echo 'count2: ' . $count2 . '<br />';
            
    }
}

