<?php
class SitemapController extends Controller
{
    public function actionSitemap()
    {
        header('Content-Type:application/xml; charset=' . app()->charset);
        
        $duration = 600;
        $cmd = app()->getDb()->cache($duration)->createCommand()
            ->select('id')
            ->from(TABLE_POST)
            ->where('state = ' . POST_STATE_ENABLED)
            ->order('id desc')
            ->limit(2000);
        $posts = $cmd->queryAll();
        $this->renderPartial('sitemap', array(
            'posts' => $posts
        ));
        app()->end();
        exit(0);
    }
}
