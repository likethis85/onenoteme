<?php

class PostController extends AdminController
{
    public function filters()
    {
        return array(
            'ajaxOnly + setVerify, quickUpdate, setDelete, setTrash, multiTrash, multiDelete, multiVerify, multiReject, multiRecommend, multiHottest',
            'postOnly + setVerify, quickUpdate, setDelete, setTrash, multiTrash,, multiDelete, multiVerify, multiReject, multiRecommend, multiHottest',
        );
    }
    
    public function actionInfo($id)
    {
        $id = (int)$id;
        $model = AdminPost::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, '段子不存在');
        
        $this->render('info', array('model'=>$model));
    }
    
	public function actionCreate($id = 0)
	{
	    $id = (int)$id;
	    if ($id === 0) {
	        $model = new AdminPost();
	        $model->homeshow = user()->checkAccess('create_post_in_home') ? CD_YES : CD_NO;
	        $model->state = user()->checkAccess('editor') ? POST_STATE_ENABLED : POST_STATE_NOT_VERIFY;
	        $this->adminTitle = '添加文章';
	    }
	    elseif ($id > 0) {
	        $model = AdminPost::model()->findByPk($id);
	        $this->adminTitle = '编辑文章';
	    }
	    else
	        throw new CHttpException(500);
	    
	    if (request()->getIsPostRequest() && isset($_POST['AdminPost'])) {
	        $model->attributes = $_POST['AdminPost'];
	        // 此处如果以后有多种文章模型了，这一句可以去掉。
	        if ($model->getIsNewRecord()) {
	            $model->user_id = user()->id;
	            $model->user_name = user()->name;
    	        $model->post_type = POST_TYPE_POST;
	        }
	        if ($model->save()) {
	            $this->afterPostSave($model);
	            $resultHtml = sprintf('{%s}&nbsp;发表成功，<a href="{%s}" target="_blank">点击查看</a>', $model->title, $model->url);
	            user()->setFlash('save_post_result', $resultHtml);
                $this->redirect(request()->getUrl());
	        }
	    }
	    else {
	        $key = param('sess_post_create_token');
            if (!app()->session->contains($key) || empty(app()->session[$key])) {
                $token = $model->getIsNewRecord() ? uniqid('beta', true) : $model->id;
                app()->session->add($key, $token);
    	    }
            else {
                $token = app()->session[$key];
                $tempPictures = Upload::model()->findAllByAttributes(array('token'=>$token));
            }
	    }
	    
		$this->render('create', array(
		    'model'=>$model,
	        'tempPictures' => $tempPictures,
		));
	}
	
	private function afterPostSave(AdminPost $post)
	{
	    $key = param('sess_post_create_token');
        if (app()->session->contains($key) && $token = app()->session[$key] && !is_numeric($token)) {
            if (!$post->hasErrors()) {
                $attributes = array('post_id'=>$post->id, 'token'=>'');
                AdminUpload::model()->updateAll($attributes, 'token = :token', array(':token'=>$token));
                app()->session->remove($key);
            }
        }
        
        // save remote images to local
        if (param('auto_remote_image_local'))
            $this->imagesLocal($post);
	}
	
	private function imagesLocal(AdminPost $post)
	{
	    set_time_limit(0);
	    $summary = CDFileLocal::fetchAndReplaceMultiWithHtml($post->summary);
	    $content = CDFileLocal::fetchAndReplaceMultiWithHtml($post->content);
	    if ($summary === false and $content === false) return false;
	    
	    $summary === false or $post->summary = $summary;
	    $content === false or $post->content = $content;
	    return $post->save(true, array('summary', 'content'));
	}
	
	public function actionLatest()
	{
	    $criteria = new CDbCriteria();
	    // 不显示回收站的内容
	    $criteria->addCondition('t.state != ' . POST_STATE_TRASH);
	    
	    $title = '最新文章列表';
	    $data = AdminPost::fetchList($criteria);
	    
	    $this->adminTitle = $title;
	    $this->render('list', $data);
	}
	
	public function actionVerify()
	{
	    $criteria = new CDbCriteria();
	    $criteria->addColumnCondition(array('t.state'=>POST_STATE_UNVERIFY));
	    $data = AdminPost::fetchList($criteria);
	    
	    $this->adminTitle = '未审核文章列表';
	    $this->render('list', $data);
	}
	
	public function actionSearch()
	{
	    $form = new PostSearchForm();
	    
	    if (isset($_GET['PostSearchForm'])) {
	        $form->attributes = $_GET['PostSearchForm'];
	        if ($form->validate())
	            $data = $form->search();
	        user()->setFlash('table_caption', '文章搜索结果');
	    }
	    
        $this->render('search', array('form'=>$form, 'data'=>$data));
	}
	
	public function actionHottest()
	{
	    $criteria = new CDbCriteria();
	    $criteria->addColumnCondition(array('hottest'=>CD_YES));
	    $data = AdminPost::fetchList($criteria);
	     
	    $this->render('list', $data);
	}
	
	public function actionRecommend()
	{
	    $criteria = new CDbCriteria();
	    $criteria->addColumnCondition(array('recommend'=>CD_YES));
	    $data = AdminPost::fetchList($criteria);
	     
	    $this->render('list', $data);
	}
	
	public function actionHomeshow()
	{
	    $criteria = new CDbCriteria();
	    $criteria->addColumnCondition(array('homeshow'=>CD_YES));
	    $data = AdminPost::fetchList($criteria);
	     
	    $this->render('list', $data);
	}
	
	public function actionIstop()
	{
	    $criteria = new CDbCriteria();
	    $criteria->addColumnCondition(array('istop'=>CD_YES));
	    $data = AdminPost::fetchList($criteria);
	     
	    $this->render('list', $data);
	}
	
	// @todo 回收站，暂时不用
	public function actionTrash()
	{
	    $criteria = new CDbCriteria();
	    $criteria->addColumnCondition(array('t.state'=>POST_STATE_TRASH));
	    $data = AdminPost::fetchList($criteria);
	     
	    $this->render('list', $data);
	}
	
	public function actionQuickUpdate($id, $callback)
	{
	    $id = (int)$id;
	    
	    if ($id <= 0)
	        throw new CHttpException(500, '非法请求');

	    $model = AdminPost::model()->findByPk($id);
	    if ($model === null)
	        throw new CHttpException(404, '文章不存在');
	     
        $model->attributes = $_POST['AdminPost'];
        $model = self::updatePostEditor($model);
        $attributes = array('state', 'hottest', 'recommend', 'istop', 'homeshow', 'disable_comment', 'user_id', 'user_name');
        $result = (int)$model->save(true, $attributes);
        
        CDBase::jsonp($callback, $result);
	}
	
    public function actionSetVerify($id, $callback)
	{
	    $id = (int)$id;
	    $model = AdminPost::model()->findByPk($id);
	    if ($model === null)
	        throw new CHttpException(500);
	    
	    $model->state = ($model->state == POST_STATE_NOT_VERIFY) ? POST_STATE_ENABLED : POST_STATE_NOT_VERIFY;
	    if ($model->state == POST_STATE_ENABLED) {
	        $model->create_time = $_SERVER['REQUEST_TIME'];
	        $attributes = array('user_id', 'user_name', 'state', 'create_time');
	    }
	    else
	        $attributes = array('state');
	    
	    $model = self::updatePostEditor($model);
        $model->save(true, $attributes);
	    if ($model->hasErrors())
	        throw new CHttpException(500);
	    else {
	        $data = array(
	            'errno' => CD_NO,
	            'label' => t($model->state == POST_STATE_ENABLED ? 'sethide' : 'setshow', 'admin')
	        );
	        CDBase::jsonp($callback, $data);
	    }
	}

	public function actionSetDelete($id, $callback)
	{
	    $id = (int)$id;
	    $model = AdminPost::model()->findByPk($id);
	    if ($model === null)
	        throw new CHttpException(500);
	     
	    if ($model->delete()) {
	        $data = array(
	            'errno' => CD_NO,
	            'label' => '删除',
	        );
	        CDBase::jsonp($callback, $data);
	    }
	    else
	        throw new CHttpException(500);
	}

	public function actionSetTrash($id, $callback)
	{
	    $id = (int)$id;
	    $model = AdminPost::model()->findByPk($id);
	    if ($model === null)
	        throw new CHttpException(404);
	     
	    if ($model->trash()) {
	        $data = array(
	            'errno' => CD_NO,
	            'label' => '删除成功',
	        );
	        CDBase::jsonp($callback, $data);
	    }
	    else
	        throw new CHttpException(500);
	}

	/**
	 * 批量删除文章
	 * @param array $ids ID数组
	 * @param string $callback jsonp回调函数，自动赋值
	 */
	public function actionMultiDelete($callback)
	{
	    $ids = (array)request()->getPost('ids');
	    $successIds = $failedIds = array();
	    foreach ($ids as $id) {
	        $model = AdminPost::model()->findByPk($id);
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
	    CDBase::jsonp($callback, $data);
	}
	
	/**
	 * 批量将文章扔到回收站
	 * @param array $ids ID数组
	 * @param string $callback jsonp回调函数，自动赋值
	 */
	public function actionMultiTrash($callback)
	{
	    $ids = (array)request()->getPost('ids');
	    $successIds = $failedIds = array();
	    foreach ($ids as $id) {
	        $model = AdminPost::model()->findByPk($id);
	        if ($model === null)
	            continue;
	        	
	        $result = $model->trash();
	        if ($result)
	            $successIds[] = $id;
	        else
	            $failedIds[] = $id;
	    }
	    $data = array(
    	    'success' => $successIds,
    	    'failed' => $failedIds,
	    );
	    CDBase::jsonp($callback, $data);
	}
	

	/**
	 * 批量设置文章状态
	 * @param array $ids 文章ID数组
	 * @param string $callback jsonp回调函数，自动赋值
	 */
	public function actionMultiState($state, $callback)
	{
	    $state = (int)$state;
	    $ids = (array)request()->getPost('ids');
	     
	    $successIds = $failedIds = array();
	    $attributes = array('user_id', 'user_name', 'state', 'create_time');
	    foreach ($ids as $id) {
	        $model = AdminPost::model()->findByPk($id);
	        if ($model === null) continue;
	        
	        $model->state = $state;
	        $result = $model->save(true, $attributes);
	        if ($result)
	            $successIds[] = $id;
	        else
	            $failedIds[] = $id;
	    }
	    $data = array(
    	    'success' => $successIds,
    	    'failed' => $failedIds,
	    );
	    CDBase::jsonp($callback, $data);
	}
	
	/**
	 * 批量审核文章
	 * @param array $ids 文章ID数组
	 * @param string $callback jsonp回调函数，自动赋值
	 */
	public function actionMultiVerify($callback)
	{
	    $ids = (array)request()->getPost('ids');
	     
	    $successIds = $failedIds = array();
	    $attributes = array('user_id', 'user_name', 'state', 'create_time');
	    foreach ($ids as $id) {
	        $model = AdminPost::model()->findByPk($id);
	        if ($model === null) continue;
	        
	        $model->state = POST_STATE_ENABLED;
	        $model->create_time = $_SERVER['REQUEST_TIME'];
	        $model = self::updatePostEditor($model);
	        $result = $model->save(true, $attributes);
	        if ($result)
	            $successIds[] = $id;
	        else
	            $failedIds[] = $id;
	    }
	    $data = array(
    	    'success' => $successIds,
    	    'failed' => $failedIds,
	    );
	    CDBase::jsonp($callback, $data);
	}

	/**
	 * 批量拒绝文章
	 * @param array $ids 文章ID数组
	 * @param string $callback jsonp回调函数，自动赋值
	 */
	public function actionMultiReject($callback)
	{
	    $ids = (array)request()->getPost('ids');
	     
	    $successIds = $failedIds = array();
	    $attributes = array(
	        'state' => POST_STATE_REJECTED,
	    );
	    foreach ($ids as $id) {
	        $result = AdminPost::model()->updateByPk($id, $attributes);
	        if ($result)
	            $successIds[] = $id;
	        else
	            $failedIds[] = $id;
	    }
	    $data = array(
    	    'success' => $successIds,
    	    'failed' => $failedIds,
	    );
	    CDBase::jsonp($callback, $data);
	}
	
	/**
	 * 批量推荐文章
	 * @param array $ids 文章ID数组
	 * @param string $callback jsonp回调函数，自动赋值
	 */
	public function actionMultiRecommend($callback)
	{
	    $ids = (array)request()->getPost('ids');
	     
	    $successIds = $failedIds = array();
	    $attributes = array(
    	    'state' => POST_STATE_ENABLED,
    	    'recommend' => CD_YES,
    	    'create_time' => $_SERVER['REQUEST_TIME'],
	    );
	    foreach ($ids as $id) {
	        $result = AdminPost::model()->updateByPk($id, $attributes);
	        if ($result)
	            $successIds[] = $id;
	        else
	            $failedIds[] = $id;
	    }
	    $data = array(
    	    'success' => $successIds,
    	    'failed' => $failedIds,
	    );
	    CDBase::jsonp($callback, $data);
	}
	
	/**
	 * 批量设置热门文章
	 * @param array $ids 文章ID数组
	 * @param string $callback jsonp回调函数，自动赋值
	 */
	public function actionMultiHottest($callback)
	{
	    $ids = (array)request()->getPost('ids');
	     
	    $successIds = $failedIds = array();
	    foreach ($ids as $id) {
	        $model = AdminPost::model()->findByPk($id);
	        if ($model === null) continue;
	         
	        $model->hottest = CD_YES;
	        $model->state = POST_STATE_ENABLED;
	         
	        $result = $model->save(true, array('hottest', 'state'));
	        if ($result)
	            $successIds[] = $id;
	        else
	            $failedIds[] = $id;
	    }
	    $data = array(
    	    'success' => $successIds,
    	    'failed' => $failedIds,
	    );
	    CDBase::jsonp($callback, $data);
	}


	private static function updatePostEditor(AdminPost $model)
	{
	    if (empty($model->user_id) && $model->state == POST_STATE_ENABLED) {
	        $model->user_id = user()->id;
	        $model->user_name = user()->name;
	    }
	    
	    return $model;
	}
	
}