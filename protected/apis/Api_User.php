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
	        $criteria->select = array('id', 'email', 'name', 'create_time', 'state');
	        $columns = array('email'=>$params['email'], 'password'=>$params['password']);
	        $criteria->addColumnCondition($columns);
	        $criteria->addCondition('state != ' . User::STATE_DISABLED);
	        $user = User::model()->find($criteria);
        }
        catch (ApiException $e) {
        	throw new ApiException('系统错误', ApiError::SYSTEM_ERROR);
        }
        
	    if (null !== $user) {
        	$user->token = self::makeToken($user->email);
        	$user->save(true, array('token'));
        	$this->afterLogin($user, $params);
        	$data = $user->attributes;
        	unset($user);
        	unset($data['password'], $data['create_ip']);
        	return array('error'=>'OK', 'userinfo'=>$data);
        }
        else {
        	// @todo 此处处理错误
        	throw new ApiException('用户登录错误', ApiError::USER_NOT_EXIST);
        }
    }
    
    private function afterLogin(User $user, $params)
    {
    	$data = $user->attributes;
    	try {
    		app()->cache->set($user->email, $data, 3600*24*30);
    	}
    	catch (ApiException $e) {
    		throw new ApiException('系统错误', ApiError::SYSTEM_ERROR);
    	}
    }
    
    public function logout()
    {
        self::requirePost();
        $this->requiredParams(array('email', 'token'));
        $params = $this->filterParams(array('email', 'token'));
        
        $user = app()->getCache()->get($params['email']);
        $token = $user['token'];
        if ($token == $params['token']) {
        	$result = app()->getCache()->delete($params['email']);
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
        
        $user = new User();
        $user->password = md5($params['password']);
        $user->email = $params['username'];
        $user->name = $params['username'];
        $user->token = self::makeToken($user->email);
        try {
        	if ($user->save()) {
        	    $this->afterLogin($user, $params);
        	    $data = $user->attributes;
            	unset($user);
            	unset($data['password'], $data['create_ip']);
            	return $data;
        	}
        	else
        	    return 0;
        }
        catch (ApiException $e) {
        	throw new ApiException('系统错误', ApiError::SYSTEM_ERROR);
        }
    }
    
    public static function makeToken($email)
    {
        $token = md5($email . $_SERVER['REQUEST_TIME'] . uniqid());
        return $token;
    }

    public static function checkUserAccess($email, $token)
    {
        $user = app()->getCache()->get($email);
        if ($user['token'] == $token)
            return $user;
        else
            return false;
    }
    
    public function favorite()
    {
        $this->requiredParams(array('email', 'token'));
        $params = $this->filterParams(array('email', 'token', 'fields', 'maxid'));
        
        $user = self::checkUserAccess($params['email'], $params['token']);
        
        if ($user === false)
            throw new ApiException('$token 验证失败', ApiError::USER_TOKEN_ERROR);
        else {
            $uid = (int)$user['id'];
            $cmd = app()->getDb()->createCommand()
                ->select('post_id')
                ->from(TABLE_NAME_POST_FAVORITE)
                ->where('user_id = :userid', array(':userid' => $uid))
                ->order('id desc');
            
            $ids = $cmd->queryColumn();
            
            if (empty($ids)) return array();

            $count = 30;
            $maxid = (int)$params['maxid'];
            $fields = empty($params['fields']) ? '*' : $params['fields'];
            $conditions = array('and', array('in', 'id', $ids), 'state = :enabled');
            $conditionParams = array(':enabled' => Post::STATE_ENABLED);
            if ($maxid > 0) {
                $conditions[] = 'id < :maxid';
                $conditionParams[':maxid'] = $maxid;
            }
            $cmd = app()->getDb()->createCommand()
                ->select($fields)
                ->from(TABLE_NAME_POST)
                ->limit($count)
                ->where($conditions, $conditionParams);
            
            $rows = $cmd->queryAll();
            $rows = Api_Post::formatRows($rows);
            
            return $rows;
        }
            
    }
    
}


