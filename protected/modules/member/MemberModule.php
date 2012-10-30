<?php

class MemberModule extends CWebModule
{
	public function init()
	{
	    if (user()->getIsGuest()) {
	        $url = url('site/login', array('url'=>abu(request()->getUrl())));
	        request()->redirect($url);
	        exit(0);
	    }
	    
		$this->setImport(array(
			'member.models.*',
			'member.components.*',
		));
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
