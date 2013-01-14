<?php

class DefaultController extends AppController
{
	public function actionIndex()
	{
	}

	public function actionError()
	{
	    $error = app()->errorHandler->error;
	    if ($error) {
	        $this->render('/system/error', $error);
	    }
	}
	
}