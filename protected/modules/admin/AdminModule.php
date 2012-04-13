<?php

class AdminModule extends CWebModule
{
	public function init()
	{
		$this->setImport(array(
			'admin.models.*',
			'admin.components.*',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			if (user()->getIsGuest())
			    request()->redirect(url('site/login'));
			else
    			return true;
		}
		else
			return false;
	}
}
