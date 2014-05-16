<?php

class DefaultController extends MobileController
{
    public function filters()
    {
        return array(
            array(
                'COutputCache + index',
                'duration' => 120,
                'varyByParam' => array('page'),
                'varyByExpression' => array(request(), 'getServerName'),
            ),
        );
    }
    
	public function actionIndex()
	{
	    $this->forward('channel/latest');
	}

	public function actionError()
	{
	    $error = app()->errorHandler->error;
	    if ($error) {
	        if ($error['code'] == 404)
	            $this->redirect($this->getHomeUrl());
	        $this->pageTitle = 'Error ' . $error['code'];
	        $this->render('/system/error', $error);
	    }
	}
	
}


