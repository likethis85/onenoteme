<?php
class UserController extends RestController
{
    public function filters()
    {
        return array(
            'postOnly + create, login, logout',
        );
    }
    
    public function actionCreate()
    {
        $username = request()->getPost('username');
        $password = request()->getPost('password');
        $form = new RestUserForm();
        $form->username = $username;
        $form->password = $password;
        if ($form->validate() && $user = $form->save()) {
            $this->afterSave($user);
            $token = RestUser::generateUserToken($user->id, $username);
            $data = CDRestDataFormat::formatUser($user, $token);
            $this->output($data);
        }
        else
            throw new CDRestException(CDRestError::USER_CREATE_ERROR);
            
    }
    
    protected function afterSave(RestUser $user)
    {
        $device = RestMobileDevice::model()->findByPk($this->deviceUDID);
        if ($device === null) {
            $device = new RestMobileDevice();
            $device->udid = $this->deviceUDID;
            $device->user_id = $user->id;
            $device->sys_version = $this->osVersion;
            $device->sys_name = $this->osName;
            $device->app_version = $this->appVersion;
            $result = $device->save();
        }
        else {
            $device->user_id = $user->id;
            $result = $device->save(true, array('user_id'));
        }
        
        return $result ? $device : false;
    }
    
    
    /**
     * 用户验证
     * @param string $username
     * @param string $password md5加密后的密码
     */
    public function actionLogin()
    {
        $username = trim(request()->getPost('username'));
        $password = trim(request()->getPost('password'));
        $identity = new AppUserIdentity($username, $password);
        if ($identity->authenticate(true)) {
            $userID = $identity->getId();
            $device = $this->getCurrentDevice();
            if ($device) {
                $userToken = RestUser::generateUserToken($identity->getId(), $username);
                $device->user_token = $userToken;
                $device->user_id = $userID;
                $result = $device->save(true, array('user_id', 'user_token'));
                if ($result) {
                    $data = CDRestDataFormat::formatUser($identity->getUser(), $userToken);
                    $this->output($data);
                }
                else
                    throw new CDRestException(CDRestError::USER_LOGIN_ERROR);
            }
            else {
                //@todo 这里需要额外处理，此情况逻辑上不会发生
                throw new CDRestException(CDRestError::USER_NOT_EXIST);
            }
            
        }
        else
            throw new CDRestException(CDRestError::USER_NOT_AUTHENTICATED);
    }
    
    /**
     * 注销用户
     * @param integer $user_id
     * @param string $username
     */
    public function actionLogout()
    {
        try {
            $device = $this->getCurrentDevice();
            $device->user_token = '';
            $device->save(true, array('user_token'));
            $this->output(array('success' => 1));
        }
        catch (Exception $e) {
            throw new CDRestException(CDRestError::CLASS_METHOD_EXECUTE_ERROR);
        }
    }
    
    /**
     * 判断用户是否登录
     * @param integer $user_id 用户名，可选
     * @param string $username 用户名，可选
     */
    public function actionLogined()
    {
        $data = array('logined' => (int)$this->getUser());
        $this->output($data);
    }
    
    /**
     * 查看用户信息
     * @param integer $user_id
     * @param string $username
     */
    public function actionShow($user_id, $username = '')
    {
        $user_id = (int)$user_id;
        $user = RestUser::model()->findByPk($user_id);
        if ($user === null)
            throw new CDRestException(CDRestError::USER_NOT_EXIST);
        else
            $this->output(CDRestDataFormat::formatUser($user));
        
        exit(0);
    }
    
    
    /**
     * 返回字段列表
     */
    public function selectColumns()
    {
        return array('id', 'username', 'screen_name', 'create_time', 'create_time', 'token', 'token_time');
    }
}

