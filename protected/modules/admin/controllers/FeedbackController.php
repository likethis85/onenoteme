<?php
class FeedbackController extends AdminController
{
    public function actionList($page = 1)
    {
        $criteria = new CDbCriteria();
        
        $pages = new CPagination();
        $pages->setPageSize(20);
        $pages->applyLimit($criteria);
        
        $sort = new CSort('AdminFeedback');
        $sort->defaultOrder = 't.create_time desc';
        $sort->applyOrder($criteria);
        
        $models = AdminFeedback::model()->findAll($criteria);
        
        $this->channel = 'feedback';
        $this->adminTitle = '用户留言';
        $this->render('list', array(
            'pages' => $pages,
            'sort' => $sort,
            'models' => $models,
        ));
    }
    
    public function actionDelete($id, $callback)
    {
        $id = (int)$id;
        $model = AdminFeedback::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(500);
         
        $result = $model->delete();
        $data = array(
            'errno' => $result ? CD_NO : CD_YES,
        );
        echo $callback . '(' . CJSON::encode($data) . ')';
        exit(0);
    }
    
    /**
     * 批量删除留言
     * @param array $ids 留言ID数组
     * @param string $callback jsonp回调函数，自动赋值
     */
    public function actionBatchDelete($callback)
    {
        $ids = (array)request()->getPost('ids');
        $successIds = $failedIds = array();
        foreach ($ids as $id) {
            $model = AdminFeedback::model()->findByPk($id);
            if ($model === null)
                continue;
            	
            $result = $model->delete();
            if ($result)
                $successIds[] = $id;
            else
                $failedIds[] = $id;
        }
        $data = array(
            'success' => $successIds,
            'failed' => $failedIds,
        );
        echo $callback . '(' . CJSON::encode($data) . ')';
        exit(0);
    }
}


