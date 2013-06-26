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
    public function rules()
    {
        return array(
            array('username, password', 'required', 'message'=>'您总得填用户名和密码吧'),
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
    
    protected function beforeValidate()
    {
        return true;
    }
    
    public function save()
    {
        $user = new RestUser();
        $user->username = $this->username;
        $user->password = $this->password;
    }
}