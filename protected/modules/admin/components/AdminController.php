<?php
class AdminController extends CController
{
    public $title;
    
    public function actions()
	{
		return array(
			'captcha'=>array(
				'class'=>'application.extensions.CdCaptcha.CdCaptchaAction',
				'backColor' => 0xFFFFFF,
				'height' => 22,
				'width' => 70,
				'maxLength' => 4,
				'minLength' => 4,
		        'foreColor' => 0xFF0000,
		        'padding' => 3,
		        'testLimit' => 3,
			),
		);
	}
	
	public function init()
	{
	    
	}
}