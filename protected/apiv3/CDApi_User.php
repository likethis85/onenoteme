<?php
/**
 * 用户Api接口
 * @author Chris
 * @copyright cdcchen@gmail.com
 * @package api
 */

class CDApi_User extends ApiBase
{
    public function create()
    {
        
    }
    
    /**
     * 用户验证
     * @param string $username
     * @param string $password md5加密后的密码
     */
    public function login()
    {
        $this->requiredParams(array('username', 'password'));
        $params = $this->filterParams(array('username', 'password'));
        
        $username = trim($params['username']);
        $password = trim($params['password']);
        $identity = new AppUserIdentity($username, $password);
        if ($identity->authenticate(true)) {
            if (appuser()->login($identity))
                return $this->formatRow($identity->getUser());
            else
                throw new CDApiException(ApiBase::USER_LOGIN_ERROR);
        }
        else
            throw new CDApiException(ApiBase::USER_NOT_AUTHENTICATED);
    }
    
    /**
     * 注销用户
     * @param string $username
     */
    public function logout()
    {
        $this->requiredParams(array('username'));
        $params = $this->filterParams(array('username'));
        
        appuser()->logout();
    }
    
    /**
     * 判断用户是否登录
     * @param string $username 用户名，可选
     */
    public function logined()
    {
        $params = $this->filterParams(array('username'));
        return appuser()->getIsGuest();
    }
    
    /**
     * 查看用户信息
     * @param string $username
     */
    public function show()
    {
        $this->requiredParams(array('user_id', 'username'));
        $params = $this->filterParams(array('user_id', 'username'));
        
        $user_id = (int)$params['user_id'];
        $user = ApiUser::model()->findByPk($user_id);
        if ($user === null)
            throw new CDApiException(ApiError::USER_NOT_EXIST);
        else
            return $this->formatRow($user);
    }
    
    
    
    
    
    
    
    
    

    protected function formatRows(array $models)
    {
        $rows = array();
        foreach ($models as $index => $model)
            $rows[$index] = $this->formatRow($model);
    
        $models = null;
        return $rows;
    }
    
    protected function formatRow(ApiComment $model)
    {
        $data = array();
        $fieldMap = $this->fieldAttributeMap();
        foreach ($fieldMap as $key => $field) {
            $data[$key] = self::execRelation($model, $field);
        }
    
        return $data;
    }
    
    /**
     * 返回字段与模型属性对应关系，包括自定义的getMethod方法
     * @see ApiBase::fieldAttributeMap()
     */
    public function fieldAttributeMap()
    {
        return array(
            'id' => 'id',
            'username' => 'username',
            'screen_name' => 'screen_name',
            'create_time' => 'create_time',
            'create_time_text' => 'createTime',
            'token' => 'token',
            'token_time' => 'token_time',
            'website' => array('profile', 'website'),
            'desc' => array('profile', 'description'),
            'mini_avatar' => array('profile', 'getMiniAvatarUrl', true),
            'small_avatar' => array('profile', 'getSmallAvatarUrl', true),
            'large_avatar' => array('profile', 'getLargeAvatarUrl', true),
        );
    }
    
    /**
     * 返回字段与数据库表字段的对应关系
     * @see ApiBase::fieldColumnMap()
     */
    public function fieldColumnMap()
    {
        return array(
            'id' => 'id',
            'username' => 'username',
            'screen_name' => 'screen_name',
            'create_time' => 'create_time',
            'token' => 'token',
            'token_time' => 'token_time',
        );
    }
}


