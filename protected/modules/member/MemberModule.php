<?php

class MemberModule extends CWebModule
{
	public function init()
	{
	    if (user()->getIsGuest()) {
	        $url = CDBaseUrl::loginUrl(abu(request()->getUrl()));
	        request()->redirect($url);
	        exit(0);
	    }
	    
	    $params = require(dirname(__FILE__) . DS . 'config' . DS . 'params.php');
	    Yii::app()->params->mergeWith($params);
	    
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
