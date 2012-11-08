<?php
class ProfileController extends MemberController
{
    public function actionIndex()
    {
        $this->breadcrumbs[] = '我的基本资料';
        $this->channel = 'home';
        $this->setSiteTitle('我的基本资料');
        $this->render('index');
    }
    
    public function actionEmail()
    {
        $this->breadcrumbs[] = '修改邮箱';
        $this->channel = 'email';
        $this->setSiteTitle('修改邮箱');
        $this->render('index');
    }
    
    public function actionPasswd()
    {
        $this->breadcrumbs[] = '修改密码';
        $this->channel = 'passwd';
        $this->setSiteTitle('修改密码');
        $this->render('index');
    }
    
    public function actionAvatar()
    {
        $this->breadcrumbs[] = $this->title = '修改头像';
        $this->channel = 'avatar';
        $this->setSiteTitle('修改头像');
        $this->render('index');
    }
}