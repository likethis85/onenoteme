<?php

class DefaultController extends MobileController
{
	public function actionIndex()
	{
	    $data = self::fetchLatestPosts();
	    
	    $this->setSiteTitle('');
	    cs()->registerMetaTag('all', 'robots');
	    $this->render('index', $data);
	}

	public function actionError()
	{
	    $error = app()->errorHandler->error;
	    if ($error) {
	        $this->pageTitle = 'Error ' . $error['code'];
	        $this->render('/system/error', $error);
	    }
	}
	
	private static function fetchLatestPosts()
	{
	    $criteria = new CDbCriteria();
	    $criteria->order = 't.istop desc, t.create_time desc';
	    $criteria->limit = param('postCountOfPage');
	    $criteria->scopes = array('homeshow', 'published');
	
	    $count = MobilePost::model()->count($criteria);
	    $pages = new CPagination($count);
	    $pages->setPageSize(param('mobile_post_list_page_count'));
	    $pages->applyLimit($criteria);
	    $posts = MobilePost::model()->findAll($criteria);
	
	    return array(
	        'models' => $posts,
	        'pages' => $pages,
	    );
	}
}