<?php

class MemberModule extends CWebModule
{
	public function init()
	{
		$this->setImport(array(
			'member.models.*',
			'member.components.*',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		if (parent::beforeControllerAction($controller, $action)) {
			if (user()->getIsGuest()) {
		        $url = url('site/login', array('url'=>request()->getUrl()));
		        request()->redirect($url);
		        exit(0);
		    }
			return true;
		}
		else
			return false;
	}
}
