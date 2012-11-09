<?php
class PostController extends MemberController
{
    public function actionIndex()
    {
        $count = 15;
        $pages = new CPagination($this->user->postCount);
        $pages->setPageSize($count);
        $offset = ($pages->currentPage - 1) * $count;
        
        $posts = $this->user->posts(array(
            'offset' => $offset,
            'limit' => $count,
            'order' => 'posts.create_time desc',
        ));
        
        $this->breadcrumbs[] = $this->title = $this->siteTitle = '我的段子';
        $this->channel = 'post';
        $this->render('list', array(
            'posts' => $posts,
            'pages' => $pages,
        ));
    }
    
    public function actionFavorite()
    {
        $count = 15;
        $pages = new CPagination($this->user->favoritePostsCount);
        $pages->setPageSize($count);
        
        $posts = $this->user->getFavoritePosts($pages->currentPage, $count);
        
        $this->breadcrumbs[] = $this->title = $this->siteTitle = '我的收藏';
        $this->channel = 'favorite';
        $this->render('favorite', array(
            'posts' => $posts,
            'pages' => $pages,
        ));
    }
    
}