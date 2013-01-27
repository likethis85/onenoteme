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
        return parent::fetchListByPostID($postid, $page, $count);
    }
}