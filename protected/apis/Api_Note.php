<?php
/**
 * 笔记Api接口
 * @author Chris
 * @copyright cdcchen@gmail.com
 * @package api
 */


class Api_Note extends ApiBase
{
    public function getone()
    {
        self::requireGet();
        $params = array('noteid');
        $params = $this->filterParams(array('noteid', 'fields'));
        
        try {
	        $criteria = new DDbCriteria();
	        $criteria->select = (isset($params['fields']) && $params['fields']) ? $params['fields'] : '*';
	        $criteria->addColumnCondition(array('id'=>$params['noteid']));
	        $data = Note::model()->find($criteria);
	        return $data;
        }
        catch (Exception $e) {
        	throw new ApiException('系统错误', ApiError::SYSTEM_ERROR, $params['debug']);
        }
    }
    
    public function getListOfBook()
    {
        self::requireGet();
        $this->requiredParams(array('bookid'));
        $params = $this->filterParams(array('bookid', 'fields'));
        
    	try {
	        $criteria = new DDbCriteria();
	        $criteria->select = (isset($params['fields']) && $params['fields']) ? $params['fields'] : '*';
	        $criteria->order = 'id asc';
	        $criteria->addColumnCondition(array('book_id'=>$params['bookid']));
	        $data = Note::model()->findAll($criteria);
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
        $this->requiredParams(array('noteid'));
        $params = $this->filterParams(array('noteid'));
        
        try {
	        $criteria = new DDbCriteria();
	        $criteria->addColumnCondition(array('id'=>$params['noteid']));
	        $data = Note::model()->deleteAll($criteria);
	        return $data;
        }
        catch (Exception $e) {
        	throw new ApiException('系统错误', ApiError::SYSTEM_ERROR);
        }
    }
   
    public function create()
    {
    	self::requirePost();
    	$this->requireLogin();
    	$this->requiredParams(array('title', 'content', 'token'));
    	$params = $this->filterParams(array('id', 'book_id', 'title', 'content', 'token', 'lat', 'lon'));
    	
    	$note = new Note();
    	$note->title = $params['title'];
    	$note->content = $params['content'];
    	$note->create_time = $_SERVER['REQUEST_TIME'];
    	if (empty($params['book_id'])) {
    	    $c = new DDbCriteria();
    	    $c->order = 'id desc';
    	    $c->select = 'id';
    	    $c->addColumnCondition(array('user_id'=>1));
    	    $book = Book::model()->find($c);
    	    $note->book_id = $book->id;
    	}
    	else
    	    $note->book_id = $params['book_id'];
    	    
    	try {
    		return (int)$note->insert();
    	}
    	catch (ApiException $e) {
    		throw new ApiException('系统错误', ApiError::SYSTEM_ERROR);
    	}
    }
    
}