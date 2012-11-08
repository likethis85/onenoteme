<?php
class ProfileController extends MemberController
{
    public function actionIndex()
    {
        $model = $this->profile;
        if (request()->getIsPostRequest() && isset($_POST['MemberUserProfile'])) {
            $model->attributes = $_POST['MemberUserProfile'];
        
            if ($model->save()) {
                user()->setFlash('user_save_result', '昵称修改成功');
                $this->redirect(request()->getUrl());
            }
        }
        
        $this->breadcrumbs[] = $this->title = $this->siteTitle = '基本资料';
        $this->channel = 'profile';
        $this->render('profile', array(
            'model' => $model,
        ));
    }
    
    public function actionNickname()
    {
        $model = $this->user;
        if (request()->getIsPostRequest() && isset($_POST['MemberUser'])) {
            $model->attributes = $_POST['MemberUser'];

            if ($model->save(true, array('screen_name'))) {
                user()->setFlash('user_save_result', '昵称修改成功');
                $this->redirect(request()->getUrl());
            }
        }
        
        $this->breadcrumbs[] = $this->title = $this->siteTitle = '修改昵称';
        $this->channel = 'nickname';
        $this->render('nickname', array(
            'model' => $model,
        ));
    }
    
    public function actionPasswd()
    {
        $model = $this->user;
        if (request()->getIsPostRequest() && isset($_POST['MemberUser'])) {
            $model->attributes = $_POST['MemberUser'];
            $model->encryptPassword();
            
            if ($model->save(true, array('password'))) {
                user()->setFlash('user_save_result', '密码修改成功');
                $this->redirect(request()->getUrl());
            }
        }
        $model->password = '';
        
        $this->breadcrumbs[] = $this->title = $this->siteTitle = '修改密码';
        $this->channel = 'passwd';
        $this->render('passwd', array(
            'model' => $model,
        ));
    }
    
    public function actionAvatar()
    {
        $this->breadcrumbs[] = $this->title = $this->siteTitle = '修改头像';
        $this->channel = 'avatar';
        $this->render('avatar');
    }
}