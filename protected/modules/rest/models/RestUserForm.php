<?php

/**
 * 通过api注册账号专用表单模型
 * @author chendong
 *
 * @param string $username
 * @param string $password
 */
class RestUserForm extends CFormModel
{
    public $username;
    public $password;
    
    public function rules()
    {
        return array(
            array('username, password', 'required', 'message'=>'您总得填用户名和密码吧'),
            array('username', 'length', 'min'=>2, 'max'=>30, 'message'=>'用户名允许的长度为2-30个字'),
            array('password', 'length', 'is'=>32, 'message'=>'密码必须为MD5加密后的32位字符串'),
//             array('username', 'checkUserName'),
            array('username', 'unique', 'caseSensitive'=>false, 'className'=>'RestUser', 'attributeName'=>'username', 'message'=>'啊，被抢注了，换个名字吧'),
        );
    }
    
    public function attributeLabels()
    {
        return array(
            'username' => '名字',
            'password' => '密码',
        );
    }
    

    public function checkUserName($attribute, $params)
    {
        $value = $this->$attribute;
        if (CDBase::checkEmail($value) || CDBase::checkMobilePhone($value))
            return true;
        else
            $this->addError($attribute, '用户名必须为邮箱或手机号');
    }
    
    protected function beforeValidate()
    {
        return true;
    }
    
    public function save()
    {
        $user = new RestUser();
        $user->username = $user->screen_name = $this->username;
        $user->password = $this->password;
        if ($user->getUserNameIsEmail()) {
            $pos = mb_stripos($this->username, '@', null, app()->charset);
            $user->screen_name = mb_substr($this->username, 0, $pos, app()->charset);
        }

        if ($user->save()) {
            return $user;
        }
        else
            throw new CDRestException(CDRestError::USER_CREATE_ERROR);
    }
}



