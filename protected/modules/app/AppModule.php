<?php

class AppModule extends CWebModule
{
	public function init()
	{
		$this->setImport(array(
			'app.models.*',
			'app.components.*',
		));
		
		app()->errorHandler->errorAction = 'app/default/error';
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action)) {
			return true;
		}
		else
			return false;
	}
}
