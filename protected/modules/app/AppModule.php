<?php

class AppModule extends CWebModule
{
	public function init()
	{
		// import the module-level models and components
		$this->setImport(array(
			'mobile.models.*',
			'mobile.components.*',
		));
		
		app()->errorHandler->errorAction = 'app/default/error';
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			return true;
		}
		else
			return false;
	}
}
