<?php
/**
 *
 * @author chendong
 * @property string $deleteUrl
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

    public function getDeleteUrl()
    {
        return aurl('member/post/delete', array('id'=>$this->id));
    }
    
    public static function fetchFavoritePosts($userID, $page = 1, $count = 15)
    {
        $userID = (int)$userID;
        $page = (int)$page;
        $count = (int)$count;
        $offset = ($page - 1) * $pageSize;
        $ids = app()->getDb()->createCommand()
            ->select('post_id')
            ->from(TABLE_POST_FAVORITE)
            ->where('user_id = :userid', array(':userid' => $userID))
            ->order('create_time desc')
            ->offset($offset)
            ->limit($count)
            ->queryColumn();
    
        $criteria = new CDbCriteria();
        $criteria->addInCondition('id', $ids);
        $criteria->addColumnCondition(array('t.state' => POST_STATE_ENABLED));
    
        $models = MemberPost::model()->findAll($criteria);
        return $models;
    }
}