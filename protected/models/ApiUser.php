<?php
class ApiUser extends User
{

    /**
     * Returns the static model of the specified AR class.
     * @return ApiUser the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}