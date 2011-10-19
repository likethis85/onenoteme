<?php
/**
 * 笔记本Api接口
 * @author Chris
 * @copyright cdcchen@gmail.com
 * @package api
 */

class Api_Book extends ApiBase
{
	public function getone()
	{
		self::requireGet();
        $params = array('bookid');
        $params = $this->filterParams(array('bookid'));
        
        try {
	        $criteria = new DDbCriteria();
	        $criteria->addColumnCondition(array('id'=>$params['bookid']));
	        $data = Book::model()->find($criteria);
	        return $data;
        }
        catch (Exception $e) {
        	throw new ApiException('系统错误', ApiError::SYSTEM_ERROR, $params['debug']);
        }
	}
	
    public function getlist()
    {
        self::requireGet();
        $params = $this->filterParams();
        
    	try {
	        $criteria = new DDbCriteria();
	        $criteria->order = 'id asc';
	        $data = Book::model()->findAll($criteria);
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
    	$this->requiredParams(array('name'));
    	$params = $this->filterParams(array('name', 'isdefault'));
    	
    	$book = new Book();
    	$book->name = $params['name'];
    	$book->isdefault = $params['isdefault'];
    	try {
    		return (int)$book->insert();
    	}
    	catch (ApiException $e) {
    		throw new ApiException('系统错误', ApiError::SYSTEM_ERROR);
    	}
    }
    
    public function delete()
    {
    	self::requirePost();
    	$this->requireLogin();
        $this->requiredParams(array('bookid'));
        $params = $this->filterParams(array('bookid'));
        
    	try {
	        $criteria = new DDbCriteria();
	        $criteria->addColumnCondition(array('id'=>$params['bookid']));
	        $data = Book::model()->deleteAll($criteria);
	        return $data;
        }
        catch (Exception $e) {
        	throw new ApiException('系统错误', ApiError::SYSTEM_ERROR);
        }
    }
}
?>