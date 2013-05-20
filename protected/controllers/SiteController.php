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
    
    public function actionIndex($page = 1)
    {
        $this->forward('channel/hot');
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
