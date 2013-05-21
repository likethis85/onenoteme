<?php
class PostController extends MemberController
{
    public function filters()
    {
        return array(
            'ajaxOnly  + delete, unlike',
            'postOnly + delete, unlike',
        );
    }
    
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
        $this->menu = 'post';
        $this->render('list', array(
            'posts' => $posts,
            'pages' => $pages,
        ));
    }
    
    public function actionFavorite()
    {
        $count = 25;
        $pages = new CPagination($this->user->favoritePostsCount);
        $pages->setPageSize($count);
        $posts = $this->user->getFavoritePosts($pages->currentPage+1, $count);
        
        $this->breadcrumbs[] = $this->title = $this->siteTitle = '我的收藏';
        $this->menu = 'favorite';
        $this->render('favorite', array(
            'posts' => $posts,
            'pages' => $pages,
        ));
    }
    
    public function actionDelete($id)
    {
        $id = (int)$id;
        if ($id > 0) {
            $model = MemberPost::model()->findByPk($id, 'user_id = :userid', array(':userid'=>$this->getUserID()));
            if ($model === null) {
                $data['errno'] = CD_YES;
                $data['error'] = '段子不存在';
            }
            else {
                $data['errno'] = $model->delete() ? CD_NO : CD_YES;
            }
        }
        else {
            $data['errno'] = CD_YES;
            $data['error'] = '非法请求';
        }
        
        echo CJSON::encode($data);
        exit(0);
    }

    public function actionUnlike($id)
    {
        $id = (int)$id;
        $conditions = array('and', 'user_id = :userid', 'post_id = :postid');
        $params = array(':userid'=>$this->getUserID(), ':postid'=>$id);
        $result = app()->getDb()->createCommand()
            ->delete(TABLE_POST_FAVORITE, $conditions, $params);
    
        if ($result > 0) {
            $counters = array('favorite_count' => 1);
            $result = Post::model()->updateCounters($counters, 'id = :postid', array(':postid' => $id));
            $data = array('errno' => CD_NO);
        }
        
        $data = array(
            'errno' => $result ? CD_NO : CD_YES,
        );
    
        echo CJSON::encode($data);
        exit(0);
    }
    
}


