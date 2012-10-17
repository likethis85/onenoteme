<?php

class DefaultController extends AdminController
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
	
}