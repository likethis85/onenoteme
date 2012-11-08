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

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array_merge(parent::relations(), array(
            'profile' => array(self::HAS_ONE, 'MemberUserProfile', 'user_id'),
        ));
    }
}