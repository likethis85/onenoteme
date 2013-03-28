<?php
class ProfileController extends MemberController
{
    public function actionIndex()
    {
        $model = $this->getProfile();
        if (request()->getIsPostRequest() && isset($_POST['MemberUserProfile'])) {
            $model->attributes = $_POST['MemberUserProfile'];
        
            if ($model->save()) {
                user()->setFlash('user_save_result', '昵称修改成功');
                $this->redirect(request()->getUrl());
            }
        }
        
        $this->breadcrumbs[] = $this->title = $this->siteTitle = '基本资料';
        $this->menu = 'profile';
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
        $this->menu = 'nickname';
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
        $this->menu = 'passwd';
        $this->render('passwd', array(
            'model' => $model,
        ));
    }
    
    public function actionAvatar()
    {
        $model = $this->getProfile();
        if (request()->getIsPostRequest() && isset($_POST['MemberUserProfile'])) {
            $upload = CUploadedFile::getInstance($model, 'original_avatar');
            if ($upload === null)
                $model->addError('original_avatar', '请选择头像图片');
            else {
                $model->original_avatar = $upload;
                if ($model->uploadAvatar() && $model->save(true, array('original_avatar', 'avatar_large', 'image_url'))) {
                    user()->setFlash('user_save_result', '头像修改成功');
                    $this->redirect(request()->getUrl());
                }
            }
        }
        
        $this->breadcrumbs[] = $this->title = $this->siteTitle = '修改头像';
        $this->menu = 'avatar';
        $this->render('avatar', array(
            'model' => $model,
        ));
    }
}

