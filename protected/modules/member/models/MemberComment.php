<?php
/**
 *
 * @author chendong
 * @property string $deleteUrl
 */
class MemberComment extends Comment
{
    /**
     * Returns the static model of the specified AR class.
     * @return MemberComment the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }


    public function getDeleteUrl()
    {
        return aurl('member/post/delete', array('id'=>$this->id));
    }
}

