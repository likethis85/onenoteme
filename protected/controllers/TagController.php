<?php
class TagController extends Controller
{
    public function actionList()
    {
        $cmd = app()->getDb()->createCommand()
            ->order('id asc');
        $tags = DTag::model()->findAll($cmd);
        
        $this->render('list', array('tags' => $tags));
    }
    
    public function actionPosts($tag)
    {
        $tag = urldecode($tag);
        $cmd = app()->getDb()->createCommand()
            ->join('{{post2tag}} pt', 't.id = pt.post_id')
            ->join('{{tag}} tag', 'tag.id = pt.tag_id')
            ->where('tag.name = :tagname', array(':tagname' => $tag));
        $posts = DPost::model()->findAll($cmd);
        $this->render('posts', array('posts'=>$posts));
    }
}