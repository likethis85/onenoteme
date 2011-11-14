<?php
/**
 * 笔记Api接口
 * @author Chris
 * @copyright cdcchen@gmail.com
 * @package api
 */


class Api_Post extends ApiBase
{
    public function getone()
    {
        self::requireGet();
        $params = $this->filterParams(array('postid', 'fields'));
        
        try {
	        $criteria = new CDbCriteria();
	        $criteria->select = (isset($params['fields']) && $params['fields']) ? $params['fields'] : '*';
	        $criteria->addColumnCondition(array('id'=>$params['noteid']));
	        $data = Post::model()->findByPk($params['postid'], $criteria);
	        return $data;
        }
        catch (Exception $e) {
        	throw new ApiException('系统错误', ApiError::SYSTEM_ERROR, $params['debug']);
        }
    }
    
    public function getListOfCategory()
    {
        self::requireGet();
        $this->requiredParams(array('cid'));
        $params = $this->filterParams(array('cid', 'fields'));
        
    	try {
	        $criteria = new CDbCriteria();
	        $criteria->select = (isset($params['fields']) && $params['fields']) ? $params['fields'] : '*';
	        $criteria->order = 'create_time desc, id desc';
	        $criteria->addColumnCondition(array('category_id'=>$params['cid']));
	        $data = Post::model()->findAll($criteria);
	        return $data;
        }
        catch (Exception $e) {
        	throw new ApiException('系统错误', ApiError::SYSTEM_ERROR);
        }
    }
    
    public function delete()
    {
    	self::requirePost();
    	$this->requireLogin();
        $this->requiredParams(array('postid'));
        $params = $this->filterParams(array('postid'));
        
        try {
	        return Post::model()->findByPk($params['postid'])->delete();
        }
        catch (Exception $e) {
        	throw new ApiException('系统错误', ApiError::SYSTEM_ERROR);
        }
    }
   
    public function create()
    {
    	self::requirePost();
//    	$this->requireLogin();
    	$this->requiredParams(array('content', 'token'));
    	$params = $this->filterParams(array('content', 'tags', 'category_d', 'token'));
    	
    	$post = new Post('api');
    	$post->category_id = (int)$params['category_id'];
    	$post->content = $params['content'];
    	$post->tags = $params['tags'];
    	$post->create_time = $_SERVER['REQUEST_TIME'];
    	    
    	try {
    	    return $post->validate();
    		return (int)$post->save();
    	}
    	catch (ApiException $e) {
    		throw new ApiException('系统错误', ApiError::SYSTEM_ERROR);
    	}
    }
    
}