<?php

class UserController extends AdminController
{
    public function filters()
    {
        return array(
            'postOnly + setVerify',
            'ajaxOnly + setVerify',
        );
    }
    
	public function actionIndex()
	{
		$this->render('index');
	}
	
	public function actionVerify()
	{
	    $criteria = new CDbCriteria();
	    $criteria->addColumnCondition(array('state'=>USER_STATE_UNVERIFY));
	    $data = AdminUser::fetchList($criteria);
	     
	    $this->adminTitle = '审核用户';
	    $this->render('list', $data);
	}
	
	public function actionForbidden()
	{
	    $criteria = new CDbCriteria();
	    $criteria->addColumnCondition(array('state'=>USER_STATE_FORBIDDEN));
	    $data = AdminUser::fetchList($criteria);
	     
	    $this->adminTitle = '禁用用户';
	    $this->render('list', $data);
	}
	
	public function actionToday()
	{
	    $time = $_SERVER['REQUEST_TIME'] - 24*60*60;
	    $criteria = new CDbCriteria();
	    $criteria->addCondition('create_time > ' . $time);
	    $data = AdminUser::fetchList($criteria);
	    
	    $this->adminTitle = '今日注册用户';
	    
	    $this->render('list', $data);
	}
	
	
	public function actionList()
	{
	    $criteria = new CDbCriteria();
	    $data = AdminUser::fetchList($criteria);
	    
	    $this->adminTitle = '用户列表';
	    
	    $this->render('list', $data);
	}
	
	public function actionCreate($id = 0)
	{
	    $id = (int)$id;
	    if ($id === 0) {
	        $model = new AdminUser();
	        $this->adminTitle = '新建用户';
	    }
	    else {
	        $model = AdminUser::model()->findByPk($id);
	        $this->adminTitle = '编辑用户 - ' . $model->username;
	    }
	    
	    if (request()->getIsPostRequest() && isset($_POST['AdminUser'])) {
	        $model->attributes = $_POST['AdminUser'];
	        
	        $attributes = $model->getAttributes();
	        $model->state = (bool)$model->state ? USER_STATE_ENABLED : USER_STATE_FORBIDDEN;
	        if ($model->getIsNewRecord()) {
    	        $model->encryptPassword();
	        }
	        else {
                $attributes['password'] = null;
	            unset($attributes['password']);
            }
	        
	        if ($model->save()) {
	            user()->setFlash('user_create_result', $model->username . '&nbsp;保存成功');
	            $this->redirect(request()->getUrl());
	        }
	    }
	    
	    $view = $model->getIsNewRecord() ? 'create' : 'edit';
	    $this->render($view, array(
	        'model' => $model,
	    ));
	}
	
	public function actionSearch()
	{
	    $form = new UserSearchForm();
	    
	    if (isset($_GET['UserSearchForm'])) {
	        $form->attributes = $_GET['UserSearchForm'];
	        if ($form->validate())
	            $data = $form->search();
	        user()->setFlash('table_caption', '用户搜索结果');
	    }
	    
        $this->render('search', array('form'=>$form, 'data'=>$data));
	}

	public function actionSetVerify($id, $callback)
	{
	    $id = (int)$id;
	    $model = AdminUser::model()->findByPk($id);
	    if ($model === null)
	        throw new CHttpException(500);
	     
	    $model->state = ($model->state == USER_STATE_ENABLED) ? USER_STATE_FORBIDDEN : USER_STATE_ENABLED;
	    $model->save(true, array('state'));
	    if ($model->hasErrors())
	        throw new CHttpException(500);
	    else {
	        if ($model->state == USER_STATE_ENABLED)
	            $text = '启用';
	        elseif ($model->state == USER_STATE_FORBIDDEN)
    	        $text = '禁用';

	        $data = array(
	            'errno' => CD_NO,
	            'label' => t($text, 'admin')
	        );
	        CDBase::jsonp($callback, $data);
	        exit(0);
	    }
	}

	public function actionResetPassword($id)
	{
	    $id = (int)$id;
	    if ($id <= 0)
	        throw new CHttpException(500);
	    
	    $criteria = new CDbCriteria();
	    $criteria->select = array('id', 'username', 'screen_name', 'password');
	    $user = AdminUser::model()->findByPk($id, $criteria);
	    if ($user === null)
	        throw new CHttpException(404, '用户不存在');
	    
	    if (request()->getIsPostRequest() && isset($_POST['AdminUser'])) {
	        $user->attributes = $_POST['AdminUser'];
	        $user->encryptPassword();
	        if ($user->save(true, array('password'))) {
	            user()->setFlash('user_create_result', "修改&nbsp;{$user->username}&nbsp;密码成功");
	            $this->redirect(request()->getUrl());
	        }
	    }
	    
	    $user->password = '';
	    $this->adminTitle = '重设用户密码 - ' . $user->username;
	    $this->render('resetpwd', array('model'=>$user));
	}

    public function actionCurrent()
    {
        $this->forward('info');
    }
    
    public function actionInfo($id = 0)
    {
        $id = (int)$id;
        $userID = ($id > 0) ? $id : (int)user()->id;
        $model = AdminUser::model()->findByPk($userID);
        if ($model === null)
            throw new CHttpException(404, '用户不存在');
        
        $this->adminTitle = $model->username;
        $this->render('info', array('model' => $model));
    }


    /**
     * 批量审核用户
     * @param array $ids 用户ID数组
     * @param string $callback jsonp回调函数，自动赋值
     */
    public function actionMultiVerify($callback)
    {
        $ids = (array)request()->getPost('ids');
    
        $successIds = $failedIds = array();
        $attributes = array(
            'state' => USER_STATE_ENABLED,
        );
        foreach ($ids as $id) {
            $result = AdminUser::model()->updateByPk($id, $attributes);
            if ($result)
                $successIds[] = $id;
            else
                $failedIds[] = $id;
        }
        $data = array(
            'success' => $successIds,
            'failed' => $failedIds,
            'label' => '启用',
        );
        CDBase::jsonp($callback, $data);
    }
    
    /**
     * 批量禁用用户
     * @param array $ids 用户ID数组
     * @param string $callback jsonp回调函数，自动赋值
     */
    public function actionMultiForbidden($callback)
    {
        $ids = (array)request()->getPost('ids');
    
        $successIds = $failedIds = array();
        $attributes = array(
            'state' => USER_STATE_FORBIDDEN,
        );
        foreach ($ids as $id) {
            $result = AdminUser::model()->updateByPk($id, $attributes);
            if ($result)
                $successIds[] = $id;
            else
                $failedIds[] = $id;
        }
        $data = array(
            'success' => $successIds,
            'failed' => $failedIds,
            'label' => '禁用',
        );
        CDBase::jsonp($callback, $data);
    }
}