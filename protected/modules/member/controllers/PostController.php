<?php
class PostController extends MemberController
{
    public function actionIndex()
    {
        $this->breadcrumbs[] = $this->title = $this->siteTitle = '我的段子';
        $this->channel = 'post';
        $this->render('index');
    }
    
    public function actionFavorite()
    {
        $this->breadcrumbs[] = $this->title = $this->siteTitle = '我的收藏';
        $this->channel = 'favorite';
        $this->render('favorite');
    }
}