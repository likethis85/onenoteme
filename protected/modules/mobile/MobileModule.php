<?php

class MobileModule extends CWebModule
{
	public function init()
	{
		// import the module-level models and components
		$this->setImport(array(
			'mobile.models.*',
			'mobile.components.*',
			'mobile.widgets.*',
		));
		
		app()->errorHandler->errorAction = 'mobile/default/error';
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
