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
                if ($model->uploadAvatar() && $model->save(true, array('original_avatar'))) {
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

    public function actionSendMail()
    {
        if ($this->user->getVerified()) {
            $data['errno'] = 1;
            $data['message'] = '您的账号已经激活，不需要再重新发送激活邮件。';
        }
        else {
            $result = $this->user->sendVerifyEmail();
            if ($result) {
                $data['errno'] = 0;
                $data['message'] = '确认邮件已经发送到您的注册邮箱中，请进入邮箱点击链接激活账号。';
            }
            else {
                $data['errno'] = 1;
                $data['message'] = '邮件发送失败，请重试或<a href="' . aurl('help/contact') . '" target="_blank">联系客服人员</a>';
            }
        }

        $this->breadcrumbs[] = $this->title = $this->siteTitle = '发送账号确认邮件';
        $this->render('sendmail', $data);
    }
}

