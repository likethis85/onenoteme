<?php
class AppadslotController extends AdminController
{
    public function filters()
    {
        return array(
        'ajaxOnly + setState, setDelete',
        'postOnly + setState, setDelete',
        );
    }
    
    public function actionList()
    {
        $criteria = new CDbCriteria();
        $sort = new CSort('AdminAppAdslot');
        $sort->defaultOrder = 't.slot asc, t.id asc';
        $sort->applyOrder($criteria);
        
        $models = AdminAppAdslot::model()->findAll($criteria);
        
        $this->render('list', array(
            'models' => $models,
            'sort' => $sort,
        ));
    }
    
    public function actionCreate($id = 0)
    {
        $id = (int)$id;
        if ($id > 0) {
            $model = AdminAppAdslot::model()->findByPk($id);
            $this->adminTitle = '编辑应用广告位';
        }
        else {
            $model = new AdminAppAdslot();
            $this->adminTitle = '新建应用广告位';
        }
        
        if (request()->getIsPostRequest() && isset($_POST['AdminAppAdslot'])) {
            $model->attributes = $_POST['AdminAppAdslot'];
            if ($model->save()) {
                user()->setFlash('save_app_adslot_result', '保存广告位成功：' . $model->name);
                $model->clearCache();
                $this->redirect(request()->getUrl());
            }
        }
        
        $this->render('create', array('model'=>$model));
    }
    
    public function actionClearAllCache()
    {
        $result = AdminAppAdslot::clearAllCache();
        if ($result) {
            user()->setFlash('clear_app_adslot_all_cache_result', '清除所有广告代码缓存完成');
            $this->redirect(url('admin/appadslot/list'));
        }
    }
    

    public function actionSetState($id, $callback)
    {
        $id = (int)$id;
        $model = AdminAppAdslot::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(500);
         
        $model->state = ($model->state == APP_ADSLOT_STATE_ENABLED) ? APP_ADSLOT_STATE_DISABLED : APP_ADSLOT_STATE_ENABLED;
        $model->save(true, array('state'));
        if ($model->hasErrors())
            throw new CHttpException(500, var_export($model->getErrors(), true));
        else {
            $model->clearCache();
            $data = array(
                'errno' => CD_NO,
                'label' => $model->state == APP_ADSLOT_STATE_ENABLED ? '启用' : '禁用',
            );
            CDBase::jsonp($callback, $data);
        }
    }
    
    public function actionSetDelete($id, $callback)
    {
        $id = (int)$id;
        $model = AdminAppAdslot::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(500);
    
        if ($model->delete()) {
            $model->clearCache();
            $data = array(
                'errno' => CD_NO,
                'label' => '删除成功',
            );
            CDBase::jsonp($callback, $data);
        }
        else
            throw new CHttpException(500, var_export($model->getErrors(), true));
    }
}


