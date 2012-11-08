<?php
class CommentController extends MemberController
{
    public function actionIndex()
    {
        $this->breadcrumbs[] = $this->title = $this->siteTitle = '我的评论';
        $this->channel = 'comment';
        $this->render('index');
    }
}