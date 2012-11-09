<?php
class CommentController extends MemberController
{
    public function actionIndex($page = 1)
    {
        $count = 15;
        $pages = new CPagination($this->user->commentCount);
        $pages->setPageSize($count);
        $offset = ($pages->currentPage - 1) * $count;
        
        $comments = $this->user->comments(array(
            'with' => 'post',
            'offset' => $offset,
            'limit' => $count,
        ));
        
        $this->breadcrumbs[] = $this->title = $this->siteTitle = '我的评论';
        $this->channel = 'comment';
        $this->render('index', array(
            'comments' => $comments,
            'pages' => $pages,
        ));
    }
}