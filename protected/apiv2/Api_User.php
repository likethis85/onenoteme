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
        $this->requiredParams(array('username', 'password'));
        $params = $this->filterParams(array('username', 'password'));
        
        try {
	        $criteria = new CDbCriteria();
	        $criteria->select = array('id', 'username', 'screen_name', 'create_time', 'state');
	        $columns = array('username'=>$params['username'], 'password'=>$params['password']);
	        $criteria->addColumnCondition($columns);
	        $criteria->addCondition('state = ' . User::STATE_ENABLED);
	        $user = User::model()->find($criteria);
        }
        catch (ApiException $e) {
        	throw new ApiException('系统错误', ApiError::SYSTEM_ERROR);
        }
        
	    if (null !== $user) {
        	$user->token = self::makeToken($user->username);
        	$user->save(true, array('token'));
        	$this->afterLogin($user, $params);
        	$data = $user->attributes;
        	unset($user);
        	unset($data['password'], $data['create_ip']);
        	return $data;
        }
        else {
        	// @todo 此处处理错误
        	throw new ApiException('user or password is error');
        }
    }
    
    private function afterLogin(User $user, $params)
    {
    	$data = $user->attributes;
    	try {
    	    $key = 'app_user_info_' . $user->id;
    		app()->cache->set($key, $data, 3600*24*30);
    	}
    	catch (ApiException $e) {
    		throw new ApiException('系统错误', ApiError::SYSTEM_ERROR);
    	}
    }
    
    public function logout()
    {
        self::requirePost();
        $this->requiredParams(array('user_id', 'token'));
        $params = $this->filterParams(array('userid', 'token'));
        
        $key = 'app_user_info_' . $params['userid'];
        
        $user = app()->getCache()->get($key);
        $token = $user['token'];
        if ($token == $params['token']) {
        	$result = app()->getCache()->delete($key);
        	return (int)$result;
        }
        else
        	throw new ApiException('$token 验证失败', ApiError::USER_TOKEN_ERROR);
    }
    
    public function create()
    {
        self::requirePost();
        $this->requiredParams(array('username', 'password'));
        $params = $this->filterParams(array('username', 'password'));
        
        $user = new User('apiinsert');
        $user->password = $params['password'];
        $user->username = $params['username'];
        $user->screen_name = $user->username; // substr($params['username'], 0, strpos($params['username'], '@'));
        $user->state = User::STATE_ENABLED;
        $user->token = self::makeToken($user->username);
        try {
        	if ($user->save()) {
        	    $this->afterLogin($user, $params);
        	    $data = $user->attributes;
            	unset($user);
            	unset($data['password'], $data['create_ip']);
            	return $data;
        	}
        	else {
        	    $messages = $user->getErrors();
        	    $message = current($messages);
        	    throw new ApiException($message);
        	}
        }
        catch (ApiException $e) {
        	throw new ApiException('系统错误', ApiError::SYSTEM_ERROR);
        }
    }
    
    public static function makeToken($username)
    {
        $token = md5(makeToken . $_SERVER['REQUEST_TIME'] . uniqid());
        return $token;
    }

    public static function checkUserAccess($userid, $token)
    {
        $key = 'app_user_info_' . $userid;
        $user = app()->getCache()->get($key);
        if ($user['token'] == $token)
            return $user;
        else
            return false;
    }
    
    
}


