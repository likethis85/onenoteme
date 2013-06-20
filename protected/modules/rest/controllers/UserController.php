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
            if (user()->login($identity)) {
                $data = CDRestDataFormat::formatUser($identity->getUser());
                $this->output($data);
            }
            else
                throw new CDRestException(CDRestError::USER_LOGIN_ERROR);
        }
        else
            throw new CDRestException(CDRestError::USER_NOT_AUTHENTICATED);
    }
    
    /**
     * 注销用户
     * @param integer $user_id
     * @param string $username
     */
    public function actionLogout($user_id, $username = '')
    {
        $user_id = (int)$user_id;
        
        appuser()->logout();
        exit(0);
    }
    
    /**
     * 判断用户是否登录
     * @param integer $user_id 用户名，可选
     * @param string $username 用户名，可选
     */
    public function actionLogined()
    {
        $user_id = (int)$user_id;
        
        return appuser()->getIsGuest();
        exit(0);
    }
    
    /**
     * 查看用户信息
     * @param integer $user_id
     * @param string $username
     */
    public function actionShow($user_id, $username = '')
    {
        $user_id = (int)$user_id;
        $user = ApiUser::model()->findByPk($user_id);
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

