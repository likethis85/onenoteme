<?php

class DefaultController extends AdminController
{
	public function actionIndex()
	{
	    $criteria = new CDbCriteria();
	    $criteria->addColumnCondition(array('t.state'=>POST_STATE_NOT_VERIFY));
	    $postCount = Post::model()->count($criteria);
	    
	    $criteria = new CDbCriteria();
	    $criteria->addColumnCondition(array('t.state'=>USER_STATE_UNVERIFY));
	    $userCount = User::model()->count($criteria);
	    
	    $criteria = new CDbCriteria();
	    $criteria->addColumnCondition(array('t.state'=>COMMENT_STATE_NOT_VERIFY));
	    $commentCount = Comment::model()->count($criteria);
	    
	    $this->render('index', array(
	        'postCount' => $postCount,
	        'userCount' => $userCount,
	        'commentCount' => $commentCount,
	    ));
	}

	public function actionError()
	{
	    $error = app()->errorHandler->error;
	    if ($error) {
	        $this->render('/system/error', $error);
	    }
	}
	
	
    public function actionTest()
    {
        $this->layout = 'test';
        $this->render('/default/welcome');
    }
}