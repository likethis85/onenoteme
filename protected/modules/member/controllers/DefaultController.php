<?php

class DefaultController extends MemberController
{
	public function actionIndex()
	{
	    $this->forward('profile/index');
	}
}