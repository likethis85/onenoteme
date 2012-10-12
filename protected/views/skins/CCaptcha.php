<?php
return array(
    'default' => array(
	    'captchaAction' => 'captcha',
        'clickableImage' => true,
        'buttonLabel' => '换一张',
        'imageOptions' => array('alt'=>'验证码', 'align'=>'top', 'class'=>'beta-captcha-img'),
        'buttonOptions' => array('class'=>'refresh-captcha'),
    ),
    'comment' => array(
        'captchaAction' => 'captcha',
        'buttonLabel' => '换一张',
        'clickableImage' => false,
        'showRefreshButton' => false,
        'imageOptions' => array('alt'=>'验证码', 'align'=>'top', 'class'=>'beta-captcha-img'),
        'buttonOptions' => array('class'=>'refresh-captcha'),
    ),
    'big' => array(
	    'captchaAction' => 'bigcaptcha',
        'clickableImage' => true,
        'buttonLabel' => '换一张',
        'imageOptions' => array('alt'=>'验证码', 'align'=>'top', 'class'=>'beta-captcha-img'),
        'buttonOptions' => array('class'=>'refresh-captcha'),
    ),
);