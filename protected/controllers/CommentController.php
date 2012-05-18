<?php
class CommentController extends Controller
{
    public function filters()
    {
        return array(
            'ajaxOnly + create',
            'postOnly + create',
        );
    }
    
    public function actionList($pid)
    {
        
    }
    
    public function actionCreate()
    {
        $c = new Comment();
        $c->post_id = $_POST['postid'];
        $c->content = $_POST['content'];
        $c->user_id = user()->isGuest ? 0 : user()->id;
        $c->user_name = user()->isGuest ? '' : user()->name;
        $c->state = (app()->session['state'] >= User::STATE_EDITOR) ? Comment::STATE_ENABLED : Comment::STATE_DISABLED;
        echo (int)$c->save();
        exit(0);
    }
}