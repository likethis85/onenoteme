<?php
class SiteController extends Controller
{
    public function filters()
    {
        return array(
            array(
                'COutputCache + bdmap, links',
                'duration' => 600,
                'varyByParam' => array('page'),
            ),
            array(
                'COutputCache + index',
                'duration' => 120,
                'varyByParam' => array('page'),
                'varyByExpression' => array(user(), 'getIsGuest'),
                'requestTypes' => array('GET'),
            ),
        );
    }
    
    public function actionIndex($page = 1)
    {
        $this->channel = 'siteindex';
        $this->setSitePageTitle('');
        $this->setKeywords(p('home_index_keywords'));
        $this->setDescription(p('home_index_description'));

        $mobileUrl = ($page > 1) ? aurl('mobile/channel/latest', array('page'=>$page)) : aurl('mobile/channel/latest');
        cs()->registerMetaTag('format=html5;url=' . $mobileUrl, null, 'mobile-agent');
    
        $criteria = new CDbCriteria();
        $criteria->scopes = array('homeshow', 'published');
        $criteria->addInCondition('t.channel_id', array(CHANNEL_FUNNY, CHANNEL_FOCUS));
        $criteria->order = 't.istop desc, t.create_time desc';
        $criteria->limit = (int)p('line_post_count_page');
    
        $data = self::fetchPosts($criteria);
        $this->render('index', array(
            'models' => $data['models'],
            'pages' => $data['pages'],
        ));
    }
    
    private static function fetchPosts(CDbCriteria $criteria)
    {
        $duration = 60*60*24;
	    $cacheID = md5(var_export($criteria->toArray(), true));
	    $redis = redis();
	    if ($redis) {
	        $count = $redis->get($cacheID);
	        if ($count === false) {
	            $count = Post::model()->count($criteria);
	            $redis->set($cacheID, $count, $duration);
	        }
	    }
	    else
	        $count = Post::model()->count($criteria);
	    
        $pages = new CPagination($count);
        $pages->setPageSize($criteria->limit);
        $pages->applyLimit($criteria);
    
        $models = Post::model()->findAll($criteria);
    
        return array(
            'models' => $models,
            'pages' => $pages,
        );
    }
    
    public function actionBdmap()
    {
        $pageSize = 100;
        $duration = 600;
        $count = app()->getDb()->cache($duration)->createCommand()
            ->select('count(*)')
            ->from(TABLE_POST)
            ->where('state = :enabled', array(':enabled' => POST_STATE_ENABLED))
            ->queryScalar();
        
        $pages = new CPagination($count);
        $pages->setPageSize($pageSize);
        $offset = ($pages->getCurrentPage() - 1) * $pageSize;
        
        $cmd = app()->getDb()->cache($duration)->createCommand()
            ->select('id, title, content, tags')
            ->from(TABLE_POST)
            ->where('state = ' . POST_STATE_ENABLED)
            ->order('id desc')
            ->limit($pageSize)
            ->offset($offset);
        
        $posts = $cmd->queryAll();
        
        $this->pageTitle = '百度地图 - ' . app()->name;
        $this->render('baidumap', array(
            'posts' => $posts,
            'pages' => $pages,
        ));
    }

    public function actionLinks()
    {
        $this->render('links');
    }

    public function actionError()
    {
        $error = app()->errorHandler->error;
        if ($error) {
            if (request()->getIsAjaxRequest()) {
                $data['code'] = $error['code'];
                $data['message'] = $error['message'];
                echo CJSON::encode($data);
                exit(0);
            }
            else {
                $this->layout = 'error';
                $this->pageTitle = '错误 ' . $error['code'] . ' - ' . app()->name;
                $this->render('/system/error', $error);
            }
        }
    }

    public function actionUa()
    {
        echo $_SERVER['HTTP_USER_AGENT'];
        exit;
    }
}
