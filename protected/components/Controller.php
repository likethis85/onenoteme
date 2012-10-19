<?php
class Controller extends CController
{
    public $breadcrumbs;
    public $channel;

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
				'height' => 30,
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
        if (user()->getIsGuest()) {
            $html = sprintf('<li><a href="%s">注册</a></li>', aurl('site/signup'));
			$html .= sprintf('<li class="user-login"><a class="fleft" href="%s">登录</a></li>', aurl('site/login'));
			$html .= sprintf('<li class="sns-icon"><a class="fright" href="%s"><img src="%s" alt="用新浪微博账号登录s" /></a>', aurl('weibo/sinat'), sbu('images/weibo24.png'));
			$html .= sprintf('<a class="fright" href="%s"><img src="%s" alt="" /></a></li>', aurl('weibo/qqt'), sbu('images/qqt24.png'));
        }
        elseif (app()->session['image_url']) {
            $html = sprintf('<li class="user-name"><span><img src="%s" alt="进入用户中心" align="top" /></span><a href="%s">%s</a></li>', app()->session['image_url'], aurl('member/defaut/index'), user()->name);
			$html .= sprintf('<li><a href="%s">退出</a></li>', aurl('site/logout'));
        }
        else {
            $html = sprintf('<li><a class="active">%s</a></li>', user()->name);
            $html .= sprintf('<li><a href="%s">退出</a></li>', aurl('site/logout'));
        }
        
        return $html;
    }
}