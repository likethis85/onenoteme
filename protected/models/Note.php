<?php
class Note extends DActiveRecord
{
    public static function model($class = __CLASS__)
    {
        return parent::model($class);
    }
    
    public function table()
    {
        return '{{note}}';
    }
    
    public function pk()
    {
        return 'id';
    }
    
    public function columns()
    {
        return array('id', 'book_id', 'title', 'content', 'create_time', 'update_time');
    }
}