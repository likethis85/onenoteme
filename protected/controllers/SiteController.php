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
                'varyByParam' => array('page', 's'),
                'varyByExpression' => array(user(), 'getIsGuest'),
                'requestTypes' => array('GET'),
            ),
            array(
                'COutputCache + index',
                'duration' => 120,
                'varyByParam' => array('page', 's'),
                'requestTypes' => array('POST'),
            ),
        );
    }
    
    public function actionIndex($page = 1, $s = POST_LIST_STYLE_LINE)
    {
        $s = strip_tags(trim($s));
        $limit = ($s == POST_LIST_STYLE_WATERFALL) ? param('waterfall_post_count_page') : param('line_post_count_page');
        
        $channels = array(CHANNEL_FUNNY);
        $mediaTypes = array(MEDIA_TYPE_TEXT, MEDIA_TYPE_IMAGE);
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(array('t.state'=>POST_STATE_ENABLED));
        $criteria->addInCondition('channel_id', $channels);
        
        if ($s != POST_LIST_STYLE_WATERFALL)
            $mediaTypes[] = MEDIA_TYPE_VIDEO;
        
        $criteria->addInCondition('media_type', $mediaTypes);
        $criteria->order = 't.istop desc, t.create_time desc, t.id desc';
        $criteria->limit = $limit;
        
        $duration = 60*60*24;
        $count = Post::model()->cache($duration)->count($criteria);
        $pages = new CPagination($count);
        $pages->setPageSize($limit);
        $pages->applyLimit($criteria);

        if ($pages->getCurrentPage() < $_GET[$pages->pageVar]-1)
            $models = array();
        else
            $models = Post::model()->findAll($criteria);
        
        $this->channel = 'home';
        if (request()->getIsAjaxRequest()) {
            $view = ($s == POST_LIST_STYLE_WATERFALL) ? '/post/mixed_list' : '/post/line_list';
            $this->renderPartial($view, array(
                'models' => $models,
                'pages' => $pages,
            ));
        } else {
            $this->pageTitle = param('sitename') . ' - ' . param('shortdesc');
            $this->setKeywords(param('home_index_keywords'));
            $this->setDescription(param('home_index_description'));
            
            $mobileUrl = ($page > 1) ? aurl('mobile/default/index', array('page'=>$page)) : $mobileUrl = aurl('mobile/default/index');;
            cs()->registerMetaTag('format=html5;url=' . $mobileUrl, null, 'mobile-agent');
            
            $view = ($s == POST_LIST_STYLE_WATERFALL) ? 'fall_index' : 'grid_index';
            $this->render($view, array(
                'models' => $models,
                'pages' => $pages,
            ));
        }
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
}
