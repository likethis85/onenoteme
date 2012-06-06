<?php
class Controller extends CController
{
    public $breadcrumbs;
    public $channel = 'latest';

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
            $html = '<li><a href="' . aurl('site/signup') . '">注册</a></li>';
			$html .= '<li class="user-login"><a class="fleft" href="' . aurl('site/login') . '">登录</a>';
			$html .= '<a class="fright" href="' . aurl('weibo/authorize') . '">' . image('http://www.sinaimg.cn/blog/developer/wiki/24x24.png', '') .'</a></li>';
        }
        else {
            $html = '<li><span class="active">' . user()->name . '</span></li>';
			$html .= '<li><a href="' . aurl('site/logout') . '">退出</a></li>';
        }
        return $html;
    }
}