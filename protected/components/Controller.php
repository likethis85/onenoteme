<?php
class Controller extends CController
{
    public $breadcrumbs;
    public $channel;
    public $clientID = null;
    public $lastVisit = array();

    public function actions()
	{
		return array(
			'captcha'=>array(
				'class'=>'application.extensions.CDCaptcha.CDCaptchaAction',
				'backColor' => 0xFFFFFF,
				'height' => 30,
				'width' => 100,
				'maxLength' => 4,
				'minLength' => 4,
		        'foreColor' => 0xFF0000,
		        'padding' => 5,
		        'testLimit' => 3,
			),
			'bigCaptcha'=>array(
				'class'=>'application.extensions.CDCaptcha.CDCaptchaAction',
				'backColor' => 0xFFFFFF,
				'height' => 50,
				'width' => 170,
				'maxLength' => 4,
				'minLength' => 4,
		        'foreColor' => 0xFF0000,
		        'padding' => 5,
		        'testLimit' => 3,
			),
		);
	}
	
	public function init()
	{
	    parent::init();
	    $this->lastVisit = CDBase::setClientLastVisit();
	    
	    $this->clientID = CDBase::getClientID();
	    if (empty($this->clientID))
	        $this->clientID = CDBase::setClientID();
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
        $html = '';
        if (user()->getIsGuest()) {
            $html = sprintf('<li><a href="%s">注册</a></li>', aurl('site/signup'));
			$html .= sprintf('<li class="user-login"><a class="fleft" href="%s">登录</a></li>', aurl('site/login'));
			$html .= sprintf('<li class="sns-icon"><a class="fright" href="%s"><img src="%s" alt="用新浪微博账号登录s" /></a>', aurl('weibo/sinat'), sbu('images/weibo24.png'));
			$html .= sprintf('<a class="fright" href="%s"><img src="%s" alt="" /></a></li>', aurl('weibo/qqt'), sbu('images/qqt24.png'));
        }
        else {
            $html = sprintf('<li class="user-name"><a href="%s" title="我的主页">我的主页</a></li>', user()->getHomeUrl());
            $html .= sprintf('<li class="user-name"><a href="%s" title="查看我的收藏">我的收藏</a></li>', aurl('member/post/favorite'));
            	
            if ($this->getProfile()->getMiniAvatarUrl())
                $avatar = sprintf('<img src="%s" alt="进入用户中心" align="top" />', $this->getProfile()->getMiniAvatarUrl());
            $html .= sprintf('<li class="user-name"><a href="%s" title="进入用户中心">%s%s</a></li>', CDBaseUrl::memberHomeUrl(), $avatar, user()->name);
            $html .= sprintf('<li><a href="%s">退出</a></li>', aurl('site/logout'));
        }
        
        return $html;
    }

    protected function autoSwitchMobile($url = null)
    {
        $mark = strip_tags(trim($_GET['f']));
        if (empty($mark) && CDBase::isMobileDevice()) {
            if (empty($url)) {
                $route = 'mobile/' . $this->id . '/' . $this->action->id;
                $url = aurl($route, $this->actionParams);
            }
            $this->redirect($url);
            exit(0);
        }
    }

    public function getUserID()
    {
        return (int)user()->id;
    }
    
    public function getUsername()
    {
        return $this->user->username;
    }
    
    public function getNickname()
    {
        return user()->name;
    }
    
    public function getUser()
    {
        $user = User::model()->findByPk($this->getUserID());
        if ($user === null)
            throw new CHttpException(500, '未找到用户');
    
        return $user;
    }
    
    /**
     * 获取当前登录用户的资料
     * @return UserProfile
     */
    public function getProfile()
    {
        return $this->user->profile;
    }



    public function filterSwitchMobile($filterChain)
    {
        $url = url('mobile/' . $filterChain->controller->id . '/' . $filterChain->action->id, $filterChain->controller->getActionParams());
        $this->autoSwitchMobile($url);
    
        $filterChain->run();
    }
    
    protected function beforeRender($view)
    {
        cs()->defaultScriptFilePosition = CClientScript::POS_END;
        return true;
    }
}