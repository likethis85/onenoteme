<?php
/**
 *
 * @author chendong
 * @property string $deleteLink
 * @property string $editLink
 * @property string $unlikeLink
 * @property string $stateHtml
 */
class MemberPost extends Post
{
    /**
     * Returns the static model of the specified AR class.
     * @return MemberPost the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function getDeleteLink()
    {
        $html = '';
        if ($this->state == POST_STATE_UNVERIFY) {
            $url = aurl('member/post/delete', array('id'=>$this->id));
            $html = l('<i class="icon-trash icon-white"></i>', 'javascript:void(0);', array('class'=>'btn btn-mini btn-danger btn-delete', 'data-url'=>$url));
        }
        
        return $html;
    }

    public function getEditLink()
    {
        $html = '';
        if ($this->state == POST_STATE_UNVERIFY) {
            $url = aurl('member/post/create', array('id'=>$this->id));
            $html = l('<i class="icon-edit icon-white"></i>', $url, array('class'=>'btn btn-mini btn-primary'));
        }
        
        return $html;
    }
    
    public function getStateHtml()
    {
        $classes = array(
            POST_STATE_ENABLED => 'label label-success',
            POST_STATE_DISABLED => 'label label-inverse',
            POST_STATE_UNVERIFY => 'label label-important',
            POST_STATE_TRASH => 'label',
        );
        $class = $classes[$this->state];
        
        return sprintf('<span class="%s">%s</span>', $class, $this->getStateLabel());
    }
    
    public function getUnlikeLink()
    {
        $url = aurl('member/post/unlike', array('id'=>$this->id));
        $html = l('<i class="icon-trash icon-white"></i>', 'javascript:void(0);', array('class'=>'btn btn-mini btn-danger btn-delete', 'data-url'=>$url));
        
        return $html;
    }
    
    public static function fetchFavoritePosts($userID, $page = 1, $count = 15)
    {
        $userID = (int)$userID;
        $page = (int)$page;
        $count = (int)$count;
        $offset = ($page - 1) * $count;
        $ids = app()->getDb()->createCommand()
            ->select('post_id')
            ->from(TABLE_POST_FAVORITE)
            ->where('user_id = :userid', array(':userid' => $userID))
            ->order('create_time desc')
            ->offset($offset)
            ->limit($count)
            ->queryColumn();
    
        $criteria = new CDbCriteria();
        $criteria->select = array('id', 'title', 'state');
        $criteria->addInCondition('id', $ids);
        $criteria->addColumnCondition(array('t.state' => POST_STATE_ENABLED));
    
        $models = MemberPost::model()->findAll($criteria);
        foreach ($models as $model)
            $temp[$model->id] = $model;
        
        $models = null;
        $ids = array_intersect($ids, array_keys($temp));
        foreach ($ids as $id)
            $models[$id] = $temp[$id];
            
        return $models;
    }
}