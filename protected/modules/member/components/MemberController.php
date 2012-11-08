<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 *
 * @property integer $userID
 * @property string $username
 * @property MemberUser $user
 * @property UserProfile $profile
 */
class MemberController extends CController
{
    public $title;
    public $channel;
    public $breadcrumbs = array();
    
	public function setSiteTitle($text)
	{
	    $this->pageTitle = $text . '_' . app()->name;
	}
	
	public function getUserID()
	{
	    return user()->id;
	}
	
	public function getUsername()
	{
	    return user()->name;
	}

    public function getUser()
    {
        $user = MemberUser::model()->findByPk($this->getUserID());
        if ($user === null)
            throw new CHttpException(500, '未找到用户');
        
        return $user;
    }
    
    public function getProfile()
    {
        return $this->user->profile;
    }
}