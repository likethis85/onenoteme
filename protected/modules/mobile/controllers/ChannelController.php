<?php

class ChannelController extends MobileController
{
    public function filters()
    {
        return array(
            array(
                'COutputCache + posts',
                'duration' => param('mobile_post_list_cache_expire'),
                'varyByParam' => array('id', 'page'),
            ),
        );
    }
    
	public function actionIndex($id, $page = 1)
	{
	    $this->redirect(url('mobile/channel/posts', array('id'=>$id, 'page'=>$page)));
	}
	
	public function actionPosts($id)
	{
	    $id = (int)$id;
	    $data = self::fetchLatestPosts($id);
	    
	    $this->setSiteTitle('');
	    cs()->registerMetaTag('all', 'robots');
	    $this->render('/post/list', $data);
	}

	private static function fetchLatestPosts($id)
	{
	    $id = (int)$id;
	    $criteria = new CDbCriteria();
	    $criteria->order = 't.istop desc, t.create_time desc';
	    $criteria->limit = param('mobile_post_list_page_count');
	    $criteria->scopes = array('published');
	    $criteria->addColumnCondition(array('channel_id' => $id));
	
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