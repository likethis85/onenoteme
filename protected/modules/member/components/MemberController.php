<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class MemberController extends CController
{
    public $channel;
    public $breadcrumbs = array();
    
    /**
     * 当前登录用户
     * @var User
     */
    public $user;
    
    /**
     * 当前登录用户的资料
     * @var UserProfile
     */
    public $profile;
    
    public function init()
    {
        parent::init();
        $user = User::model()->findByPk(user()->id);
        if ($user === null)
            throw new CHttpException(500, '未找到用户');
        
        $this->user = $user;
        $this->profile = $user->profile;
    }
    
	public function setSiteTitle($text)
	{
	    $this->pageTitle = $text . '_' . app()->name;
	}
}