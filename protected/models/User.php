<?php
class User extends DActiveRecord
{
    public static function model($class = __CLASS__)
    {
        return parent::model($class);
    }
    
    public function table()
    {
        return '{{user}}';
    }

    public function pk()
    {
        return 'id';
    }
    
    public function columns()
    {
        return array('id', 'email', 'name', 'password', 'create_time', 'create_time', 'state');
    }
}