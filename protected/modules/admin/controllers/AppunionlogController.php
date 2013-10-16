<?php

class AppunionlogController extends AdminController
{
    public function actionList()
    {
        $criteria = new CDbCriteria();
        $criteria->limit = param('adminAppUnionLogCountOfPage');
         
        $sort  = new CSort('AdminAppUnionLog');
        $sort->defaultOrder = 't.id desc';
        $sort->applyOrder($criteria);
         
        $count = AdminAppUnionLog::model()->count($criteria);
        $pages = new CPagination($count);
        $pages->setPageSize($criteria->limit);
        $pages->applyLimit($criteria);
         
        $models = AdminAppUnionLog::model()->findAll($criteria);
         
        $this->adminTitle = '应用联盟广告点击记录';
        $this->channel = 'app_union_log';
        $this->render('list', array(
            'models' => $models,
            'sort' => $sort,
            'pages' => $pages,
        ));
    }
}
