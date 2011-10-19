<?php
class Book extends DActiveRecord
{
    public static function model($class = __CLASS__)
    {
        return parent::model($class);
    }
    
    public function table()
    {
        return '{{book}}';
    }

    public function pk()
    {
        return 'id';
    }
    
    public function columns()
    {
        return array('id', 'name', 'user_id', 'create_time', 'isdefault');
    }
}