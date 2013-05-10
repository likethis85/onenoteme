<?php
class MobileComment extends Comment
{
    /**
     * Returns the static model of the specified AR class.
     * @return MobileComment the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    public function getReplyUrl()
    {
        return aurl('mobile/comment/create', array('id'=>$this->id));
    }
    
    public function getSupportUrl()
    {
        return aurl('mobile/comment/support', array('id'=>$this->id));
    }
    
    public function getAgainstUrl()
    {
        return aurl('mobile/comment/against', array('id'=>$this->id));
    }

    public static function fetchListByPostID($postid, $page = 1, $count = null)
	{
	    $postid = (int)$postid;
	    $page = (int)$page;
	    $count = (int)$count;
	    if ($count === 0)
	        $count = param('comment_count_page');
	    
	    $criteria = new CDbCriteria();
	    $criteria->order = 'create_time asc';
	    $criteria->limit = $count;
	    $offset = ($page - 1) * $criteria->limit;
	    $criteria->offset = $offset;
	    $criteria->addColumnCondition(array(
            'post_id' => $postid,
            'state' => COMMENT_STATE_ENABLED,
	    ));
	
	    $comments = self::model()->findAll($criteria);
	    return $comments;
	}
}