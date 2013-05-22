<?php
class AdcodeController extends AdminController
{
    public function actionList($adid)
    {
        $adid = (int)$adid;
        $advert = AdminAdvert::model()->findByPk($adid);
        if ($advert === null)
            throw new CHttpException(404, '广告位不存在');
        
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(array('ad_id'=>$adid));
        $sort = new CSort('AdminAdcode');
        $sort->defaultOrder = 't.state desc, t.id asc';
        $sort->applyOrder($criteria);
        
        $models = AdminAdcode::model()->findAll($criteria);
        
        $this->render('list', array(
            'models' => $models,
            'sort' => $sort,
            'advert' => $advert,
        ));
    }
    
    
    public function actionCreate($adid = 0, $id = 0)
    {
        $id = (int)$id;
        $adid = (int)$adid;
        if (empty($adid) && empty($id))
            throw new CDException('无效请求');
        
        if ($adid > 0) {
            $advert = AdminAdvert::model()->findByPk($adid);
            if ($advert === null)
                throw new CDException(404, '广告位不存在');
        }
        
        if ($id > 0) {
            $model = AdminAdcode::model()->findByPk($id);
            $this->adminTitle = '编辑广告';
        }
        else {
            $model = new AdminAdcode();
            $model->ad_id = $adid;
            $this->adminTitle = '新建广告';
        }
        
        if (request()->getIsPostRequest() && isset($_POST['AdminAdcode'])) {
            $model->attributes = $_POST['AdminAdcode'];
            if ($model->save()) {
                user()->setFlash('save_adcode_result', '保存广告成功');
                $model->advert->clearCache();
                $this->redirect(url('admin/adcode/list', array('adid'=>$model->ad_id)));
            }
        }
        
        $this->render('create', array('model'=>$model));
    }
    
    public function actionSetState($id, $callback)
    {
        $id = (int)$id;
        $model = AdminAdcode::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(500);
    
        $model->state = ($model->state == ADCODE_STATE_ENABLED) ? ADCODE_STATE_DISABLED : ADCODE_STATE_ENABLED;
        $model->save(true, array('state'));
        if ($model->hasErrors())
            throw new CHttpException(500, var_export($model->getErrors(), true));
        else {
            $model->advert->clearCache();
            $data = array(
                'errno' => CD_NO,
                'label' => $model->state == ADCODE_STATE_ENABLED ? '启用' : '禁用',
            );
            CDBase::jsonp($callback, $data);
        }
    }
    
    public function actionSetDelete($id, $callback)
    {
        $id = (int)$id;
        $model = AdminAdcode::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(500);
    
        if ($model->delete()) {
            $model->advert->clearCache();
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