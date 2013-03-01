<?php

class DefaultController extends AdminController
{
	public function actionIndex()
	{
	    $criteria = new CDbCriteria();
	    $criteria->addColumnCondition(array('t.state'=>POST_STATE_UNVERIFY));
	    $unverifyPostCount = Post::model()->count($criteria);

	    $criteria = new CDbCriteria();
	    $criteria->addColumnCondition(array('t.state'=>POST_STATE_DISABLED));
	    $hidePostCount = Post::model()->count($criteria);
	    
	    $criteria = new CDbCriteria();
	    $criteria->addColumnCondition(array('t.state'=>USER_STATE_UNVERIFY));
	    $userCount = User::model()->count($criteria);
	    
	    $criteria = new CDbCriteria();
	    $criteria->addColumnCondition(array('t.state'=>COMMENT_STATE_DISABLED));
	    $commentCount = Comment::model()->count($criteria);
	    
	    $this->render('index', array(
	        'unverifyPostCount' => $unverifyPostCount,
	        'hidePostCount' => $hidePostCount,
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