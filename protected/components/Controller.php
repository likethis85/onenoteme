<?php
class Controller extends CController
{
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
			'bigCaptcha'=>array(
				'class'=>'application.extensions.CdCaptcha.CdCaptchaAction',
				'backColor' => 0xFFFFFF,
				'height' => 26,
				'width' => 100,
				'maxLength' => 4,
				'minLength' => 4,
		        'foreColor' => 0xFF0000,
		        'padding' => 3,
		        'testLimit' => 3,
			),
		);
	}
	
    public function setKeywords($content)
    {
        cs()->registerMetaTag($content, 'keywords');
    }
    
    public function setDescription($content)
    {
        cs()->registerMetaTag($content, 'description');
    }
    
    
    public function userToolbar()
    {
        if (user()->isGuest) {
            $html = '<a href="' . aurl('site/signup') . '">注册</a>';
			$html .= '<a href="' . aurl('site/login') . '">登录</a>';
        }
        else {
            $html = '<span>' . user()->name . '</span>';
			$html .= '<a href="' . aurl('site/logout') . '">退出</a>';
        }
        return $html;
    }
}