<?php
class MemberUser extends User
{
    /**
     * Returns the static model of the specified AR class.
     * @return MemberUser the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}