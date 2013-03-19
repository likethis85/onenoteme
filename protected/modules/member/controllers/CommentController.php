<?php
class CommentController extends MemberController
{
    public function filters()
    {
        return array(
            'ajaxOnly + delete',
            'postOnly + delete',
        );
    }
    public function actionIndex($page = 1)
    {
        $count = 10;
        $pages = new CPagination($this->user->commentCount);
        $pages->setPageSize($count);
        $offset = ($pages->currentPage - 1) * $count;
        
        $comments = $this->user->comments(array(
            'with' => 'post',
            'offset' => $offset,
            'limit' => $count,
            'order' => 'comments.create_time desc',
        ));
        
        $this->breadcrumbs[] = $this->title = $this->siteTitle = '我的评论';
        $this->menu = 'comment';
        $this->render('list', array(
            'comments' => $comments,
            'pages' => $pages,
        ));
    }
    
    public function actionDelete($id)
    {
        $id = (int)$id;
        if ($id > 0) {
            $model = MemberComment::model()->findByPk($id, 'user_id = :userid', array(':userid'=>$this->userID));
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
}
