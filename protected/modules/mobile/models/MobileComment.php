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
}