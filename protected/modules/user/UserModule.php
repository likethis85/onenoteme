<?php

class UserModule extends CWebModule
{
	public function init()
	{
		$this->setImport(array(
			'user.models.*',
			'user.components.*',
		));
		
		if (user()->getIsGuest())
		    request()->redirect(url('site/login'));
	}

	public function beforeControllerAction($controller, $action)
	{
		if (parent::beforeControllerAction($controller, $action)) {
		    return true;
		}
		else
			return false;
	}
}
