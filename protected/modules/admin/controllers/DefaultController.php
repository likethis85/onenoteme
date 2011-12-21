<?php

class DefaultController extends Controller
{
	public function actionIndex()
	{
		$this->renderPartial('index');
	}
	
	public function actionSidebar()
	{
	    $this->render('sidebar');
	}
	
	public function actionWelcome()
	{
	    $this->render('welcome');
	}
	
	
	public function actionLogin()
	{
	    if (!user()->getIsGuest() && user()->getState('isadmin'))
	        $this->redirect(url('admin/default/index'));
	
	    $model = new AdminLoginForm();
	    if (request()->getIsPostRequest() && isset($_POST['AdminLoginForm'])) {
	        $model->setAttributes($_POST['AdminLoginForm']);
	        if ($model->validate()) {
	            $this->redirect(url('admin/default/index'));
	        }
	    }
	    $this->render('login', array('model'=>$model));
	}
	
	public function actionLogout()
	{
	    user()->logout();
	    $this->redirect(url('admin/default/login'));
	}
}