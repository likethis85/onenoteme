<?php
class FeedController extends Controller
{
    const POST_COUNT = 200;
    
    public function init()
    {
        parent::init();
        header('Content-Type:application/xml; charset=' . app()->charset);
    }
    
    public function filters()
    {
        $duration = 600;
        return array(
            array(
                'COutputCache + index',
                'duration' => $duration,
            )
        );
    }
    
    public function actionIndex()
    {
        $cmd = app()->getDb()->createCommand()
            ->where('state = :enabled', array(':enabled'=>POST_STATE_ENABLED));
        
        $rows = self::fetchPosts($cmd);
        
        $this->renderPartial('rss', array(
            'rows' => $rows,
            'feedname' => app()->name,
        ));
    }
    
    private static function fetchPosts(CDbCommand $cmd)
    {
        $cmd->from(TABLE_POST)
            ->select(array('id', 'title', 'thumbnail_pic', 'bmiddle_pic', 'content', 'create_time'))
            ->order(array('create_time desc', 'id desc'))
            ->limit(self::POST_COUNT);
            
        $rows = $cmd->queryAll();
        return $rows;
    }
}