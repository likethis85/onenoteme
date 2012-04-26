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
			else {
			    $uid = (int)user()->id;
			    if (empty($uid))
			        throw new CHttpException(500, '非法操作');
			    
			    $user = AdminUser::model()->findByPk($uid);
			    if ($user === null || $user->state != AdminUser::STATE_ADMIN)
			        throw new CHttpException(500, '没有权限');
			    
			    return true;
			}
		}
		else
			return false;
	}
}
