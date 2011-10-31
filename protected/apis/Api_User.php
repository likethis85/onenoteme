<?php
/**
 * 用户Api接口
 * @author Chris
 * @copyright cdcchen@gmail.com
 * @package api
 */

class Api_User extends ApiBase
{
    public function login()
    {
        self::requirePost();
        $this->requiredParams(array('email', 'password'));
        $params = $this->filterParams(array('email', 'password'));
        
        try {
	        $criteria = new CDbCriteria();
	        $criteria->select = array('id', 'email', 'name', 'create_time');
	        $columns = array('email'=>$params['email'], 'password'=>$params['password']);
	        $criteria->addColumnCondition($columns);
	        $criteria->addCondition('state != ' . User::STATE_DISABLED);
//	        echo $criteria->condition;exit;
	        $user = User::model()->find($criteria);
        }
        catch (ApiException $e) {
        	throw new ApiException('系统错误', ApiError::SYSTEM_ERROR);
        }
        
	    if (null !== $user) {
	    	// @todo 此处的token需要制定规则，生成返回给客户端，客户端以后请求需要登录才能使用的api时，要带上此参数
        	$user->token = md5(param('name'));
        	$this->afterLogin($user, $params);
        	return $user;
        }
        else {
        	// @todo 此处处理错误
        	throw new ApiException('用户登录错误', ApiError::USER_NOT_EXIST);
        }
    }
    
    private function afterLogin($user, $params)
    {
    	// @todo 处理验证通过后的事情
    	try {
    		app()->cache->set($user->name, $user->token);
    	}
    	catch (ApiException $e) {
    		throw new ApiException('系统错误', ApiError::SYSTEM_ERROR);
    	}
    }
    
    public function logout()
    {
        self::requirePost();
        $this->requiredParams(array('name', 'token'));
        $params = $this->filterParams(array('name', 'token'));
        
        $token = app()->getCache()->get($params['name']);
        if ($token == $params['token'])
        	app()->getCache()->delete($params['name']);
        else
        	throw new ApiException('$token 验证失败', ApiError::USER_TOKEN_ERROR);
    }
    
    public function create()
    {
        self::requirePost();
        $this->requiredParams(array('name', 'password', 'email'));
        $params = $this->filterParams(array('name', 'password', 'email'));
        
        $user = new User();
        $user->name = $params['name'];
        $user->password = md5($params['password']);
        $user->email = $params['email'];
        try {
        	if ($user->insert()) {
        	    $data['name'] = $user->name;
        	    $data['token'] = md5($user->name);
        	    return $data;
        	}
        	else
        	    return 0;
        }
        catch (ApiException $e) {
        	throw new ApiException('系统错误', ApiError::SYSTEM_ERROR);
        }
    }
}
?>