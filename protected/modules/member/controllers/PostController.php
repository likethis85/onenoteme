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
        $pages = new CPagination($this->user->favoritePostsCount);
        $pages->setPageSize(15);
        
        $posts = $this->user->getFavoritePosts($pages->currentPage, $pages->pageSize);
        
        $this->breadcrumbs[] = $this->title = $this->siteTitle = '我的收藏';
        $this->channel = 'favorite';
        $this->render('favorite', array(
            'posts' => $posts,
            'pages' => $pages,
        ));
    }
    
}