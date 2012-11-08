<?php
class ProfileController extends MemberController
{
    public function actionIndex()
    {
        $this->breadcrumbs[] = $this->title = $this->siteTitle = '基本资料';
        $this->channel = 'profile';
        $this->render('profile');
    }
    
    public function actionEmail()
    {
        $this->breadcrumbs[] = $this->title = $this->siteTitle = '修改邮箱';
        $this->channel = 'email';
        $this->render('email');
    }
    
    public function actionPasswd()
    {
        $this->breadcrumbs[] = $this->title = $this->siteTitle = '修改密码';
        $this->channel = 'passwd';
        $this->render('passwd');
    }
    
    public function actionAvatar()
    {
        $this->breadcrumbs[] = $this->title = $this->siteTitle = '修改头像';
        $this->channel = 'avatar';
        $this->render('avatar');
    }
}